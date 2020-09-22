<?php

namespace backend\modules\customer\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;

/**
 * This is the model class for table "customer_feedback".
 *
 * @property int $id
 * @property int $token_id
 * @property string $feedback
 * @property int $created_at
 */
class CustomerFeedback extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'customer_feedback';
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


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'rating', 'token_id'], 'required'],
            [['customer_id', 'token_id', 'rating', 'created_at'], 'integer'],
            [['feedback'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'customer_id' => Yii::t('backend', 'Khách hàng'),
            'rating' => Yii::t('backend', 'Đánh giá'),
            'token_id' => Yii::t('backend', 'Token ID'),
            'feedback' => Yii::t('backend', 'Phản hồi'),
            'created_at' => Yii::t('backend', 'Đánh giá lúc'),
        ];
    }

    public function getCustomerHasOne()
    {
        return $this->hasOne(Dep365CustomerOnline::class, ['id' => 'customer_id']);
    }
}
