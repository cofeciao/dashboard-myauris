<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

class ApiContact extends ActiveRecord
{
    const TYPE_MYAURIS = 1;

    public static function tableName()
    {
        return 'api_contact';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                'value' => time(),
            ],
        ];
    }

    public function rules()
    {
        return [
            [['type', 'phone'], 'required'],
            [['content'], 'string'],
            [['type', 'status', 'created_at'], 'integer'],
            [['fullname', 'phone', 'title', 'ip'], 'string', 'max' => 255],
            ['type', 'in', 'range' => [self::TYPE_MYAURIS]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'fullname' => Yii::t('backend', 'Họ tên'),
            'phone' => Yii::t('backend', 'Số điện thoại'),
            'title' => Yii::t('backend', 'Tiêu đề'),
            'content' => Yii::t('backend', 'Nội dung'),
            'ip' => Yii::t('backend', 'Ip'),
            'type' => Yii::t('backend', 'Type'),
            'status' => Yii::t('backend', 'Status'),
            'created_at' => Yii::t('backend', 'Created At'),
        ];
    }
}
