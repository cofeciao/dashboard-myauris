<?php

namespace backend\modules\clinic\models;

use backend\models\phongkham\BacsiModel;
use backend\modules\clinic\models\query\LichDieuTriQuery;
use backend\modules\setting\models\Dep365CoSo;
use backend\modules\user\models\User;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;
use yii\helpers\ArrayHelper;

class PhongKhamLichDieuTri extends \yii\db\ActiveRecord
{
    public $name;
    public $phone;

    public $direct_sale;

    public $order;
    public $thanhtoan;

    public $keyword; //Clinic search name, phone, customer_code, order_code

    const SCENARIO_TIMEEND = 'timeend';

    const STATUS_PUBLISHED = 1;
    const STATUS_DRAFF = 0;


    const THAO_TAC_KHAC = 0;
    const THAO_TAC_MAI = 1;
    const THAO_TAC_LAP = 2;
    const THAO_TAC_LOI = 3;

    public static function listThaoTac()
    {
        return [
            self::THAO_TAC_MAI => "Mài",
            self::THAO_TAC_LAP => "Lắp",
            self::THAO_TAC_LOI => "Lợi",
            self::THAO_TAC_KHAC => "Khác",
        ];
    }

    public function getThaoTac()
    {
        $thao_tac = $this->thao_tac;
        $result = '';
        $aThaoTac = self::listThaoTac();
        // var_dump($thao_tac);
        if ($thao_tac == null) {
            return "";
        }
        // var_dump($thao_tac);
        if (is_array($thao_tac) && count($thao_tac) > 0) {
            foreach ($thao_tac as $key => $value) {
                $result .= $aThaoTac[(int)$value] . "<br>";
                // $result .= $value;
            }
            return $result;
        }
        return "";
    }

    public function getTroThu()
    {
        $user = new \common\models\UserProfile();
        $result = '';
        $tro_thu_lich = $this->tro_thu;
        if (is_array($tro_thu_lich)) {
            foreach ($tro_thu_lich as $tro_thu) {
                $fullname = $user->getFullNameBacSi($tro_thu);
                if ($fullname == false) {
                    $result .= null;
                }
                $result .= $fullname . "<br>";
            }
        }
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'phong_kham_lich_dieu_tri';
    }

    public function behaviors()
    {
        $user = new User();
        $roleName = $user->getRoleName(Yii::$app->user->id);
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
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'last_dieu_tri'
                ],
                'value' => function () use ($roleName) {
                    if ($this->tai_kham != null && $roleName != User::USER_DATHEN) {
                        return 1;
                    }
                    return $this->last_dieu_tri;
                }
            ]
        ];
    }

    public static function find()
    {
        return new LichDieuTriQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'customer_code', 'order_code', 'time_dieu_tri', 'id_list_chuphinh', 'ekip', 'tro_thu', 'room_id', 'thao_tac'], 'required'],
            [['customer_id', 'ekip', 'created_at', 'updated_at', 'created_by', 'updated_by', 'last_dieu_tri', 'id_list_chuphinh'], 'integer'],
            [['huong_dieu_tri', 'note'], 'string'],
            [['customer_code', 'order_code'], 'string', 'max' => 25],
            [['time_start', 'time_end', 'tai_kham'], 'safe'],
            [['time_end'], 'validateTimeEnd'],
            [['danh_gia', 'thai_do', 'chuyen_mon', 'tham_my', 'id_list_chuphinh', 'room_id'], 'integer'],
            [['tro_thu', 'direct_sale', 'thao_tac', 'time_dieu_tri'], 'safe'],
        ];
    }

    public function scenarios()
    {
        $scenarios = [
            self::SCENARIO_TIMEEND => ['time_end'],
        ];

        return array_merge(parent::scenarios(), $scenarios);
    }

    public function validateTimeEnd($attribute, $params)
    {
        if ($this->time_end != null) {
            if ($this->time_end < $this->time_start) {
                return $this->addError('time_end', 'Thời gian kết thúc không thể trước thời gian bắt đầu.');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'customer_id' => Yii::t('backend', 'Id khách hàng'),
            'customer_code' => Yii::t('backend', 'Mã khách hàng'),
            'order_code' => Yii::t('backend', 'Mã đơn hàng'),
            'ekip' => Yii::t('backend', 'Ekip bác sĩ'),
            'tro_thu' => Yii::t('backend', 'Trợ thủ'),
            'id_list_chuphinh' => Yii::t('backend', 'Loại điều trị'),
            'time_dieu_tri' => Yii::t('backend', 'Thời gian điều trị'),
            'huong_dieu_tri' => Yii::t('backend', 'Hướng điều trị'),
            'note' => Yii::t('backend', 'Ghi chú'),
            'room_id' => Yii::t('backend', 'Phòng'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
            'time_start' => 'Bắt đầu',
            'time_end' => 'Kết thúc',
            'order' => 'Đơn hàng',
            'thanhtoan' => 'Thanh toán',
            'danh_gia' => 'Đánh giá',
            'thai_do' => 'Thái độ',
            'chuyen_mon' => 'Chuyên môn',
            'tham_my' => 'Thẩm mỹ',
            'last_dieu_tri' => 'Điều trị cuối, hoàn thành điều trị',
            'phone' => 'Điện thoại',
            'name' => 'Họ và tên',
            'thao_tac' => 'Thao tác',
        ];
    }


    public function getOrderHasOne()
    {
        return $this->hasOne(PhongKhamDonHang::class, ['order_code' => 'order_code']);
    }

    public function getClinicHasOne()
    {
        return $this->hasOne(Clinic::class, ['id' => 'customer_id']);
    }

    public function getEkipInfoHasOne()
    {
        return $this->hasOne(UserProfile::class, ['user_id' => 'ekip']);
    }

    public function getRoomHasOne()
    {
        return $this->hasOne(UserProfile::class, ['user_id' => 'room_id']);
    }

    public function getCoSoHasOne()
    {
        return $this->hasOne(Dep365CoSo::class, ['id' => 'co_so']);
    }

    public function getUserCreatedByHasOne()
    {
        return $this->hasOne(UserProfile::class, ['user_id' => 'created_by']);
    }

    public function getListChupHinhHasOne()
    {
        return $this->hasOne(ListChupHinh::class, ['id' => 'id_list_chuphinh']);
    }

    public function getUserCreatedBy($id)
    {
        if ($id == null) {
            return false;
        }
        $user = UserProfile::getUserCreatedOrUpdateBy($id);
        return $user;
    }

    public function getUserUpdatedBy($id)
    {
        if ($id == null) {
            return false;
        }
        $user = UserProfile::getUserCreatedOrUpdateBy($id);
        return $user;
    }

    public function getThongTinCoBan()
    {
        $result = date("d-m-Y H:i:s", $this->time_dieu_tri);
        $userCreatedBy = $this->getUserCreatedBy($this->created_by);
        if ($userCreatedBy == false) {
            return "";
        }
        if ($this->clinicHasOne == null) {
            $tit = null;
        } else {
            $tit = $this->clinicHasOne->full_name == null ? $this->clinicHasOne->forename : $this->clinicHasOne->full_name;
        }

        $result .= '<br> <span style="font-weight: 600;">' . $tit . "</span>";
        $result .= '<br> <i style="font-size:13px">Tạo bởi: ' . $userCreatedBy->fullname . "</i>";
        return $result;
    }

    public function getThongTinTroThu()
    {
        $result = [];
        $list = $this->tro_thu;
        $user = new UserProfile();
        foreach ($list as $item) {
            $fullname = $user->getFullNameBacSi($item);
            if ($fullname !== false) {
                $result[] = $fullname;
            }
        }
        return $result;
    }
}
