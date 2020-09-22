<?php

namespace backend\modules\clinic\models;

use backend\modules\clinic\models\query\ListChupHinhQuery;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;

class ListChupHinh extends ActiveRecord
{
    const STATUS_PUBLISHED = 1;

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => time(),
            ],
        ];
    }

    public static function tableName()
    {
        return 'list_chuphinh_lichdieutri';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['status'], 'integer']
        ];
    }

    public static function find()
    {
        return new ListChupHinhQuery(get_called_class());
    }

    public static function getListChupHinh()
    {
        return self::find()->published()->all();
    }
}
