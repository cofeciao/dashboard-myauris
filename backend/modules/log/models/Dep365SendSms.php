<?php

namespace backend\modules\log\models;

use backend\models\CustomerModel;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;
use common\helpers\MyHelper;

/**
 * This is the model class for table "dep365_send_sms".
 *
 * @property int $id
 * @property int $customer_id
 * @property string $sms_uuid
 * @property string $sms_text
 * @property string $sms_to
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
        ];
    }




    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'sms_uuid', 'sms_text', 'sms_to', 'sms_lanthu'], 'required'],
            [['customer_id', 'sms_time_send', 'sms_lanthu', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['sms_uuid', 'sms_text', 'sms_to'], 'string'],
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
            'sms_uuid' => Yii::t('backend', 'Sms Uuid'),
            'sms_text' => Yii::t('backend', 'Tin nhắn'),
            'sms_to' => Yii::t('backend', 'SDT nhận'),
            'sms_time_send' => Yii::t('backend', 'Thời gian gửi'),
            'sms_lanthu' => Yii::t('backend', 'Lần gửi tin'),
            'status' => Yii::t('backend', 'Status'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
        ];
    }

    public function getCustomerHasOne()
    {
        return $this->hasOne(CustomerModel::class, ['id' => 'customer_id']);
    }

    public static function smsErrorStatus($status)
    {
        switch ($status) {
            case 0:
                $result = 'Thành công';
                break;
            case 2:
                $result = 'lỗi hệ thống';
                break;
            case 3:
                $result = 'Sai user hoặc mật khẩu';
                break;
            case 4:
                $result = 'Ip không được phép';
                break;
            case 5:
                $result = 'Chưa khai báo brandname/dịch vụ';
                break;
            case 6:
                $result = 'Lặp nội dung';
                break;
            case 7:
                $result = 'Thuê bao từ chối nhận tin';
                break;
            case 8:
                $result = 'Không được phép gửi tin';
                break;
            case 9:
                $result = 'Chưa khai báo template';
                break;
            case 10:
                $result = 'Định dạng thuê bao không đúng';
                break;
            case 11:
                $result = 'Có tham số không hợp lệ';
                break;
            case 12:
                $result = 'Tài khoản không đúng';
                break;
            case 13:
                $result = 'Gửi tin: lỗi kết nối';
                break;
            case 14:
                $result = 'Gửi tin: lỗi kết nối';
                break;
            case 15:
                $result = 'Tài khoản hết hạn';
                break;
            case 16:
                $result = 'Hết hạn dịch vụ';
                break;
            case 17:
                $result = 'Hết hạn mức gửi test';
                break;
            case 18:
                $result = 'Hủy gửi tin (CSKH)';
                break;
            case 19:
                $result = 'Hủy gửi tin (KD)';
                break;
            case 20:
                $result = 'Gateway chưa hỗ trợ Unicode';
                break;
            case 21:
                $result = 'Chưa set giá trả trước';
                break;
            case 22:
                $result = 'Tài khoản chưa kích hoạt';
                break;
            case 25:
                $result = 'Chưa khai báo partner cho user';
                break;
            case 26:
                $result = 'Chưa khai báo GateOwner cho user';
                break;
            case 27:
                $result = 'Gửi tin: gate trả mã lỗi';
                break;
            case 31:
                $result = 'Bạn không thể gửi tin tới số điện thoại 11 số';
                break;
            default:
                $result = 'Hãy liên hệ lập trình viên';
                break;
        }
        return $result;
    }

    public function getUserCreatedBy($id)
    {
        if ($id == null) {
            return false;
        }
        $user = UserProfile::find()->where(['user_id' => $id])->one();
        return $user;
    }

    public function getUserUpdatedBy($id)
    {
        if ($id == null) {
            return false;
        }
        $user = UserProfile::find()->where(['user_id' => $id])->one();
        return $user;
    }
}
