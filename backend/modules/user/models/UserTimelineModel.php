<?php


namespace backend\modules\user\models;

use backend\models\CustomerModel;
use backend\modules\customer\models\Dep365CustomerOnline;
use common\models\UserProfile;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_timeline".
 *
 * @property int $id
 * @property int $user_id
 * @property int $customer_id
 * @property int $action
 * @property int $created_at
 */
class UserTimelineModel extends ActiveRecord
{
    const ACTION_THEM = 1;
    const ACTION_TAO = 2;
    const ACTION_CAP_NHAT = 3;
    const ACTION_XOA = 4;
    const ACTION_TRANG_THAI = 5;
    const ACTION_DAT_HEN = 6;
    const ACTION_THAM_KHAM = 7;
    const ACTION_DIRECT_SALE = 8;
    const ACTION_DON_HANG = 9;
    const ACTION_LICH_DIEU_TRI = 10;
    const ACTION_THANH_TOAN = 11;
    const LIST = [
        self::ACTION_THEM => 'thêm',
        self::ACTION_TAO => 'tạo',
        self::ACTION_CAP_NHAT => 'cập nhật',
        self::ACTION_XOA => 'xóa',
        self::ACTION_TRANG_THAI => 'trạng thái',
        self::ACTION_DAT_HEN => 'lịch hẹn',
        self::ACTION_THAM_KHAM => 'đến thăm khám',
        self::ACTION_DIRECT_SALE => 'direct-sale',
        self::ACTION_DON_HANG => 'đơn hàng',
        self::ACTION_LICH_DIEU_TRI => 'lịch điều trị',
        self::ACTION_THANH_TOAN => 'thanh toán'
    ];

    public static function tableName()
    {
        return 'user_timeline';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_VALIDATE => ['created_at'],
                ],
                'value' => time(),
            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'user_id',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_VALIDATE => ['user_id'] // If usr_id is required
                ]
            ],
        ];
    }

    public function rules()
    {
        return [
            [['user_id', 'customer_id', 'created_at'], 'required'],
            [['user_id', 'customer_id', 'created_at'], 'integer'],
        ];
    }

    public function getNameCustomerHasOne()
    {
        return $this->hasOne(CustomerModel::class, ['id' => 'customer_id']);
    }

    public function getNameUserHasOne()
    {
        return $this->hasOne(UserProfile::class, ['user_id' => 'user_id']);
    }
}
