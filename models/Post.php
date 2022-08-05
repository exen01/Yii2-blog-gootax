<?php

namespace app\models;

use phpDocumentor\Reflection\Types\This;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%post}}".
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string|null $tags
 * @property int $status
 * @property int|null $create_time
 * @property int|null $update_time
 * @property int $author_id
 *
 * @property User $author
 * @property Comment[] $comments
 */
class Post extends ActiveRecord
{
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_ARCHIVED = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%post}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['title', 'content', 'status'], 'required'],
            ['title', 'string', 'max' => 128],
            ['status', 'in', 'range' => array(1, 2, 3)],
            ['tags', 'match', 'pattern' => '/^[\w\s,]+$/',
                'message' => 'В тегах можно использовать только буквы.'],
            ['tags', 'normalizeTags'],
            [['title', 'status'], 'safe', 'on' => 'search'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'tags' => 'Tags',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'author_id' => 'Author ID',
        ];
    }

    /**
     * Gets query for [[Author]].
     *
     * @return ActiveQuery
     */
    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'author_id']);
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return ActiveQuery
     */
    public function getComments(): ActiveQuery
    {
        // TODO add order comments.create_time DESC
        return $this->hasMany(Comment::class, ['post_id' => 'id'])->filterWhere(['status' => Comment::STATUS_APPROVED]);
    }

    /**
     * Gets count of comments of post.
     *
     * @return bool|int|string|null count of comments.
     */
    public function getCommentsCount(): bool|int|string|null
    {
        return $this->getComments()->count();
    }

    /**
     * @return array a list of links that point to the post list filtered by every tag of this post.
     */
    public function getTagLinks(): array
    {
        $links = [];
        foreach (Tag::string2array($this->tags) as $tag) {
            $links[] = Html::a(Html::encode($tag), ['post/index', 'tag' => $tag]);
        }

        return $links;
    }

    /**
     * Normalizes the tags introduced by the user.
     *
     * @param $attribute
     * @param $params
     * @return void
     */
    public function normalizeTags($attribute, $params): void
    {
        $this->tags = Tag::array2string(array_unique(Tag::string2array($this->tags)));
    }


    public function getUrl(): string
    {
        return Url::to(['post/view', 'id' => $this->id, 'title' => $this->title]);
    }

    /**
     * Adds a comment to the post.
     *
     * @param $comment Comment Added comment.
     * @return bool
     */
    public function addComment(Comment $comment): bool
    {
        if (isset(Yii::$app->params['commentNeedApproval'])) {
            $comment->status = Comment::STATUS_PENDING;
        } else {
            $comment->status = Comment::STATUS_APPROVED;
        }

        $comment->post_id = $this->id;
        return $comment->save();
    }

    /**
     * This is invoked before the record is saved.
     * @return boolean whether the record should be saved.
     */
    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->create_time = $this->update_time = time();
                $this->author_id = Yii::$app->user->id;
            } else {
                $this->update_time = time();
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * This is invoked after the record is saved.
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        Tag::updateFrequency($this->_oldTags, $this->tags);
    }

    private string $_oldTags = '';

    /**
     * This is invoked when a record is populated with data from a find() call.
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->_oldTags = $this->tags;
    }

    /**
     * This is invoked after the record is deleted.
     */
    public function afterDelete()
    {
        parent::afterDelete();
        Comment::deleteAll('post_id=' . $this->id);
        Tag::updateFrequency($this->tags, '');
    }
}
