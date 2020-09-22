<?php

namespace backend\modules\labo\models;

use backend\modules\labo\models\query\LaboGiaiDoanQuery;
use common\models\UserProfile;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "labo_giai_doan".
 *
 * @property int $id
 * @property int $labo_don_hang_id
 * @property string $note
 * @property int $giai_doan
 * @property int $status
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $updated_at
 */
class LaboGiaiDoan extends \yii\db\ActiveRecord
{
    const STATUS_DISABLED = 0;
    const STATUS_PUBLISHED = 1;

    const GIAI_DOAN_TIEP_NHAN = 1;
    const GIAI_DOAN_DAU_RANG = 2;
    const GIAI_DOAN_FORM_RANG = 3;
    const GIAI_DOAN_NUONG_HOAN_THANH = 4;


    public static function tableName()
    {
        return 'labo_giai_doan';
    }

    public static function find()
    {
        return new LaboGiaiDoanQuery(get_called_class());
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
            [['labo_don_hang_id', 'giai_doan', 'status', 'created_at', 'created_by', 'updated_by', 'updated_at'], 'integer'],
            [['note'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'labo_don_hang_id' => 'Labo Don Hang ID',
            'note' => 'Ghi chú',
            'giai_doan' => 'Công Đoạn',
            'status' => 'Trạng thái',
            'created_at' => 'Thời gian tạo',
            'created_by' => 'Người tạo',
            'updated_by' => 'Người cập nhật',
            'updated_at' => 'Ngày cập nhật',
        ];
    }

    public function getGiaiDoan()
    {
        $list = self::getListGiaiDoan();
        return isset($list[$this->giai_doan]) ? $list[$this->giai_doan] : "";
    }

    public static function getListGiaiDoan()
    {
        return [
            self::GIAI_DOAN_TIEP_NHAN => "Tiếp Nhận",
            self::GIAI_DOAN_DAU_RANG => "Làm Dấu Răng",
            self::GIAI_DOAN_FORM_RANG => "Làm Form Răng",
            self::GIAI_DOAN_NUONG_HOAN_THANH => "Nướng Hoàn Thành",
        ];
    }

    public function getStatus()
    {
        $list = self::getListStatus();
        return ($list[$this->status]) ? $list[$this->status] : "";
    }

    public static function getListStatus()
    {
        return [
            self::STATUS_DISABLED => "Khởi tạo",
            self::STATUS_PUBLISHED => "Hoàn thành",
        ];
    }

    public function getStatusView()
    {
        $array = self::getListStatus();
        if (isset($array[$this->status])) {
            $text = '';
            switch ($this->status) {
                case self::STATUS_DISABLED:
                    $text = "<span class=\"badge badge-warning\">" . $array[$this->status] . "</span>";
                    break;
                case self::STATUS_PUBLISHED:
                    $text = "<span class=\"badge badge-success\">" . $array[$this->status] . "</span>";
                    break;
                default:
                    return "";
            }
            return $text;
        } else {
            return "";
        }
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

    public function getLaboDonHang()
    {
        return $this->hasOne(LaboDonHang::class, ['id' => 'labo_don_hang_id']);
    }

    public function getCustomer()
    {
        $phongkham = $this->getPhongKhamDonHang();
        if ($phongkham) return $phongkham->customerOnlineHasOne;
        return null;
    }

    public function getPhongKhamDonHang()
    {
        $laboDonHang = $this->laboDonHang;
        if ($laboDonHang) return $laboDonHang->phongKhamDonHang;
        return null;
    }

    // xoa giai doan hinh anh, feedback
    public function deleteGiaiDoan()
    {
        $listGiaiDoanImage = LaboGiaiDoanImage::find()->where(['labo_giai_doan_id' => $this->id]);
        if ($listGiaiDoanImage->count() > 0) {
            $alistGiaiDoanImage = $listGiaiDoanImage->all();
            foreach ($alistGiaiDoanImage as $mGiaiDoanImage) {
                if ($mGiaiDoanImage->delete()) {
                    $mGiaiDoanImage->deleteFile($mGiaiDoanImage->image);
                }
            }
        }
        $listFeedback = LaboFeedback::find()->where(['labo_giai_doan_id' => $this->id]);
        if ($listFeedback->count() > 0) {
            $alistFeedback = $listFeedback->all();
            foreach ($alistFeedback as $mFeedback) {
                $mFeedback->delete();
            }
        }
    }

    public function countLaboGiaiDoanImageView()
    {
        $count = LaboGiaiDoanImage::find()->where(['labo_giai_doan_id' => $this->id])->count();
        if ($count > 0) {
            return "<span class=\"badge badge-success\">" . $count . "</span>";
        }
        return "<span class=\"badge badge-dark\">" . $count . "</span>";
    }

    public  static function getByDonHangGiaiDoan($labo_don_hang_id, $giai_doan)
    {
        return LaboGiaiDoan::find()->where(['labo_don_hang_id' => $labo_don_hang_id, 'giai_doan' => $giai_doan])->one();
    }

    public static function exitsDonHang($labo_don_hang_id){
        return LaboGiaiDoan::find()->where(['labo_don_hang_id' => $labo_don_hang_id])->count();
    }

}
