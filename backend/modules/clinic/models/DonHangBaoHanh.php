<?php

namespace backend\modules\clinic\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "don_hang_bao_hanh".
 *
 * @property int $id
 * @property int $phong_kham_don_hang_id
 * @property int $so_luong_rang
 * @property string $ly_do
 * @property int $ngay_thuc_hien
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $updated_at
 */
class DonHangBaoHanh extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'don_hang_bao_hanh';
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
            ]
        ];
    }



    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phong_kham_don_hang_id', 'so_luong_rang', 'ngay_thuc_hien'], 'required'],
            [['phong_kham_don_hang_id', 'so_luong_rang', 'created_at', 'created_by', 'updated_by', 'updated_at'], 'integer'],
            [['ly_do'],'string'],
            [['phong_kham_don_hang_id', 'so_luong_rang', 'ngay_thuc_hien','ly_do'],'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phong_kham_don_hang_id' => 'Mã đơn hàng',
            'so_luong_rang' => 'Số lượng răng',
            'ly_do' => 'Lý do',
            'ngay_thuc_hien' => 'Ngày thực hiện',
            'created_at' => 'Ngày tạo',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }

    public function getUserCreatedBy($id)
    {
        if ($id == null)
            return null;
        $user = UserProfile::find()->where(['user_id' => $id])->one();
        return $user;
    }

    public function getUserUpdatedBy($id)
    {
        if ($id == null)
            return null;
        $user = UserProfile::find()->where(['user_id' => $id])->one();
        return $user;
    }
    
    public function getNgayThucHien(){
        return date('d-m-Y H:i', $this->ngay_thuc_hien);
    }

    public function getMaHoaDon(){
        $mPhongKhamDonHang = PhongKhamDonHang::findOne($this->phong_kham_don_hang_id);
        return $mPhongKhamDonHang->order_code."- Thành tiền : ".number_format($mPhongKhamDonHang->thanh_tien,0,'.','.');
    }

    public function getID(){
        return $this->id;
    }

    public function getKhachHang(){
        $mPhongKhamDonHang = PhongKhamDonHang::findOne($this->phong_kham_don_hang_id);
        if($mPhongKhamDonHang->customerOnlineHasOne){
            $name = $mPhongKhamDonHang->customerOnlineHasOne->full_name;
            $id = $mPhongKhamDonHang->customerOnlineHasOne->id;
            return Html::a($name, [Url::to(['/quan-ly/customer-view']), 'id' => $id], ['target' => "_blank"]);
        }
        return "";
    }

    public function getEditKhachHang(){
        $mPhongKhamDonHang = PhongKhamDonHang::findOne($this->phong_kham_don_hang_id);
        if($mPhongKhamDonHang->customerOnlineHasOne){
            $id = $mPhongKhamDonHang->customerOnlineHasOne->id;
            return Html::a('<i class="ft-edit blue"></i>', [Url::to(['/clinic/clinic/bao-hanh']), 'customer_id' => $id], ['class' => 'btn btn-default','target' => "_blank"]);
        }
        return "";
    }

}
