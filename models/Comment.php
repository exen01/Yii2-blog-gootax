<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%comment}}".
 *
 * @property int $id
 * @property string $content
 * @property int $status
 * @property int|null $create_time
 * @property string $author
 * @property string $email
 * @property string|null $url
 * @property int $post_id
 *
 * @property Post $post
 */
class Comment extends ActiveRecord
{
    const STATUS_PENDING = 1;
    const STATUS_APPROVED = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%comment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['content', 'author', 'email'], 'required'],
            [['content'], 'string'],
            [['author', 'email', 'url'], 'string', 'max' => 128],
            [['status', 'create_time', 'post_id'], 'integer'],
            //[['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::class, 'targetAttribute' => ['post_id' => 'id']],
            ['email', 'email'],
            ['url', 'url'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'content' => 'Comment',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'author' => 'Name',
            'email' => 'Email',
            'url' => 'Website',
            'post_id' => 'Post',
        ];
    }

    /**
     * Gets query for [[Post]].
     *
     * @return ActiveQuery
     */
    public function getPost(): ActiveQuery
    {
        return $this->hasOne(Post::class, ['id' => 'post_id']);
    }

    /**
     * @param Post|null $post the post that this comment belongs to. If null, the method
     * will query for the post.
     * @return string the permalink URL for this comment
     */
    public function getUrl(Post $post = null): string
    {
        if ($post === null) {
            $post = $this->post;
        }

        return $post->getUrl() . '#c' . $this->id;
    }

    /**
     * @return string the hyperlink display for the current comment's author
     */
    public function getAuthorLink(): string
    {
        if (!empty($this->url)) {
            return Html::a(Html::encode($this->author), $this->url);
        } else {
            return Html::encode($this->author);
        }
    }

    /**
     * @return integer the number of comments that are pending approval
     */
    public static function getPendingCommentCount(): int
    {
        return Comment::find()
            ->where(['status' => self::STATUS_PENDING])
            ->count();
    }

    /**
     * @param integer $limit the maximum number of comments that should be returned
     * @return array the most recently added comments
     */
    public static function findRecentComments(int $limit = 10): array
    {
        return Comment::find()
            ->where(['status' => self::STATUS_APPROVED])
            ->limit($limit)
            ->orderBy(['create_time' => SORT_DESC])
            ->all();
    }

    /**
     * This is invoked before the record is saved.
     * @return boolean whether the record should be saved.
     */
    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->create_time = time();
            }
            return true;
        } else
            return false;
    }

    /**
     * Approves a comment.
     * @throws StaleObjectException
     */
    public function approve()
    {
        $this->status = Comment::STATUS_APPROVED;
        $this->update();
    }
}
