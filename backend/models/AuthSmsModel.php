<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tran
 * Date: 07-05-2019
 * Time: 01:52 PM
 */

namespace backend\models;

use cheatsheet\Time;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use Yii;

class AuthSmsModel extends ActiveRecord
{
    const TIME_EXPIRE = 30;
    const USED = 1;
    const CUSTOMER_LOGIN = 'customer_login';

    public static function tableName()
    {
        return 'user_auth_sms';
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
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at']
                ],
                'value' => time()
            ],
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['expire_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['expire_at']
                ],
                'value' => function () {
                    return time() + self::TIME_EXPIRE * Time::SECONDS_IN_A_MINUTE;
                }
            ]
        ];
    }

    public function rules()
    {
        return [
            [['user_id', 'type', 'pin'], 'required'],
            [['user_id'], 'integer'],
            [['type'], 'string'],
            [['pin'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('backend', 'Nhân viên'),
            'type' => Yii::t('backend', 'Loại'),
            'pin' => Yii::t('backend', 'Mã pin'),
        ];
    }
}
