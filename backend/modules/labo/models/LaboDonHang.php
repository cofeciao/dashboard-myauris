<?php

namespace backend\modules\labo\models;

use backend\models\CustomerModel;
use backend\modules\clinic\models\Clinic;
use backend\modules\clinic\models\PhongKhamDonHang;
use backend\modules\labo\models\query\LaboDonHangQuery;
use common\models\User;
use common\models\UserProfile;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "labo_don_hang".
 *
 * @property int $id
 * @property int $bac_si_id
 * @property int $phong_kham_don_hang_id
 * @property int $ngay_nhan
 * @property int $ngay_giao
 * @property array $loai_phuc_hinh
 * @property int $loai_su
 * @property string $yeu_cau
 * @property int $trang_thai
 * @property int $status
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $updated_at
 * @property array $vi_tri_rang
 */
class LaboDonHang extends \yii\db\ActiveRecord
{
    const STATUS_DISABLED = 0;
    const STATUS_PUBLISHED = 1;

    const LOAI_SU_THUONG = 0;
    const LOAI_SU_CAO_CAP = 1;
    const LOAI_KHAC = 2;

    const TRANG_THAI_MOI = 1;
    const TRANG_THAI_DA_TIEP_NHAN = 2;
    const TRANG_THAI_DA_HOAN_THANH = 3;

    public $imageFile;

    public static function tableName()
    {
        return 'labo_don_hang';
    }

    public static function getListRangTren()
    {
        return Yii::$app->controller->module->params['listRangTren'];
    }

    public static function getListRangDuoi()
    {
        return Yii::$app->controller->module->params['listRangDuoi'];
    }

    public static function coutLaboDonHangTrangThaiNew()
    {
        $user = new \backend\modules\user\models\User();
        $roleName = $user->getRoleName(Yii::$app->user->id);
        if (in_array($roleName, [
            User::USER_KY_THUAT_LABO
        ])) {
            return LaboDonHang::find()->where(['trang_thai' => LaboDonHang::TRANG_THAI_MOI, 'user_labo' => Yii::$app->user->id])->count();
        }
        return LaboDonHang::find()->where(['trang_thai' => LaboDonHang::TRANG_THAI_MOI])->count();
    }

    public static function find()
    {
        return new LaboDonHangQuery(get_called_class());
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

    // public function getLoaiSu()
    // {
    //     $array = self::getListLoaiSu();
    //     return isset($array[$this->loai_su]) ? $array[$this->loai_su] : "";
    // }

    // public static function getListLoaiSu()
    // {
    //     return [
    //         self::LOAI_SU_THUONG => "Sứ Emax Press",
    //         self::LOAI_SU_CAO_CAP => "Sứ Zirconia",
    //         self::LOAI_KHAC => "Khác",
    //     ];
    // }

    public function getLoaiPhucHinh()
    {
        $array = self::getListLoaiPhucHinh();
        return isset($array[$this->loai_phuc_hinh]) ? $array[$this->loai_phuc_hinh] : "";
    }

    public static function getListLoaiPhucHinh()
    {
        return [
            self::LOAI_SU_THUONG => "Sứ thường",
            self::LOAI_SU_CAO_CAP => "Sứ cao cấp",
            self::LOAI_KHAC => "Loại khác",
        ];
    }

    public function getTrangThai()
    {
        $array = self::getListTrangThai();
        return isset($array[$this->trang_thai]) ? $array[$this->trang_thai] : "";
    }

    public static function getListTrangThai()
    {
        return [
            self::TRANG_THAI_MOI => "Mới tạo",
            self::TRANG_THAI_DA_TIEP_NHAN => "Đã tiếp nhận",
            self::TRANG_THAI_DA_HOAN_THANH => "Hoàn thành",
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['so_luong'], 'required'],
            [['bac_si_id', 'user_labo', 'phong_kham_don_hang_id', 'loai_su', 'trang_thai', 'status', 'created_at', 'created_by', 'updated_by', 'updated_at', 'so_luong', 'loai_phuc_hinh'], 'integer'],
            [['loai_phuc_hinh', 'ngay_nhan', 'ngay_giao', 'vi_tri_rang', 'user_labo'], 'safe'],
            [['yeu_cau', 'image'], 'string'],
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bac_si_id' => 'Bác sĩ',
            'phong_kham_don_hang_id' => 'Đơn hàng',
            'ngay_nhan' => 'Ngày nhận',
            'ngay_giao' => 'Ngày giao',
            'loai_phuc_hinh' => 'Loai Phuc Hinh',
            'loai_su' => 'Loại sứ',
            'yeu_cau' => 'Yêu cầu',
            'trang_thai' => 'Trạng thái',
            'status' => 'Status',
            'created_at' => 'Ngày tạo',
            'created_by' => 'Người tạo',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
            'user_labo' => 'Kỹ thuật Labo',
            'so_luong' => 'Số lượng',
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

    public function getPhongKhamDonHang()
    {
        return $this->hasOne(PhongKhamDonHang::class, ['id' => 'phong_kham_don_hang_id']);
    }

    public function getPhieuLabo()
    {
        $user = new \common\models\UserProfile();
        $fullname = $user->getFullNameBacSi($this->bac_si_id);
        $donhang = PhongKhamDonHang::findOne($this->phong_kham_don_hang_id);
        $order_code = ($donhang) ? "<b style='font-weight: 900;'>" . $donhang->order_code . "</b>" : "";
        $ngay_nhan = "N.nhận : " . date('d-m-Y', $this->ngay_nhan);
        $ngay_giao = "N.giao : " . date('d-m-Y', $this->ngay_giao);
        $trang_thai = $this->getTrangThaiView();
        $text = implode("<br>", [$order_code . " - " . $fullname, $ngay_nhan, $ngay_giao, $trang_thai]);
        return $text;
    }

    public function getTrangThaiView()
    {
        $array = self::getListTrangThai();
        if (isset($array[$this->trang_thai])) {
            $text = '';
            switch ($this->trang_thai) {
                case self::TRANG_THAI_MOI:
                    $text = "<span class=\"badge badge-danger\">" . $array[$this->trang_thai] . "</span>";
                    break;
                case self::TRANG_THAI_DA_TIEP_NHAN:
                    $text = "<span class=\"badge badge-warning\">" . $array[$this->trang_thai] . "</span>";
                    break;
                case self::TRANG_THAI_DA_HOAN_THANH:
                    $text = "<span class=\"badge badge-success\">" . $array[$this->trang_thai] . "</span>";
                    break;
                default:
                    return "";
            }
            return $text;
        } else {
            return "";
        }
    }

    public  static function getListUserLabo()
    {
        $data = User::getNhanVienKyThuatLabo();
        $result = [];
        if (count($data) > 0) {
            foreach ($data as $key => $item) {
                $result[$item->id] = $item->userProfile->fullname;
            }
        }
        return $result;
    }

    public function getUserLabo()
    {
        $list = self::getListUserLabo();
        if (!empty($this->user_labo)) {
            return isset($list[$this->user_labo]) ? $list[$this->user_labo] : "";
        }
        return "";
    }

    public function upload($fileName)
    {
        if ($this->validate()) {
            $this->imageFile->saveAs(Yii::getAlias('@backend/web') . '/uploads/tmp/' . $fileName, false);
            return true;
        } else {
            return false;
        }
    }
}
