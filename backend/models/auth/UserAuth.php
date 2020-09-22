<?php

namespace backend\models\auth;

use cheatsheet\Time;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

class UserAuth extends ActiveRecord
{
    const NOT_USED = 0;
    const USED = 1;
    const TIME_EXPIRED = 30;
    const MIN = 100000;
    const MAX = 999999;
    const TYPE_USER_LOGIN = 'user-login';

    public static function tableName()
    {
        return 'user_auth';
    }

    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['used']
                ],
                'value' => 0
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['expired_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['expired_at'],
                ],
                'value' => function () {
                    return time() + self::TIME_EXPIRED * Time::SECONDS_IN_A_MINUTE;
                }
            ],
            [
                'class' => AttributeBehavior::class,
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
            [['user_id', 'pin'], 'required'],
            [['user_id'], 'integer'],
            [['pin'], 'integer', 'min' => 100000, 'max' => 999999]
        ];
    }

    public function getUserHasOne()
    {
        return $this->hasOne(\backend\modules\user\models\User::class, ['id' => 'user_id']);
    }
}
