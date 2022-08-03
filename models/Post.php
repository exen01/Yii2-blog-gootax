<?php

namespace app\models;

use phpDocumentor\Reflection\Types\This;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
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
        return $this->hasOne('User', ['id' => 'author_id']);
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

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        Tag::updateFrequency($this->_oldTags, $this->tags);
    }

    private string $_oldTags = '';

    public function afterFind()
    {
        parent::afterFind();
        $this->_oldTags = $this->tags;
    }
}
