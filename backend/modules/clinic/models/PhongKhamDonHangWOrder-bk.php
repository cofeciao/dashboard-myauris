<?php

namespace backend\modules\clinic\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;

/**
 * This is the model class for table "phong_kham_don_hang_w_order".
 *
 * @property int $id
 * @property int $phong_kham_don_hang_id
 * @property int $dich_vu
 * @property int $san_pham
 * @property int $mau_sac
 * @property int $so_luong
 * @property string $thanh_tien
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class PhongKhamDonHangWOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'phong_kham_don_hang_w_order';
    }

    public function getMoneyCustomer($idDonHang)
    {
        return self::find()->where(['phong_kham_don_hang_id' => $idDonHang])->sum('replace(thanh_tien, \'.\', \'\')');
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
                //'preserveNonEmptyValues' => true,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => time(),
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'ngay_tao',
                ],
                'value' => function () {
                    return strtotime(date('d-m-Y'));
                },
            ],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phong_kham_don_hang_id', 'dich_vu', 'san_pham', 'mau_sac', 'so_luong'], 'required'],
            [['customer_id', 'phong_kham_don_hang_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['thanh_tien'], 'string', 'max' => 255],
            [['dich_vu', 'san_pham', 'mau_sac', 'so_luong'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'phong_kham_don_hang_id' => Yii::t('backend', 'Phòng Khám Đơn Hàng ID'),
            'dich_vu' => Yii::t('backend', 'Dịch Vụ'),
            'san_pham' => Yii::t('backend', 'Sản Phẩm'),
            'mau_sac' => Yii::t('backend', 'Màu Sắc'),
            'so_luong' => Yii::t('backend', 'Số Lượng'),
            'thanh_tien' => Yii::t('backend', 'Thành Tiền'),
            'status' => Yii::t('backend', 'Status'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
        ];
    }

    public function getDichVuHasOne()
    {
        return $this->hasOne(PhongKhamDichVu::class, ['id' => 'dich_vu']);
    }

    public function getSanPhamHasOne()
    {
        return $this->hasOne(PhongKhamSanPham::class, ['id' => 'san_pham']);
    }

    public function getMauSacHasOne()
    {
        return $this->hasOne(PhongKhamMauSac::class, ['id' => 'mau_sac']);
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
