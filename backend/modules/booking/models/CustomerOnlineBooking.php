<?php

namespace backend\modules\booking\models;

use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\setting\models\Dep365CoSo;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;

/**
 * This is the model class for table "dep365_customer_online_booking".
 *
 * @property int $id
 * @property int $user_register_id
 * @property int $customer_type
 * @property int $time_id
 * @property int $coso_id
 * @property int $booking_date
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 */
class CustomerOnlineBooking extends \yii\db\ActiveRecord
{
    const MAX_BOOKING_IN_TIME = 2; /* SỐ LƯỢNG KHÁCH TIẾP TỐI ĐA TRONG 1 MÚI GIỜ */

    const CUSTOMER_FROM_ONLINE = 1;
    const CUSTOMER_FROM_WEBSITE = 2;
    const CUSTOMER_VITUAL = 3;

    const CUSTOMER_TYPE = [
        self::CUSTOMER_FROM_ONLINE => 'Khách từ tư vấn online',
        self::CUSTOMER_FROM_WEBSITE => 'Khách đăng ký mới từ website',
        self::CUSTOMER_VITUAL => 'Khách hàng ảo'
    ];

    const STATUS_PUBLISHED = 1;
    const STATUS_DISABLED = 0;

    const LIST_RANDOM_BY_DAY = [
        1 => [20, 25], /* NGÀY MAI */
        2 => [15, 20], /* NGÀY MỐT */
        3 => [18, 23], /* T7 - CN (3 TUẦN KẾ TIẾP) */
        4 => [10, 15], /* TRONG 1 TUẦN KẾ TIẾP */
        5 => [6, 10], /* TUẦN T2 ĐẾN TUẦN T4 */
        6 => [1, 5], /* THÁNG T2 */
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dep365_customer_online_booking';
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
            [['user_register_id', 'customer_type', 'time_id', 'coso_id', 'booking_date'], 'required'],
            [['user_register_id', 'customer_type', 'time_id', 'coso_id', 'booking_date', 'status', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['time_id'], 'checkTimeIdExist', 'message' => 'Lịch khám không tồn tại!'],
            [['time_id'], 'checkTimeIdEmpty', 'message' => 'Lịch khám đã hết chỗ!']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'user_register_id' => Yii::t('backend', 'Khách hàng'),
            'customer_type' => Yii::t('backend', 'Loại khách hàng'),
            'time_id' => Yii::t('backend', 'Thời gian'),
            'coso_id' => Yii::t('backend', 'Cơ sở'),
            'booking_date' => Yii::t('backend', 'Ngày'),
            'status' => Yii::t('backend', 'Status'),
            'created_by' => Yii::t('backend', 'Created By'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_by' => Yii::t('backend', 'Updated By'),
            'updated_at' => Yii::t('backend', 'Updated At'),
        ];
    }

    public function getUserRegisterHasOne()
    {
        return $this->hasOne(UserRegister::class, ['id' => 'user_register_id']);
    }

    public function getCustomerOnlineHasOne()
    {
        return $this->hasOne(Dep365CustomerOnline::class, ['id' => 'user_register_id']);
    }

    public function getTimeWorkHasOne()
    {
        return $this->hasOne(TimeWork::class, ['id' => 'time_id']);
    }

    public function getCoSoHasOne()
    {
        return $this->hasOne(Dep365CoSo::class, ['id' => 'coso_id']);
    }

    public function getUserCreatedBy($id)
    {
        if ($id == null) {
            return null;
        }
        $user = UserProfile::find()->where(['user_id' => $id])->one();
        return $user;
    }

    public function getUserUpdatedBy($id)
    {
        if ($id == null) {
            return null;
        }
        $user = UserProfile::find()->where(['user_id' => $id])->one();
        return $user;
    }

    public function checkTimeIdExist()
    {
        $time = TimeWork::find()->where(['time' => $this->time_id])->published()->one();
        return $time != null;
    }

    public function checkTimeIdEmpty()
    {
        $booking = self::find()->where(['time_id' => $this->time_id])->count();
        return $booking < self::MAX_BOOKING_IN_TIME;
    }

    public function afterDelete()
    {
        $status = $this->getAttribute('status');
        $cache = Yii::$app->cache;
        $key = 'get-total-customer-booking-menu-left-' . $status . '-redis';
        $cache->delete($key);
        parent::afterDelete(); // TODO: Change the autogenerated stub
    }

    public function afterSave($insert, $changedAttributes)
    {
        $status = $this->getAttribute('status');
        $cache = Yii::$app->cache;
        $key = 'get-total-customer-booking-menu-left-' . $status . '-redis';
        $cache->delete($key);
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    public static function getTotalCustomerBooking($status = null)
    {
        $cache = Yii::$app->cache;
        $key = 'get-total-customer-booking-menu-left-' . $status . '-redis';

        $data = $cache->get($key);

        if ($data == false) {
            if (!in_array($status, [self::STATUS_DISABLED, self::STATUS_PUBLISHED])) {
                $status = null;
            }
            $query = static::find()->where(['customer_type' => CustomerOnlineBooking::CUSTOMER_FROM_WEBSITE]);
            if ($status !== null) {
                $query->andWhere(['status' => $status]);
            }
            $data = $query->count();
            $cache->set($key, $data, 1800);
        }


        return $data;
    }
}
