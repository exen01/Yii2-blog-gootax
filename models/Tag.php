<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%tag}}".
 *
 * @property int $id
 * @property string $name
 * @property int|null $frequency
 */
class Tag extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%tag}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['frequency'], 'integer'],
            [['name'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'frequency' => 'Frequency',
        ];
    }

    /**
     * Returns tag names and their corresponding weights.
     * Only the tags with the top weights will be returned.
     * @param integer $limit the maximum number of tags that should be returned
     * @return array weights indexed by tag names.
     */
    public static function findTagWeights(int $limit = 20): array
    {
        $models = Tag::find()
            ->limit($limit)
            ->all();

        $total = 0;
        foreach ($models as $model) {
            $total += $model->frequency;
        }

        $tags = [];
        if ($total > 0) {
            foreach ($models as $model) {
                $tags[$model->name] = 8 + (int)(16 * $model->frequency / ($total + 10));
            }
            ksort($tags);
        }

        return $tags;
    }

    /**
     * Converts a string of tags into an array.
     *
     * @param $tags string string of tags
     * @return array|false|string[] array of tags.
     */
    public static function string2array(string $tags): array|bool
    {
        return preg_split('/\s*,\s*/', trim($tags), -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * Converts an array of tags into a string.
     *
     * @param $tags array array of tags
     * @return string string of tags.
     */
    public static function array2string(array $tags): string
    {
        return implode(', ', $tags);
    }

    /**
     * Updates information about the frequency of tags.
     *
     * @param $oldTags string old tags in post.
     * @param $newTags string new tags in post.
     * @return void
     */
    public static function updateFrequency(string $oldTags, string $newTags): void
    {
        $oldTags = self::string2array($oldTags);
        $newTags = self::string2array($newTags);

        $updTags = array_values(array_diff($newTags, $oldTags));
        foreach ($updTags as $name) {
            $tag = Tag::findOne(['name' => $name]);
            if ($tag) {
                $tag->updateCounters(['frequency' => 1]);
            } else {
                $newTag = new Tag;
                $newTag->name = $name;
                $newTag->frequency = 1;
                $newTag->save();
            }
        }

        $removeTags = array_values(array_diff($oldTags, $newTags));
        if (empty($removeTags)) {
            return;
        }

        foreach ($removeTags as $name) {
            $tag = Tag::findOne(['name' => $name]);
            $tag?->updateCounters(['frequency' => -1]);
        }
    }
}
