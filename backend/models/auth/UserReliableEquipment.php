<?php

namespace backend\models\auth;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class UserReliableEquipment extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%user_reliable_equipment}}";
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at']
                ],
                'value' => time()
            ]
        ];
    }

    public function rules()
    {
        return [
            [['user_id', 'computer_name'], 'required'],
            [['user_id'], 'integer'],
            [['computer_name'], 'string'],
        ];
    }
}
