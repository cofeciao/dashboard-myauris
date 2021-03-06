<?php

namespace backend\modules\customer\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;

/**
 * This is the model class for table "dep365_send_sms".
 *
 * @property int $id
 * @property int $customer_id
 * @property string $sms_uuid
 * @property string $sms_text
 * @property int $sms_to
 * @property int $sms_time_send
 * @property int $sms_lanthu
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class Dep365SendSms extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dep365_send_sms';
    }

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
                'preserveNonEmptyValues' => true,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'sms_uuid', 'sms_text', 'sms_to', 'sms_lanthu'], 'required'],
            [['customer_id', 'sms_to', 'sms_time_send', 'sms_lanthu', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['sms_text'], 'string'],
            [['type'], 'string', 'max' => 255]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'customer_id' => Yii::t('backend', 'Customer ID'),
            'sms_uuid' => Yii::t('backend', 'Sms Uuid'),
            'sms_text' => Yii::t('backend', 'Sms Text'),
            'sms_to' => Yii::t('backend', 'Sms To'),
            'sms_time_send' => Yii::t('backend', 'Sms Time Send'),
            'sms_lanthu' => Yii::t('backend', 'Sms Lanthu'),
            'type' => Yii::t('backend', 'Type'),
            'status' => Yii::t('backend', 'Status'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
        ];
    }

    public function getUserCreatedBy($id)
    {
        $user = UserProfile::find()->where(['user_id' => $id])->one();
        return $user;
    }

    public function getUserUpdatedBy($id)
    {
        $user = UserProfile::find()->where(['user_id' => $id])->one();
        return $user;
    }
}
