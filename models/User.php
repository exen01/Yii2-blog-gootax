<?php

namespace app\models;

use Yii;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string|null $profile
 * @property string auth_key
 *
 * @property Post[] $posts
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['username', 'password', 'email'], 'required'],
            [['profile'], 'string'],
            [['username', 'password', 'email'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'email' => 'Email',
            'profile' => 'Profile',
        ];
    }

    /**
     * Gets query for [[Posts]].
     *
     * @return ActiveQuery
     */
    public function getPosts(): ActiveQuery
    {
        return $this->hasMany(Post::class, ['author_id' => 'id']);
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id): ?IdentityInterface
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null): ?IdentityInterface
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string current user ID
     */
    public function getId(): int|string
    {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey(): string
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return bool if auth key is valid for current user
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @throws Exception
     */
    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }

    /**
     * Validates the password.
     *
     * @param string $password the password currently being validated.
     */
    public function validatePassword(string $password): bool
    {
        $hash = $this->password;

        return Yii::$app->getSecurity()->validatePassword($password, $hash);
    }

}
