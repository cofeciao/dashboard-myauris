<?php

namespace backend\modules\customer\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "dep365_customer_online".
 *
 * @property int $id
 * @property int $customer_online_id
 * @property string $name
 * @property string $slug
 * @property string $phone
 * @property string $sex
 * @property int $status
 * @property int $nguon_online
 * @property int $province
 * @property int $district
 * @property int $face_fanpage
 * @property int $face_post_id
 * @property string $note
 * @property string $tt_kh
 * @property string $ngaythang
 * @property string $time_lichhen
 * @property int $co_so
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $time_edit
 */
class Dep365CustomerOnlineBak extends ActiveRecord
{
    public static function tableName()
    {
        return 'dep365_customer_online_bak';
    }

    public function behaviors()
    {
        return [
        ];
    }

    public function rules()
    {
        return [
            [['note_tinh_trang_kh','note_mong_muon_kh','note_direct_sale_ho_tro'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'customer_online_id' => 'ID Khách hàng Online',
            'name' => Yii::t('backend', 'Name Customer'),
            'slug' => Yii::t('backend', 'Slug'),
            'phone' => Yii::t('backend', 'Phone'),
            'sex' => Yii::t('backend', 'Sex'),
            'birthday' => Yii::t('backend', 'Ngày sinh'),
            'status' => Yii::t('backend', 'Status Customer'),
            'nguon_online' => Yii::t('backend', 'Nguồn trực tuyến'),
            'province' => Yii::t('backend', 'Province'),
            'district' => Yii::t('backend', 'District'),
            'face_fanpage' => Yii::t('backend', 'Page Facebook'),
            'face_post_id' => Yii::t('backend', 'ID Post Facebook'),
            'note' => Yii::t('backend', 'Note'),
            'tt_kh' => Yii::t('backend', 'Tình trạng răng khách hàng'),
            'ngaythang' => Yii::t('backend', 'Ngày đăng ký'),
            'time_lichhen' => Yii::t('backend', 'Ngày giờ lịch hẹn'),
            'co_so' => Yii::t('backend', 'Cơ sở'),
            'permission_user' => Yii::t('backend', 'Nhân Viên'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
            'time_edit' => 'Thời gian cập nhật',
            'dat_hen' => 'Đặt hẹn',
            'reason_reject' => 'Lý do từ chối làm dịch vụ',
            "note_tinh_trang_kh" => "Tình trạng khách hàng", //13-1-2019
            "note_mong_muon_kh" => " Mong muốn khách hàng",
            "note_direct_sale_ho_tro" => "Nội dung Direct sale hổ trợ",
        ];
    }
}
