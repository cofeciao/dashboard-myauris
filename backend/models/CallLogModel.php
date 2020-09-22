<?php

namespace backend\models;

use backend\modules\customer\models\CustomerOnlineRemindCall;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "call_log".
 *
 * @property int $id
 * @property string $call_id
 * @property int $call_den_di
 * @property int $call_status
 * @property int $call_time
 * @property int $call_time_start
 * @property int $user_id
 * @property int $phone
 * @property int $customer_id
 * @property string $call_source
 * @property int $created_at
 * @property int $updated_at
 */
class CallLogModel extends \yii\db\ActiveRecord
{
    const INCOMING_CALL = 2;
    const AWAY_CALL = 1;

    const KH_BAT_MAY = 1;
    const KH_BAN = 2;
    const DO_HET_CHUONG_DI = 3;
    const NV_CANCEL = 4;
    const NV_TU_CHOI = 5;
    const DO_HET_CHUONG_DEN = 6;
    const KHACH_TAT_MAY = 7;
    const NV_BAT_MAY = 8;

    public static function getCallIncomingOrAway()
    {
        return [
            self::INCOMING_CALL => 'Gọi đến',
            self::AWAY_CALL => 'Gọi đi',
        ];
    }

    public static function getCallStatus()
    {
        return [
            self::KH_BAT_MAY => 'Khách hàng bắt máy',
            self::KH_BAN => 'Khách hàng bận',
            self::DO_HET_CHUONG_DI => 'Đổ hết chuông đi',
            self::NV_CANCEL => 'Nhân viên tự tắt máy',
            self::NV_TU_CHOI => 'Nhân viên từ chối',
            self::DO_HET_CHUONG_DEN => 'Đổ hết chuông đến',
            self::KHACH_TAT_MAY => 'Khách tắt máy',
            self::NV_BAT_MAY => 'Nhân viên bắt máy',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'call_log';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                //'preserveNonEmptyValues' => true,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => time(),
            ],
        ];
    }

    public function rules()
    {
        return [
            [['call_id'], 'required'],
            [['call_den_di', 'call_status', 'call_time', 'call_time_start', 'user_id', 'phone', 'customer_id', 'nhac_lich_id'], 'integer'],
            [['call_id', 'call_source'], 'string', 'max' => 255],
            ['call_id', 'unique',
                'targetClass' => '\backend\models\CallLogModel',
                'message' => 'Call Id đã tồn tại',
                'filter' => function ($query) {
                    $query->andWhere(['not', ['id' => $this->getId()]]);
                },
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'call_id' => Yii::t('backend', 'Call ID'),
            'call_den_di' => Yii::t('backend', 'Gọi đến/đi'),
            'call_status' => Yii::t('backend', 'Trạng thái'),
            'call_time' => Yii::t('backend', 'Thời gian gọi'),
            'call_time_start' => Yii::t('backend', 'Thời điểm gọi'),
            'user_id' => Yii::t('backend', 'Nhân viên nghe/gọi'),
            'phone' => Yii::t('backend', 'Phone'),
            'customer_id' => Yii::t('backend', 'Khách hàng'),
            'call_source' => Yii::t('backend', 'Ghi âm'),
            'created_at' => Yii::t('backend', 'Ngày gọi'),
            'updated_at' => Yii::t('backend', 'Updated At'),
        ];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCustomerRemindCallHasOne()
    {
        return $this->hasOne(CustomerOnlineRemindCall::class, ['id' => 'nhac_lich_id']);
    }
}
