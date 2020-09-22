<?php

namespace backend\models;

use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use Yii;

class UserSettings extends ActiveRecord
{
    const ACCEPT_AUTH_MESSAGE = 1;
    const ACCEPT_AUTH_MAIL = 1;

    public static function tableName()
    {
        return "{{%user_settings}}";
    }

    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['user_id']
                ],
                'value' => Yii::$app->user->getId()
            ]
        ];
    }

    public function rules()
    {
        return [
            [['auth_message', 'auth_mail'], 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'auth_message' => Yii::t('backend', 'Xác thực qua tin nhắn'),
            'auth_mail' => Yii::t('backend', 'Xác thực qua email'),
        ];
    }
}
