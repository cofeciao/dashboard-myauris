<?php

namespace backend\modules\customer\models;

use backend\models\CustomerModel;
use backend\modules\clinic\models\PhongKhamDirectSale;
use backend\modules\customer\models\query\Dep365CustomerOnlineQuery;
use backend\modules\location\models\District;
use backend\modules\setting\models\Dep365CoSo;
use backend\modules\user\models\User;
use backend\modules\user\models\UserTimelineModel;
use common\helpers\MyHelper;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;
use yii\elasticsearch\Exception;
use yii\helpers\ArrayHelper;
use yii\queue\JobInterface;

/**
 * This is the model class for table "dep365_customer_online".
 *
 * @property int $id
 * @property string $forename
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
 */
class Dep365CustomerOnline extends CustomerModel implements JobInterface
{
    const DAT_HEN_DEN = 1;
    const DAT_HEN_KHONG_DEN = 2;

    const FAIL_STATUS_KH_TIEM_NANG = 1;

    const SCENARIO_DAT_HEN = 'dat_hen';
    const SCENARIO_TU_VAN = 'required_note';
    const SCENARIO_ADMIN = 'admin';

    public $phoneConfirm = false;

    const CUSTOMER_WITH_ONLINE = 1;

    public $time_lichHen;

    /**
     * {@inheritdoc}
     */


    public function execute($queue)
    {
    }

    public static function find()
    {
        return new Dep365CustomerOnlineQuery(get_called_class());
    }

    public function behaviors()
    {
        return [
            'slug' => [
                'class' => SluggableBehavior::class,
//                'attribute' => 'name',
//                'slugAttribute' => 'slug',
                'immutable' => false, //only create 1
                'ensureUnique' => true, //
                'value' => function () {
                    return MyHelper::createAlias($this->name);
                }
            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
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
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'date_lichhen',
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
                    ActiveRecord::EVENT_BEFORE_INSERT => 'customer_come_date',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'customer_come_date',
                ],
                'value' => function () {
                    if ($this->customer_come != null) {
                        return strtotime(date('d-m-Y', $this->customer_come));
                    }
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
            [['name', 'phone', 'sex', 'status', 'forename', 'agency_id'], 'required'],
            [['province'], 'required', 'when' => function ($model) {
                return $model->status != CustomerModel::STATUS_KBM;
            }, 'whenClient' => "function (attribute, value) {
                return $('#dep365customeronline-status').val() != '3';
            }"],
            [['nguon_online'], 'required', 'when' => function ($model) {
                return $model->agency_id != null;
            }, 'whenClient' => "function (attribute, value) {
                return $('#dep365customeronline-agency_id').val() != '';
            }"],
            [['nguon_online'], 'integer', 'message' => 'Nguồn khách hàng không được để trống'],
            [['id_dich_vu'], 'required', 'message' => 'Dịch vụ không được để trống'],
            [['id_dich_vu'], 'integer', 'message' => 'Dịch vụ không hợp lệ'],
            [['status', 'co_so', 'province', 'face_fanpage', 'face_post_id', 'status_fail', 'dat_hen_fail'], 'integer'],
            [['tt_kh', 'note', 'note_tinh_trang_kh', 'note_mong_muon_kh', 'note_direct_sale_ho_tro'], 'string'], //13-1-2019
            [['forename'], 'string', 'max' => 25],
            ['phone', 'telnumvn', 'exceptTelco' => ['landLine'], 'when' => function ($model) {
                return $model->province != '97';
            }, 'whenClient' => "function (attribute, value) {
                return $('#dep365customeronline-province').val() != '97';
            }"],
            ['phone', 'unique',
                'targetClass' => '\backend\modules\customer\models\Dep365CustomerOnline',
                'message' => 'Đã tồn tại số điện thoại khách hàng này',
                'filter' => function ($query) {
                    $query->andWhere(['not', ['id' => $this->getId()]]);
                }, 'when' => function ($model) {
                $oldModel = self::find()->where(['id' => $model->primaryKey])->one();
                return ($model->primaryKey == null || ($model->primaryKey != null && $oldModel != null && $model->phone != $oldModel->phone)) && $model->phoneConfirm != true;
            }, 'on' => [self::SCENARIO_TU_VAN, self::SCENARIO_DAT_HEN]],
            [['time_lichhen', 'co_so'], 'required', 'when' => function ($model) {
                return $model->status == CustomerModel::STATUS_DH;
            }, 'whenClient' => "function (attribute, value) {
                return $('#dep365customeronline-status').val() == '" . CustomerModel::STATUS_DH . "';
            }"],
            [['tt_kh'], 'required', 'when' => function ($model) {
                return $model->status == CustomerModel::STATUS_DH && $model->dat_hen == self::DAT_HEN_DEN;
            }, 'whenClient' => "function(attribute, value){
                return $('#dep365customeronline-status').val() == '" . CustomerModel::STATUS_DH . "' && $('#dep365customeronline-dat_hen').val() == '" . self::DAT_HEN_DEN . "';
            }"],
            [['face_fanpage'], 'required', 'when' => function ($model) {
                return $model->nguon_online == 1;
            }, 'whenClient' => "function (attribute, value) {
                return $('#dep365customeronline-nguon_online').val() == '1';
            }"],
            [['status_fail'], 'required', 'when' => function ($model) {
                return $model->status == CustomerModel::STATUS_FAIL;
            }, 'whenClient' => "function (attribute, value) {
                return $('#dep365customeronline-status').val() == '" . CustomerModel::STATUS_FAIL . "';
            }"],
            [['phoneConfirm', 'dat_hen', 'created_at', 'updated_at'], 'integer'],
            [['name', 'slug', 'birthday', 'ngaythang', 'face_customer'], 'string', 'max' => 255],
            [['birthday'], 'checkBirthday'],
            [['face_customer'], 'required', 'when' => function ($model) {
                return $model->nguon_online == 1;
            }, 'whenClient' => "function (attribute, value) {
                return $('#dep365customeronline-nguon_online').val() == '1';
            }", 'on' => [self::SCENARIO_TU_VAN, self::SCENARIO_ADMIN]],
            ['face_customer', 'url', 'defaultScheme' => 'https', 'on' => [self::SCENARIO_TU_VAN, self::SCENARIO_ADMIN]],
            [['face_customer'], 'match', 'pattern' => '/^(https?:\/\/)?(www\.)?facebook.com\/[a-zA-Z0-9(\.\?)?]/', 'on' => [self::SCENARIO_TU_VAN, self::SCENARIO_ADMIN]],
            [['dat_hen_fail'], 'required', 'when' => function ($model) {
                return $model->status == CustomerModel::STATUS_DH && $model->dat_hen == Dep365CustomerOnline::DAT_HEN_KHONG_DEN;
            }, 'whenClient' => "function(attribute, value){
                return $('#dep365customeronline-status').val() == '" . CustomerModel::STATUS_DH . "' && $('#dep365customeronline-dat_hen').val() == '" . Dep365CustomerOnline::DAT_HEN_KHONG_DEN . "'
            }"],
            [['dat_hen'], 'required', 'when' => function ($model) {
                return $model->status == CustomerModel::STATUS_DH;
            }, 'on' => [self::SCENARIO_DAT_HEN]],
            [['customer_come'], 'required', 'when' => function ($model) {
                return $model->status == CustomerModel::STATUS_DH && $model->dat_hen == Dep365CustomerOnline::DAT_HEN_DEN;
            }, 'whenClient' => "function(attribute, value){
                return $('#dep365customeronline-status').val() == '" . CustomerModel::STATUS_DH . "' && $('#dep365customeronline-dat_hen').val() == '" . Dep365CustomerOnline::DAT_HEN_DEN . "';
            }", 'on' => [self::SCENARIO_DAT_HEN]],
            [['customer_come'], 'checkCustomerCome', 'on' => [self::SCENARIO_DAT_HEN]],
//            [['customer_come'], 'string', 'max' => 255],
            /*[['note_tinh_trang_kh', 'note_mong_muon_kh', 'note_direct_sale_ho_tro'], 'required', 'when' => function ($model) {
                return $model->status == CustomerModel::STATUS_DH;
            }, 'whenClient' => "function(){
                return $('#dep365customeronline-status').val() == '" . CustomerModel::STATUS_DH . "';
            }"],*/
            [['note'], 'required', 'when' => function ($model) {
                return in_array($model->status, [CustomerModel::STATUS_DH, CustomerModel::STATUS_FAIL]);
            }, 'whenClient' => "function(){
                return [" . CustomerModel::STATUS_DH . ", " . CustomerModel::STATUS_FAIL . "].includes($('#dep365customeronline-status').val());
            }"],
//            [['note'], 'string', 'min' => 15], // 13 - 1 - 2019
//            [['note'], 'required', 'on' => self::SCENARIO_TU_VAN],
//            [['note'], 'string', 'min' => 15, 'on' => self::SCENARIO_TU_VAN],
            [['address', 'full_name'], 'string', 'max' => 255],
            [['note_direct', 'customer_mongmuon', 'customer_thamkham'], 'string'],
            [['customer_gen', 'ngay_tao', 'date_lichhen', 'customer_come_date', 'remind_id'], 'integer'],
            [['time_lichhen', 'time_lichHen', 'customer_come', 'district'], 'safe'],
            [['note_remind_call'], 'string', 'when' => function ($model) {
                return ($model->status == CustomerModel::STATUS_DH && $model->dat_hen == self::DAT_HEN_KHONG_DEN);
            }],
            [['remind_call_time'], 'safe', 'when' => function ($model) {
                return ($model->status == CustomerModel::STATUS_KBM || $model->status == CustomerModel::STATUS_FAIL || ($model->status == CustomerModel::STATUS_DH && $model->dat_hen == self::DAT_HEN_KHONG_DEN));
            }],
            [['nguoi_gioi_thieu'], 'integer'],
            [['permission_user'], 'integer', 'on' => self::SCENARIO_ADMIN]
        ];
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'note' => Yii::t('backend', 'Mong muốn khách hàng'),
            'note_remind_call' => Yii::t('backend', 'Ghi chú nhắc lịch'),
            'remind_call_time' => Yii::t('backend', 'Khi nào nên gọi lại'),
            'nguoi_gioi_thieu' => Yii::t('backend', 'Người giới thiệu'),
        ]);
    }

    public function checkBirthday()
    {
        if ($this->birthday != null) {
            if ($this->birthday != date('d-m-Y', strtotime($this->birthday))) {
                $this->addError('birthday', Yii::t('backend', 'Ngày sinh không đúng định dạng dd-mm-yyyy'));
            }
        }
    }

    public function checkCustomerCome()
    {
        if (strtotime(date('d-m-Y', strtotime($this->customer_come))) < strtotime(date('d-m-Y', strtotime($this->time_lichhen)))) {
            $this->addError('customer_come', Yii::t('backend', 'Thời gian khách đến không được nhỏ hơn ngày giờ lịch hẹn'));
        }
    }

    /*
     * Lấy direct sale
     */
    public static function getDirectSale($id)
    {
        $directSale = UserProfile::find()->where(['user_id' => $id])->one();
        if ($directSale !== null) {
            return $directSale->fullname;
        }
    }

    /*
     * Lấy khách hàng theo thời gian cho sẵn theo tình trạng khách hàng
     */
    public static function getCustomerWithStatus($createdAtStart, $createdAtEnd, $idUser = null)
    {
        $from = strtotime($createdAtStart);
        $to = strtotime($createdAtEnd) + 86399;

        $customer = self::find()->where(['between', 'created_at', $from, $to])->findCustomerOfOnline();
        if ($idUser != null) {
            $customer->andWhere(['permission_user' => $idUser]);
        }

        $customer = $customer->all();
        $result = [];
        foreach ($customer as $key => $value) {
            $day = date('Y-m-d', $value->created_at);
            if (!array_key_exists($day, $result)) {
                $result[$day] = [
                    'day' => $day,
                    'dat_hen' => 0,
                    'fail' => 0,
                    'khong_bat_may' => 0,
                ];
            }
            if ($value->status == 1) {
                $result[$day]['dat_hen']++;
            }
            if ($value->status == 2) {
                $result[$day]['fail']++;
            }
            if ($value->status == 3) {
                $result[$day]['khong_bat_may']++;
            }
        }

        return array_values($result);
    }

    public function getAgencyHasOne()
    {
        return $this->hasOne(Dep365Agency::class, ['id' => 'agency_id']);
    }

    public function getSentSmsHasMany()
    {
        return $this->hasMany(Dep365SendSms::class, ['customer_id' => 'id']);
    }

    public function getTimeDatHenHasOne()
    {
        return $this->hasOne(Dep365CustomerOnlineDathenTime::class, ['customer_online_id' => 'id'])
            ->limit(1)
            ->orderBy(['dep365_customer_online_dathen_time.id' => SORT_DESC]);
    }

    public function getCustomerOnlineFailStatusTree()
    {
        return $this->hasOne(Dep365CustomerOnlineFailStatusTree::class, ['customer_online_id' => 'id']);
    }

    public function getCustomerOnlinetreeHasOne()
    {
        return $this->hasOne(Dep365CustomerOnlineTree::class, ['customer_online_id' => 'id']);
    }

    public static function getFailStatusCustomerOnline()
    {
        $result = [];
        $data = Dep365CustomerOnlineFailStatus::getCustomerOnlineStatusFail();
        foreach ($data as $item) {
            $result[$item->id] = $item->name;
        }
        return $result;
    }

    public static function getCoSoDep365()
    {
        $result = [];
        $coso = Dep365CoSo::getCoSo();
        foreach ($coso as $item) {
            $result[$item->id] = $item->name;
        }
        return $result;
    }


    public static function getFanpageFacebook()
    {
        $cache = Yii::$app->cache;
        $key = 'redis-get-fanpage-facebook';
        $result = $cache->get($key);
        if ($result === false) {
            $result = [];
            $fanpage = Dep365CustomerOnlineFanpage::find()->published()->all();
            foreach ($fanpage as $item) {
                $result[$item->id] = $item->name;
            }
            $cache->set($key, $result);
        }
        return $result;
    }

    public function getFanpageFacebookHasOne()
    {
        return $this->hasOne(Dep365CustomerOnlineFanpage::class, ['id' => 'face_fanpage']);
    }

    public static function getStatusCustomerOnline()
    {
        $result = [];
        $status = Dep365CustomerOnlineStatus::getStatusCustomerOnline();
        foreach ($status as $item) {
            $result[$item->id] = $item->name;
        }
        return $result;
    }

    public function getUserTimelineByCustomerId($id)
    {
        $query = UserTimelineModel::find()
            ->select([
                UserTimelineModel::tableName() . '.created_at',
                UserTimelineModel::tableName() . '.action',
                UserTimelineModel::tableName() . '.customer_id',
                UserTimelineModel::tableName() . '.user_id',
            ])
            ->joinWith(['nameCustomerHasOne', 'nameUserHasOne'])
            ->where(['user_timeline.customer_id' => $id])
            ->groupBy([
                UserTimelineModel::tableName() . '.created_at',
                UserTimelineModel::tableName() . '.action',
                UserTimelineModel::tableName() . '.customer_id',
                UserTimelineModel::tableName() . '.user_id',
            ])
            ->orderBy(['user_timeline.created_at' => SORT_DESC]);
        $data = $query->all();
        return $data;
    }

    public static function getListAllCustomer()
    {
        $listAccept = Dep365CustomerOnlineCome::find()->published()->where([
            'accept' => Dep365CustomerOnlineCome::STATUS_ACCEPT
        ])->all();
        return self::find()->where([
            'status' => self::STATUS_DH,
            'dat_hen' => self::DAT_HEN_DEN,
        ])->andWhere([
            'IN', 'customer_come_time_to', ArrayHelper::map($listAccept, 'id', 'id')
        ])->all();
    }


    public function afterDelete()
    {
        $phone = $this->getAttribute('phone');
        $cache = Yii::$app->cache;
        $key = 'get-customers-by-phone-' . $phone;
        $cache->delete($key);
        parent::afterDelete(); // TODO: Change the autogenerated stub
    }

    public function afterSave($insert, $changedAttributes)
    {
        $phone = $this->getAttribute('phone');
        $cache = Yii::$app->cache;
        $key = 'get-customers-by-phone-' . $phone;
        $cache->delete($key);
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

}
