<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%lookup}}".
 *
 * @property int $id
 * @property string $name
 * @property int $code
 * @property string $type
 * @property int $position
 */
class Lookup extends ActiveRecord
{
    private static array $_items = array();

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%lookup}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'code', 'type', 'position'], 'required'],
            [['code', 'position'], 'integer'],
            [['name', 'type'], 'string', 'max' => 128],
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
            'code' => 'Code',
            'type' => 'Type',
            'position' => 'Position',
        ];
    }

    /**
     * Gets string status names array for given type.
     *
     * @param $type string type of status.
     * @return mixed array of status names.
     */
    public static function items(string $type): mixed
    {
        if (!isset(self::$_items[$type])) {
            self::loadItems($type);
        }

        return self::$_items[$type];
    }

    /**
     * Gets string status name for given type.
     *
     * @param $type string type of status.
     * @param $code int code of status.
     * @return mixed status name.
     */
    public static function item(string $type, int $code): mixed
    {
        if (!isset(self::$_items[$type])) {
            self::loadItems($type);
        }

        return self::$_items[$type][$code] ?? false;
    }

    /**
     * Loads statuses into an array.
     *
     * @param $type string type of status.
     * @return void
     */
    private static function loadItems(string $type): void
    {
        self::$_items[$type] = array();
        $models = self::findAll([
            'condition' => 'type=:type',
            'params' => array(':type' => $type),
            'order' => 'position',
        ]);
        foreach ($models as $model)
            self::$_items[$type][$model->code] = $model->name;
    }
}
