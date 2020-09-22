<?php

namespace app\backend\models;

use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\user\models\User;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

class PhongKhamThongBao extends ActiveRecord
{
    const THONG_BAO_DIRECT_SALE = 1;
    const THONG_BAO_PHONG_KHAM = 2;
    public static function tableName()
    {
        return 'phong_kham_thong_bao';
    }

    public function behaviors()
    {
        return [
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
            [['name', 'content', 'user_id'], 'required'],
            [['type', 'user_id', 'customer_id'], 'integer'],
            [['name', 'description'], 'string', 'max' => 255],
            [['content'], 'string']
        ];
    }

    public function getUserHasOne()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getCustomerHasOne()
    {
        return $this->hasOne(Dep365CustomerOnline::class, ['id' => 'customer_id']);
    }
}
