<?php

namespace backend\modules\clinic\models;

use backend\models\CustomerModel;
use backend\models\doanhthu\DonHangModel;
use backend\models\doanhthu\ThanhToanModel;
use backend\modules\clinic\models\search\PhongKhamDonHangSearch;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\user\models\User;
use common\models\UserProfile;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * This is the model class for table "phong_kham_don_hang_w_thanh_toan".
 *
 * @property int $id
 * @property int $customer_id
 * @property int $phong_kham_don_hang_id
 * @property string $tien_thanh_toan
 * @property int $loai_thanh_toan
 * @property int $tam_ung
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class PhongKhamDonHangWThanhToan extends ThanhToanModel
{
    const SCENARIO_UPDATE_PAYMENT = 'update_payment';
    const SCENARIO_ACCEPT_HOAN_COC = 'accept_hoan_coc';
    const UNVIEW_HOAN_COC = 0;
    const ACCEPT_HOAN_COC = 1;
    const REJECT_HOAN_COC = 2;
    const HOAN_COC_TYPE = [
        self::UNVIEW_HOAN_COC => 'Chờ duyệt',
        self::ACCEPT_HOAN_COC => 'Đã duyệt',
        self::REJECT_HOAN_COC => 'Không duyệt',
    ];

    public $customer_name;
    public $order_code;
    public $tam_ung_name;
    public $dat_coc;
    public $tien_thuc_thu;

    public static function getCountReturnDeposit()
    {
        $cache = \Yii::$app->cache;
        $key = 'get-count-return-deposit-menu-left';

        $data = $cache->get($key);

        if ($data == false) {
            $data = self::find()->where(['tam_ung' => self::HOAN_COC])->andWhere([
                'OR',
                'accept_hoan_coc' => self::UNVIEW_HOAN_COC,
                'accept_hoan_coc IS NULL'
            ])->count();
            $cache->set($key, $data, 600);
        }
        return $data;
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['co_so']
                ],
                'value' => function () {
                    $customer = Dep365CustomerOnline::find()->where(['id' => $this->customer_id])->one();
                    if ($customer == null) {
                        return null;
                    }
                    return $customer->co_so;
                }
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'phong_kham_don_hang_id', 'tien_thanh_toan', 'ngay_tao'], 'required'],
            [['tam_ung'], 'checkTamUng'],
            [['customer_id', 'phong_kham_don_hang_id', 'loai_thanh_toan', 'tam_ung', 'co_so'], 'integer'],
            [['customer_name', 'order_code', 'tam_ung_name', 'tien_thanh_toan'], 'string', 'max' => 25],
            [['tien_thanh_toan'], 'checkMoney'],
            [['tien_thanh_toan'], 'validateMoney'],
            [['phong_kham_don_hang_id'], 'checkDonHang', 'on' => self::SCENARIO_UPDATE_PAYMENT],
            [['accept_hoan_coc'], 'integer', 'on' => self::SCENARIO_ACCEPT_HOAN_COC],
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
            'customer_name' => Yii::t('backend', 'Khách hàng'),
            'phong_kham_don_hang_id' => Yii::t('backend', 'Đơn hàng'),
            'order_code' => Yii::t('backend', 'Đơn hàng'),
            'tien_thanh_toan' => Yii::t('backend', 'Số tiền thanh toán'),
            'loai_thanh_toan' => Yii::t('backend', 'Loại thanh toán'),
            'tam_ung' => Yii::t('backend', 'Hình thức'),
            'tam_ung_name' => Yii::t('backend', 'Hình thức'),
            'dat_coc' => Yii::t('backend', 'Đặt cọc'),
            'accept_hoan_coc' => Yii::t('backend', 'Duyệt hoàn cọc'),
            'co_so' => Yii::t('backend', 'Cơ sở'),
            'status' => Yii::t('backend', 'Status'),
            'ngay_tao' => Yii::t('backend', 'Ngày thanh toán'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
        ];
    }

    public function afterDelete()
    {
        $order_id = $this->primaryKey;
        $cache = Yii::$app->cache;
        $key = 'get-dat-coc-by-order-pkdh-w-thanh-toan-' . $order_id;
        $cache->delete($key);

        $key1 = 'get-thanh-toan-by-order-pkdh-w-thanh-toan-' . $order_id;
        $cache->delete($key1);

        $key2 = 'get-hoan-coc-by-order-pkdh-w-thanh-toan-' . $order_id;
        $cache->delete($key2);

        $key3 = 'get-tra-gop-by-order-pkdh-w-thanh-toan-' . $order_id;
        $cache->delete($key3);

        parent::afterDelete(); // TODO: Change the autogenerated stub
    }

    public function afterSave($insert, $changedAttributes)
    {
        $order_id = $this->primaryKey;
        $cache = Yii::$app->cache;
        $key = 'get-dat-coc-by-order-pkdh-w-thanh-toan-' . $order_id;
        $cache->delete($key);

        $key1 = 'get-thanh-toan-by-order-pkdh-w-thanh-toan-' . $order_id;
        $cache->delete($key1);

        $key2 = 'get-hoan-coc-by-order-pkdh-w-thanh-toan-' . $order_id;
        $cache->delete($key2);

        $key3 = 'get-tra-gop-by-order-pkdh-w-thanh-toan-' . $order_id;
        $cache->delete($key3);

        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    public function checkDonHang()
    {
        $order = PhongKhamDonHang::find()->where(['id' => $this->phong_kham_don_hang_id])->one();
        if ($order == null) {
            $this->addError('phong_kham_don_hang_id', 'Không tìm thấy đơn hàng!');
        }
        $totalPrice = (new Query())->from(PhongKhamDonHangWOrder::tableName())->where(["phong_kham_don_hang_id" => $order->primaryKey])->sum('thanh_tien');
        $totalThanhToan = (new Query())->from(self::tableName())->where(["phong_kham_don_hang_id" => $order->primaryKey, 'tam_ung' => [ThanhToanModel::THANH_TOAN, ThanhToanModel::DAT_COC]])->andWhere(['<>', 'id', $this->primaryKey])->sum('tien_thanh_toan');
        $totalHoanCoc = (new Query())->from(self::tableName())->where(["phong_kham_don_hang_id" => $order->primaryKey, 'tam_ung' => [ThanhToanModel::HOAN_COC]])->sum('tien_thanh_toan');
        if ($totalPrice == '') {
            $totalPrice = 0;
        }
        if ($totalThanhToan == '') {
            $totalThanhToan = 0;
        }
        if ($totalHoanCoc == '') {
            $totalHoanCoc = 0;
        }
        $price = $totalPrice - $totalThanhToan + $totalHoanCoc;
        if ($price <= 0) {
            $this->addError('phong_kham_don_hang_id', 'Đơn hàng này đã thanh toán đủ!');
        }
    }

    public function checkMoney()
    {
        if ($this->tien_thanh_toan <= 0) {
            $this->addError('tien_thanh_toan', 'Vui lòng nhập tiền thanh toán!');
        }
    }

    public function validateMoney()
    {
        $order = PhongKhamDonHang::find()->where(['id' => $this->phong_kham_don_hang_id])->one();
        if ($order == null) {
            $this->addError('phong_kham_don_hang_id', 'Không tìm thấy đơn hàng!');
        }
        $totalPrice = (new Query())->from(PhongKhamDonHangWOrder::tableName())->where(["phong_kham_don_hang_id" => $order->primaryKey])->sum('thanh_tien');
        $query = (new Query())->from(self::tableName())->where(["phong_kham_don_hang_id" => $order->primaryKey, 'tam_ung' => [ThanhToanModel::THANH_TOAN, ThanhToanModel::DAT_COC]]);
        if ($this->primaryKey != null) $query->andWhere(['<>', 'id', $this->primaryKey]);
        $totalThanhToan = $query->sum('tien_thanh_toan');
        if ($totalPrice == '') {
            $totalPrice = 0;
        }
        if ($totalThanhToan == '') {
            $totalThanhToan = 0;
        }
        if ($this->tam_ung != self::HOAN_COC && (int)str_replace('.', '', $this->tien_thanh_toan) > $totalPrice - $totalThanhToan) {
            $this->addError('tien_thanh_toan', 'Chỉ có thể thanh toán ' . number_format($totalPrice - $totalThanhToan, 0, '', '.'));
        }
        if ($this->tam_ung == self::HOAN_COC && (int)str_replace('.', '', $this->tien_thanh_toan) > $totalThanhToan) {
            $this->addError('tien_thanh_toan', 'Chỉ có thể hoàn cọc ' . number_format($totalThanhToan, 0, '', '.'));
        }
    }

    public function checkTamUng()
    {
        $order = PhongKhamDonHang::find()->where(['id' => $this->phong_kham_don_hang_id])->one();
        if ($order != null) {
            if ($this->tam_ung == ThanhToanModel::DAT_COC && PhongKhamDonHangWThanhToan::find()->where(['phong_kham_don_hang_id' => $order->primaryKey, 'tam_ung' => ThanhToanModel::DAT_COC])->andWhere(['<>', 'id', $this->primaryKey])->one() != null) {
                $this->addError('tam_ung', 'Đơn hàng đã được đặt cọc!');
            }
        }
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

    public function getTotalDatCoc($params = null, $customer_id = null)
    {
        $query = self::find()->select([self::tableName() . '.tien_thanh_toan'])->joinWith(['customerHasOne', 'donHangHasOne'])->where(['tam_ung' => self::DAT_COC]);
        $user = new User();
        $roleUser = $user->getRoleName(\Yii::$app->user->id);
        if ($roleUser == User::USER_DIRECT_SALE) {
            $query->andFilterWhere(['dep365_customer_online.directsale' => \Yii::$app->user->id]);
        }
        if ($roleUser == User::USER_LE_TAN) {
            $co_so = $user->getCoso(\Yii::$app->user->id);
            $query->andFilterWhere([self::tableName() . '.co_so' => $co_so->permission_coso]);
        }
        $customer = null;
        if ($customer_id != null) {
            $customer = Clinic::find()->where(['id' => $customer_id])->one();
        }
        $searchModel = new PhongKhamDonHangSearch();
        $searchModel->load($params);
        if ($searchModel->button == '' && $customer == null) {
            $searchModel->type_search_create = 'range';
            $searchModel->creation_time_from = date('01-m-Y');
            $searchModel->creation_time_to = date('d-m-Y');
        }
        if ($searchModel->button == 2) {
            $searchModel->creation_time_from = date('d-m-Y', strtotime(date('d-m-Y') . ' -1 days'));
            $searchModel->type_search_create = 'date';
        }
        if ($searchModel->button == 3) {
            $searchModel->creation_time_from = date('d-m-Y', strtotime(date('d-m-Y')));
            $searchModel->type_search_create = 'date';
        }
        if ($customer != null) {
            $query->andWhere([self::tableName() . '.customer_id' => $customer_id]);
            $searchModel->keyword = $customer->customer_code;
            $searchModel->direct_sale_id = $customer->directsale;
            $searchModel->tu_van_vien = $customer->permission_user;
        }
        /* Search ngày tạo */
        if (isset($searchModel->type_search_create)) {
            if ($searchModel->type_search_create == 'date') {
                if (isset($searchModel->creation_time_from) && $searchModel->creation_time_from != null) {
                    $from = strtotime($searchModel->creation_time_from);
                    $to = strtotime($searchModel->creation_time_from) + 86399;
                    $query->andFilterWhere(['>=', self::tableName() . '.created_at', $from]);
                    $query->andFilterWhere(['<=', self::tableName() . '.created_at', $to]);
                }
            } else {
                if (
                    isset($searchModel->creation_time_from) && isset($searchModel->creation_time_to) &&
                    $searchModel->creation_time_from != null && $searchModel->creation_time_to != null
                ) {
                    $from = strtotime($searchModel->creation_time_from);
                    $to = strtotime($searchModel->creation_time_to) + 86399;
                    $query->andFilterWhere(['>=', self::tableName() . '.created_at', $from]);
                    $query->andFilterWhere(['<=', self::tableName() . '.created_at', $to]);
                }
            }
        }

        /* Search ngày thanh toán */
        if (isset($searchModel->type_search_payment)) {
            if ($searchModel->type_search_payment == 'date') {
                if (isset($searchModel->payment_time_from) && $searchModel->payment_time_from != null) {
                    $from = strtotime($searchModel->payment_time_from);
                    $to = strtotime($searchModel->payment_time_from) + 86399;
                    $query->andFilterWhere(['>=', self::tableName() . '.ngay_tao', $from]);
                    $query->andFilterWhere(['<=', self::tableName() . '.ngay_tao', $to]);
                }
            } else {
                if (
                    isset($searchModel->payment_time_from) && isset($searchModel->payment_time_to) &&
                    $searchModel->payment_time_from != null && $searchModel->payment_time_to != null
                ) {
                    $from = strtotime($searchModel->payment_time_from);
                    $to = strtotime($searchModel->payment_time_to) + 86399;
                    $query->andFilterWhere(['>=', self::tableName() . '.ngay_tao', $from]);
                    $query->andFilterWhere(['<=', self::tableName() . '.ngay_tao', $to]);
                }
            }
        }

        /* Search name, phone, code */
        if (isset($searchModel->keyword) && $searchModel->keyword != null) {
            $searchModel->keyword = trim($searchModel->keyword);
            $searchModel->keyword = preg_replace('/\s+/', ' ', $searchModel->keyword);
            $query->andFilterWhere([
                'or',
                ['like', CustomerModel::tableName() . '.full_name', $searchModel->keyword],
                ['like', CustomerModel::tableName() . '.forename', $searchModel->keyword],
                ['like', CustomerModel::tableName() . '.name', $searchModel->keyword],
                ['like', CustomerModel::tableName() . '.phone', $searchModel->keyword],
                ['like', CustomerModel::tableName() . '.customer_code', $searchModel->keyword],
                ['like', DonHangModel::tableName() . '.order_code', $searchModel->keyword],
            ]);
        }

        // grid filtering conditions
        if (isset($searchModel->direct_sale_id) && $searchModel->direct_sale_id != null) {
            $query->andFilterWhere([PhongKhamDonHang::tableName() . '.direct_sale_id' => $searchModel->direct_sale_id]);
        }

        if (isset($searchModel->tu_van_vien) && $searchModel->tu_van_vien != null) {
            $query->andFilterWhere([CustomerModel::tableName() . '.permission_user' => $searchModel->tu_van_vien]);
        }

        if (isset($searchModel->co_so) && $searchModel->co_so != null) {
            $query->andFilterWhere([CustomerModel::tableName() . '.co_so' => $searchModel->co_so]);
        }

        if (isset($searchModel->id_dich_vu) && $searchModel->id_dich_vu != null) {
            $query->andFilterWhere([CustomerModel::tableName() . '.id_dich_vu' => $searchModel->id_dich_vu]);
        }

        return $query->sum('tien_thanh_toan');
    }

    public function getTotalThanhToan($params = null, $customer_id = null)
    {
        $query = self::find()->select([self::tableName() . '.tien_thanh_toan'])->joinWith(['customerHasOne', 'donHangHasOne'])->where(['tam_ung' => self::THANH_TOAN]);
        $user = new User();
        $roleUser = $user->getRoleName(\Yii::$app->user->id);
        if ($roleUser == User::USER_DIRECT_SALE) {
            $query->andFilterWhere(['dep365_customer_online.directsale' => \Yii::$app->user->id]);
        }
        if ($roleUser == User::USER_LE_TAN) {
            $co_so = $user->getCoso(\Yii::$app->user->id);
            $query->andFilterWhere([self::tableName() . '.co_so' => $co_so->permission_coso]);
        }
        $customer = null;
        if ($customer_id != null) {
            $customer = Clinic::find()->where(['id' => $customer_id])->one();
        }
        $searchModel = new PhongKhamDonHangSearch();
        $searchModel->load($params);
        if ($searchModel->button == '' && $customer == null) {
            $searchModel->type_search_create = 'range';
            $searchModel->creation_time_from = date('01-m-Y');
            $searchModel->creation_time_to = date('d-m-Y');
        }
        if ($searchModel->button == 2) {
            $searchModel->creation_time_from = date('d-m-Y', strtotime(date('d-m-Y') . ' -1 days'));
            $searchModel->type_search_create = 'date';
        }
        if ($searchModel->button == 3) {
            $searchModel->creation_time_from = date('d-m-Y', strtotime(date('d-m-Y')));
            $searchModel->type_search_create = 'date';
        }
        if ($customer != null) {
            $query->andWhere([self::tableName() . '.customer_id' => $customer_id]);
            $searchModel->keyword = $customer->customer_code;
            $searchModel->direct_sale_id = $customer->directsale;
            $searchModel->tu_van_vien = $customer->permission_user;
        }
        /* Search ngày tạo */
        if (isset($searchModel->type_search_create)) {
            if ($searchModel->type_search_create == 'date') {
                if (isset($searchModel->creation_time_from) && $searchModel->creation_time_from != null) {
                    $from = strtotime($searchModel->creation_time_from);
                    $to = strtotime($searchModel->creation_time_from) + 86399;
                    $query->andFilterWhere(['>=', PhongKhamDonHang::tableName() . '.created_at', $from]);
                    $query->andFilterWhere(['<=', PhongKhamDonHang::tableName() . '.created_at', $to]);
                }
            } else {
                if (
                    isset($searchModel->creation_time_from) && isset($searchModel->creation_time_to) &&
                    $searchModel->creation_time_from != null && $searchModel->creation_time_to != null
                ) {
                    $from = strtotime($searchModel->creation_time_from);
                    $to = strtotime($searchModel->creation_time_to) + 86399;
                    $query->andFilterWhere(['>=', PhongKhamDonHang::tableName() . '.created_at', $from]);
                    $query->andFilterWhere(['<=', PhongKhamDonHang::tableName() . '.created_at', $to]);
                }
            }
        }

        /* Search ngày thanh toán */
        if (isset($searchModel->type_search_payment)) {
            if ($searchModel->type_search_payment == 'date') {
                if (isset($searchModel->payment_time_from) && $searchModel->payment_time_from != null) {
                    $from = strtotime($searchModel->payment_time_from);
                    $to = strtotime($searchModel->payment_time_from) + 86399;
                    $query->andFilterWhere(['>=', self::tableName() . '.ngay_tao', $from]);
                    $query->andFilterWhere(['<=', self::tableName() . '.ngay_tao', $to]);
                }
            } else {
                if (
                    isset($searchModel->payment_time_from) && isset($searchModel->payment_time_to) &&
                    $searchModel->payment_time_from != null && $searchModel->payment_time_to != null
                ) {
                    $from = strtotime($searchModel->payment_time_from);
                    $to = strtotime($searchModel->payment_time_to) + 86399;
                    $query->andFilterWhere(['>=', self::tableName() . '.ngay_tao', $from]);
                    $query->andFilterWhere(['<=', self::tableName() . '.ngay_tao', $to]);
                }
            }
        }

        /* Search name, phone, code */
        if (isset($searchModel->keyword) && $searchModel->keyword != null) {
            $searchModel->keyword = trim($searchModel->keyword);
            $searchModel->keyword = preg_replace('/\s+/', ' ', $searchModel->keyword);
            $query->andFilterWhere([
                'or',
                ['like', CustomerModel::tableName() . '.full_name', $searchModel->keyword],
                ['like', CustomerModel::tableName() . '.forename', $searchModel->keyword],
                ['like', CustomerModel::tableName() . '.name', $searchModel->keyword],
                ['like', CustomerModel::tableName() . '.phone', $searchModel->keyword],
                ['like', CustomerModel::tableName() . '.customer_code', $searchModel->keyword],
                ['like', DonHangModel::tableName() . '.order_code', $searchModel->keyword],
            ]);
        }

        // grid filtering conditions
        if (isset($searchModel->direct_sale_id) && $searchModel->direct_sale_id != null) {
            $query->andFilterWhere([PhongKhamDonHang::tableName() . '.direct_sale_id' => $searchModel->direct_sale_id]);
        }

        if (isset($searchModel->tu_van_vien) && $searchModel->tu_van_vien != null) {
            $query->andFilterWhere([CustomerModel::tableName() . '.permission_user' => $searchModel->tu_van_vien]);
        }

        if (isset($searchModel->co_so) && $searchModel->co_so != null) {
            $query->andFilterWhere([CustomerModel::tableName() . '.co_so' => $searchModel->co_so]);
        }

        if (isset($searchModel->id_dich_vu) && $searchModel->id_dich_vu != null) {
            $query->andFilterWhere([CustomerModel::tableName() . '.id_dich_vu' => $searchModel->id_dich_vu]);
        }

        return $query->sum('tien_thanh_toan');
    }

    public function getDatCocByOrder($order_id)
    {
        $data = self::find()->select(['tien_thanh_toan'])->where(['phong_kham_don_hang_id' => $order_id, 'tam_ung' => self::DAT_COC])->sum('tien_thanh_toan');
        return $data;
    }

    public static function getDatCocByOrderStatic($order_id)
    {
        $model = new self;
        return $model->getDatCocByOrder($order_id);
    }

    public function getThanhToanByOrder($order_id)
    {
        $data = self::find()->select(['tien_thanh_toan'])->where(['phong_kham_don_hang_id' => $order_id, 'tam_ung' => self::THANH_TOAN])->sum('tien_thanh_toan');
        return $data;
    }
    public static function getThanhToanByOrderStatic($order_id)
    {
        $model = new self;
        return $model->getThanhToanByOrder($order_id);
    }

    public function getHoanCocByOrder($order_id)
    {
        $data = self::find()->select(['tien_thanh_toan'])->where(['phong_kham_don_hang_id' => $order_id, 'tam_ung' => self::HOAN_COC])->sum('tien_thanh_toan');
        return $data;
    }

    public function getTraGopByOrder($order_id)
    {
        $data = self::find()->select(['tien_thanh_toan'])->where(['phong_kham_don_hang_id' => $order_id, 'loai_thanh_toan' => 3])->sum('tien_thanh_toan');
        return $data;
    }

    // CK + tiền mặt thành tiền mặt
    // 1 la tien mac , 4 la chuyen khoan $tien_mat = false , true la the
    public function getDatCocByOrderChiTiet($order_id, $tien_mat = false)
    {
        if ($tien_mat) {
            return self::find()->select(['tien_thanh_toan'])->where(['phong_kham_don_hang_id' => $order_id, 'tam_ung' => self::DAT_COC, 'loai_thanh_toan' => [1, 4]])->sum('tien_thanh_toan');
        }
        return self::find()->select(['tien_thanh_toan'])->where(['phong_kham_don_hang_id' => $order_id, 'tam_ung' => self::DAT_COC, 'loai_thanh_toan' => 2])->sum('tien_thanh_toan');
    }

    public function getThanhToanByOrderChiTiet($order_id, $tien_mat = false)
    {
        if ($tien_mat) {
            return self::find()->select(['tien_thanh_toan'])->where(['phong_kham_don_hang_id' => $order_id, 'tam_ung' => self::THANH_TOAN, 'loai_thanh_toan' => [1, 4]])->sum('tien_thanh_toan');
        }
        return self::find()->select(['tien_thanh_toan'])->where(['phong_kham_don_hang_id' => $order_id, 'tam_ung' => self::THANH_TOAN, 'loai_thanh_toan' => 2])->sum('tien_thanh_toan');
    }

    // hoan coc
    public function getHoanCocByOrderChiTiet($order_id, $tien_mat = false)
    {
        if ($tien_mat) {
            return self::find()->select(['tien_thanh_toan'])->where(['phong_kham_don_hang_id' => $order_id, 'tam_ung' => self::HOAN_COC, 'loai_thanh_toan' => [1, 4]])->sum('tien_thanh_toan');
        }
        return self::find()->select(['tien_thanh_toan'])->where(['phong_kham_don_hang_id' => $order_id, 'tam_ung' => self::HOAN_COC, 'loai_thanh_toan' => 2])->sum('tien_thanh_toan');
    }
}
