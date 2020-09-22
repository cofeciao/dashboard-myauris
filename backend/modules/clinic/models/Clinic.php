<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 14-Jan-19
 * Time: 11:13 AM
 */

namespace backend\modules\clinic\models;

use backend\models\CustomerModel;
use backend\modules\clinic\models\query\ClinicQuery;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\customer\models\Dep365CustomerOnlineCome;
use backend\modules\customer\models\Dep365CustomerOnlineDathenStatus;
use common\helpers\MyHelper;
use common\models\User;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Clinic extends CustomerModel
{
    const SCENARIO_UPDATE = 'clinic_update';
    const SCENARIO_QUANLY_COSO = 'quanly_coso';
    const SCENARIO_ADMIN = 'clinic_admin';
    public $updateCustomer;
    public $remind_call_time;
    public $change_permission_for_online;
    public $new_permission_user;
    public $dathen_time;
    public $check_dich_vu;

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::class,
                'updatedByAttribute' => 'updated_by',
            ],
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => time(),
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'name',
                ],
                'value' => function () {
                    return $this->full_name;
                },
            ],
            'slug' => [
                'class' => SluggableBehavior::class,
//                'attribute' => 'full_name',
//                'slugAttribute' => 'slug',
                'immutable' => false,
                'ensureUnique' => true, //
                'value' => function () {
                    return MyHelper::createAlias($this->full_name);
                }
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'is_customer_who',
                ],
                'value' => function () {
                    if ($this->is_customer_who != null) return $this->is_customer_who;
                    return self::IS_CUSTOMER_LETAN;
                },
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'co_so',
                ],
                'value' => function () {
                    $user = new \backend\modules\user\models\User();
                    $roleName = $user->getRoleName(Yii::$app->user->id);
                    if ($roleName == User::USER_QUANLY_PHONGKHAM && $this->co_so != null) return $this->co_so;
                    return Yii::$app->user->identity->permission_coso;
                },
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'status',
                ],
                'value' => function () {
                    return Dep365CustomerOnline::STATUS_DH;
                },
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'agency_id',
                ],
                'value' => function () {
                    return Dep365CustomerOnline::IS_AGENCY_365;
                },
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'nguon_online',
                ],
                'value' => function () {
                    return Dep365CustomerOnline::STATUS_NGUON_KHACH_VANG_LAI;
                },
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'permission_user',
                ],
                'value' => function () {
                    if ($this->permission_user != null) return $this->permission_user;
                    return Yii::$app->user->id;
                },
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'customer_code',
                ],
                'value' => function () {
                    if ($this->customer_code == null) {
                        if (strlen(Yii::$app->user->identity->permission_coso) == 1) {
                            $coso = '0' . Yii::$app->user->identity->permission_coso;
                        } else {
                            $coso = Yii::$app->user->identity->permission_coso;
                        }
                        return 'AUR' . $coso . '-' . $this->primaryKey;
                    } else {
                        return $this->customer_code;
                    }
                },
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'time_lichhen',
                ],
                'value' => time(),
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'ngay_tao',
                ],
                'value' => function () {
                    $date = date('d-m-Y', $this->created_at);
                    return strtotime($date);
                },
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'date_lichhen',
                ],
                'value' => function () {
                    if ($this->time_lichhen != null) {
                        return strtotime(date('d-m-Y', $this->time_lichhen));
                    }
                },
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'ngay_dong_y_lam',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'ngay_dong_y_lam'
                ],
                'value' => function () {
                    $user = new \backend\modules\user\models\User();
                    $roleUser = $user->getRoleName(Yii::$app->user->id);
                    $old = $this->getOldAttribute('customer_come_time_to');
                    $get_old = Dep365CustomerOnlineCome::find()->where(['id' => $old])->published()->one();
                    $new = $this->getAttribute('customer_come_time_to');
                    $get_new = Dep365CustomerOnlineCome::find()->where(['id' => $new])->published()->one();
                    if (in_array($roleUser, [User::USER_DEVELOP, User::USER_ADMINISTRATOR])) {
                        if ($this->ngay_dong_y_lam != null) {
                            return strtotime($this->ngay_dong_y_lam);
                        }
                    }
                    if ($get_old == null) {
                        if ($get_new != null && $get_new->accept != Dep365CustomerOnlineCome::STATUS_ACCEPT) {
                            if ($this->ngay_dong_y_lam != null) {
                                return strtotime($this->ngay_dong_y_lam);
                            }
                            return strtotime(date('d-m-Y'));
                        }
                    }
                    if ($this->ngay_dong_y_lam != null) {
                        return strtotime($this->ngay_dong_y_lam);
                    }
                }
            ]
        ];
    }

    public static function find()
    {
        return new ClinicQuery(get_called_class());
    }

    public function rules()
    {
        $listAccept = ArrayHelper::map(Dep365CustomerOnlineCome::find()->published()->andWhere(['accept' => Dep365CustomerOnlineCome::STATUS_ACCEPT])->all(), 'id', 'id');
        return [
            [['check_dich_vu'], 'safe'],
//            [['phone', 'sex', 'province', 'directsale'], 'required'],
            [['phone', 'sex', 'province'], 'required'],
            [['id_dich_vu'], 'required', 'when' => function () use ($listAccept) {
                return in_array($this->customer_come_time_to, $listAccept) && $this->check_dich_vu == '1';
            }, 'whenClient' => "function(){
                var customer_come_time_to = parseInt($('#customer-come-time-to').val()) || 0;
                return '" . $this->check_dich_vu . "' == '1' && " . json_encode(array_keys($listAccept)) . ".includes(customer_come_time_to);
            }"],
            [['directsale'], 'required', 'when' => function () {
                return $this->dat_hen == Dep365CustomerOnline::DAT_HEN_DEN;
            }, 'whenClient' => "function(){
                return $('#clinic-dat_hen').val() == '" . Dep365CustomerOnline::DAT_HEN_DEN . "'
            }"],
//            [['full_name', 'forename', 'birthday', 'customer_come'], 'required', 'when' => function ($model) {
            [['full_name', 'forename', 'customer_come'], 'required', 'when' => function ($model) {
                return $model->dat_hen != '2';
            }, 'whenClient' => "function (attribute, value) {
                return $('#clinic-dat_hen').val() != '2';
            }"],
            [['note_direct', 'customer_mongmuon', 'customer_thamkham'], 'string'],
            [['full_name', 'forename', 'address', 'slug', 'name'], 'string', 'max' => 255],
            [['avatar'], 'string', 'max' => 255],
            [['is_customer_who', 'sex', 'co_so', 'created_at', 'updated_at', 'directsale'], 'integer'],
            [['id_dich_vu'], 'integer', 'when' => function () use ($listAccept) {
                return in_array($this->customer_come_time_to, $listAccept) && $this->check_dich_vu == '1';
            }, 'whenClient' => "function(){
                return '" . $this->check_dich_vu . "' == '1' && " . json_encode(array_keys($listAccept)) . ".includes($('#clinic-customer_come_time_to').val());
            }"],
//            [['province'], 'integer', 'message' => 'Tỉnh thành không hợp lệ'],
//            [['district'], 'integer', 'message' => 'Quận huyện không hợp lệ'],
//            [['district', 'address'], 'required', 'when' => function ($model) {
//                return $model->province != '97';
//            }, 'whenClient' => "function (attribute, value) {
//                return $('#clinic-province').val() != '97';
//            }"],
            [['customer_gen'], 'integer', 'message' => 'Tính cách không hợp lệ'],
            ['phone', 'telnumvn', 'exceptTelco' => ['landLine'], 'when' => function ($model) {
                return $model->province != '97';
            }, 'whenClient' => "function (attribute, value) {
                return $('#clinic-province').val() != '97';
            }"],
            ['phone', 'unique',
                'targetClass' => '\backend\modules\customer\models\Dep365CustomerOnline',
                'message' => 'Đã tồn tại số điện thoại khách hàng này',
                'filter' => function ($query) {
                    $query->andWhere(['not', ['id' => $this->getId()]]);
                },
                'on' => parent::PHONE_CREATE
            ],
            [['dat_hen'], 'required', 'on' => [self::SCENARIO_UPDATE, self::SCENARIO_ADMIN], 'when' => function ($model) {
                return $model->status = CustomerModel::STATUS_DH;
            }],
            [['ngay_dong_y_lam'], 'safe'],
            [['dat_hen'], 'integer'],
//            [['customer_come_time_to'], 'required', 'when' => function ($model) {
//                return $model->dat_hen == '1';
//            },],
            [['birthday'], 'string', 'max' => 255],
            [['birthday'], 'checkBirthday'],
//            [['customer_code'], 'string', 'max' => 25],
            [['district', 'province', 'address', 'customer_come'], 'safe'],
            [['customer_come_time_to'], 'checkCustomerComeTimeTo'],
            [['ngay_tao', 'date_lichhen', 'customer_come_date'], 'integer'],
            [['customer_come_time_to'], 'integer', 'whenClient' => "function(){
                var customer_come_time_to = parseInt($('#customer-come-time-to').val()) || 0,
                    accept = " . json_encode(array_keys($listAccept)) . ".includes(customer_come_time_to);
                if('" . $this->primaryKey . "' == ''){
                    if(accept){
                        $('.dich-vu-content').slideDown();
                    } else {
                        $('.dich-vu-content').hide();
                    }
                }
            }"],
            [['ly_do_khong_lam'], 'required', 'when' => function ($model) use ($listAccept) {
                return $model->dat_hen == Dep365CustomerOnline::DAT_HEN_DEN && $model->customer_come_time_to != null && !in_array($model->customer_come_time_to, $listAccept);
            }, 'whenClient' => "function(){
                var dat_hen = $('#clinic-dat_hen').val(),
                    customer_come_time_to = $('#clinic-customer_come_time_to').val();
                return dat_hen == '" . Dep365CustomerOnline::DAT_HEN_DEN . "' && ![null, undefined, ''].includes(customer_come_time_to) && !Object.keys(" . json_encode($listAccept) . ").includes(customer_come_time_to);
            }"],
            [['ly_do_khong_lam'], 'integer', 'when' => function ($model) use ($listAccept) {
                return $model->dat_hen == Dep365CustomerOnline::DAT_HEN_DEN && $model->customer_come_time_to != null && !in_array($model->customer_come_time_to, $listAccept);
            }],
            [['remind_call_time'], 'safe', 'when' => function ($model) use ($listAccept) {
                return !in_array($model->customer_come_time_to, $listAccept);
            }],
            [['nguoi_gioi_thieu'], 'integer'],
            [['change_permission_for_online'], 'boolean'],
            [['new_permission_user'], 'integer', 'on' => self::SCENARIO_ADMIN, 'when' => function ($model) {
                return $model->change_permission_for_online == true;
            }, 'whenClient' => "function(){
                return true;
            }"],
            [['permission_old'], 'integer', 'on' => self::SCENARIO_ADMIN, 'when' => function ($model) {
                return $model->change_permission_for_online == true;
            }],
            [['dathen_time'], 'safe', 'on' => self::SCENARIO_ADMIN, 'when' => function ($model) {
                return $model->change_permission_for_online == true;
            }],
        ];
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'remind_call_time' => Yii::t('backend', 'Khi nào nên gọi lại'),
            'new_permission_user' => Yii::t('backend', 'Nhân viên online'),
            'change_permission_for_online' => Yii::t('backend', 'Chuyển quyền cho online'),
            'dathen_time' => Yii::t('backend', 'Thời gian đặt hẹn')
        ]);
    }

    public function checkCustomerComeTimeTo()
    {
        $old = $this->getOldAttribute('customer_come_time_to');
        $get_old = Dep365CustomerOnlineCome::find()->where(['id' => $old])->published()->one();
        if ($get_old != null) {
            $new = $this->getAttribute('customer_come_time_to');
            $get_new = Dep365CustomerOnlineCome::find()->where(['id' => $new])->published()->one();
            $checkOrder = PhongKhamDonHang::find()->where(['customer_id' => $this->primaryKey])->count();
            if ($checkOrder > 0 && $get_old->accept == Dep365CustomerOnlineCome::STATUS_ACCEPT && $get_new != null && $get_new->accept != Dep365CustomerOnlineCome::STATUS_ACCEPT) {
                $this->addError('customer_come_time_to', Yii::t('backend', 'Không được đổi trạng thái từ đồng ý sang không đồng ý khi đã tạo đơn hàng cho khách'));
            }
        }
    }

    public function checkBirthday()
    {
        if ($this->birthday != null) {
            if ($this->birthday != date('d-m-Y', strtotime($this->birthday))) {
                $this->addError('birthday', Yii::t('backend', 'Ngày sinh không đúng định dạng dd-mm-yyyy'));
            }
        }
    }

    public function getDanhGiaHasOne()
    {
        return $this->hasMany(CustomerDanhGia::class, ['customer_id' => 'id']);
    }

    public static function getEkipbacsi($current_ekip = null)
    {
        $cache = Yii::$app->cache;
        $key = 'redis-get-ekip-bac-si';
        $result = $cache->get($key);
        if ($result === false) {
            $data = User::getNhanVienBacSi($current_ekip);
            if ($data == null) {
                $result = [];
            } else {
                foreach ($data as $key => $item) {
                    $result[$item->id] = $item->userProfile->fullname;
                }
            }
            $cache->set($key, $result, 86400 * 365);
        }
        return $result;
    }

    public static function getTrothu($current_trothu = null)
    {
        $data = User::getNhanVienTroThu($current_trothu);
        if ($data == null) {
            $result = [];
        } else {
            foreach ($data as $key => $item) {
                $result[$item->id] = $item->userProfile->fullname;
            }
        }
        return $result;
    }

    public static function getPhongKham($current_room = null)
    {
        $data = User::getPhongKham($current_room);
        if ($data == null) {
            $result = [];
        } else {
            foreach ($data as $key => $item) {
                $result[$item->id] = $item->userProfile->fullname;
            }
        }
        return $result;
    }

    public static function getEdittableDatHen($dathen)
    {
        $result = [];
        $result['type'] = 'select';
        $result['dataChoose'] = (string)$dathen;
        $DathenStatus = Dep365CustomerOnlineDathenStatus::getDatHenStatus();
        $arr = [];
        foreach ($DathenStatus as $key => $item) {
            $arr[$item->id] = $item->name;
        }
        $result['dataSelect'] = $arr;


        return json_encode($result);
    }

    public function beforeSave($insert)
    {
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}
