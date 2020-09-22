<?php

namespace backend\modules\customer\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;

class CustomerDatHen extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'customer_dat_hen';
    }

    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_by',
                ],
                'value' => function () {
                    return Yii::$app->user->id;
                },
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                ],
                'value' => time(),
            ],
        ];
    }

    public function rules()
    {
        return [
            [['customer_id', 'user_id'], 'required'],
            [['customer_id', 'user_id', 'dat_hen_moi_cu', 'dat_hen_co_so', 'created_at', 'created_by'], 'integer'],
        ];
    }
}
