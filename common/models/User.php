<?php

namespace common\models;

use backend\modules\clinic\models\PhongKhamLichDieuTri;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\setting\models\Dep365CoSo;
use backend\modules\user\models\RbacAuthItem_;
use backend\modules\user\models\UserSubRole;
use common\models\query\UserQuery;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $email
 * @property string $auth_key
 * @property string $access_token
 * @property string $oauth_client
 * @property string $oauth_client_user_id
 * @property string $publicIdentity
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $logged_at
 * @property string $password write-only password
 *
 * @property Auth[] $auths
 *
 * @property \common\models\UserProfile $userProfile
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_NOT_ACTIVE = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_DELETED = 3;

    const SCENARIO_CREATE = 'create';

    const EVENT_AFTER_SIGNUP = 'afterSignup';
    const EVENT_AFTER_LOGIN = 'afterLogin';

    const USER_MANAGER = 'user_manager';

    const USER_DEVELOP = 'user_develop';
    const USER_ADMINISTRATOR = 'user_administrator';
    const USER_MANAGER_LE_TAN = 'user_manager_le_tan';
    const USER_LE_TAN = 'user_le_tan';
    const USER_MANAGER_ONLINE = 'user_manager_online';
    const USER_REPORT = 'user_report';
    const USER_DATHEN = 'user_dathen';
    const USER_NHANVIEN_ONLINE = 'user_nhanvien_online';
    const USER_USERS = 'user_users';
    const USER_NHANSU = 'user_nhansu';
    const USER_PHONGKHAM = 'user_phongkham';

    const USER_COVAN = 'user_covan';
    const USER_QUANLY_PHONGKHAM = 'user_quanly_phongkham';

    const USER_STUDIO = 'user_studio'; //manager
    const USER_CHUP_HINH = 'user_chup_hinh';
    const USER_TK_NU_CUOI = 'user_tk_nu_cuoi';

    const USER_MANAGER_DIRECT_SALE = 'user_manager_direct_sale';
    const USER_DIRECT_SALE = 'user_direct_sale';

    const USER_MANAGER_CHAY_ADS = 'user_manager_chay_ads';
    const USER_CHAY_ADS = 'user_chay_ads';

    const USER_MANAGER_BAC_SI = 'user_manager_bac_si';
    const USER_BAC_SI = 'user_bac_si';

    const USER_TRO_THU = 'user_trothu';

    const USER_KE_TOAN = 'user_ke_toan';
    const USER_MANAGER_KE_TOAN = 'user_manager_ke_toan';

    const USER_MYAURIS = 'user_myauris';

    const USER_BIEN_TAP = 'user_bien_tap';
    const USER_MANAGER_BIEN_TAP = 'user_manager_bien_tap';

    const USER_SEO = 'user_seo';
    const USER_MANAGER_SEO = 'user_manager_seo';

    const USER_KIEM_SOAT = 'user_kiem_soat';
    const USER_MANAGER_KIEM_SOAT = 'user_manager_kiem_soat';

    const USER_SALE_RANG = 'user_sale_rang';

    const USER_SCREEN = 'user_screen';

    const USER_TEST = 'user_test';

    const USER_KY_THUAT_LABO = 'user_ky_thuat_labo';

    public $fullname;
    public $idpancake;
    public $phone;
    public $role_name;
    public $avatar;
    public $cover;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

//    public function __sleep()
//    {
//        return [];
//        //or     return ['db', 'query', 'data' ...];
//    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        $cache = Yii::$app->cache;
        $key = 'find-identity-user-' . $id;
        $data = $cache->get($key);
        if ($data == false) {
            $data = static::find()
                ->active()
                ->andWhere(['id' => $id])
                ->one();
            $cache->set($key, $data, 30 * 86400);
        }

        return $data;
    }

    /**
     * @return UserQuery
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    public function getCoso($id)
    {
        $cache = Yii::$app->cache;
        $key = 'get-co-so-user';

        $user = $cache->get($key);

        if ($user == false) {
            $user = User::find()->where(['id' => $id])->one();
            $cache->set($key, $user);
        }

        if (isset($user)) {
            return $user;
        }

        return null;
    }

    /*
     * Lấy toàn bộ nhân viên là bác sĩ
     */
    public static function getNhanVienBacSi($current_ekip = null)
    {
        $query = self::find()->select('user.id, user.status, user.permission_coso, user_profile.fullname')->joinWith(['userProfile'])
            ->where(['in', 'user.status', [User::STATUS_ACTIVE]])
            ->join('LEFT JOIN', 'rbac_auth_assignment', 'rbac_auth_assignment.user_id = id')
            ->andFilterWhere([
                'or',
                ['rbac_auth_assignment.item_name' => self::USER_BAC_SI],
                ['rbac_auth_assignment.item_name' => self::USER_MANAGER_BAC_SI]
            ]);
        $user = new \backend\modules\user\models\User();
        $roleName = $user->getRoleName(Yii::$app->user->id);
        if (!in_array($roleName, [
            self::USER_ADMINISTRATOR,
            self::USER_DEVELOP
        ])) {
            if (Yii::$app->user->identity->permission_coso == 3) {
                $query->andWhere(['permission_coso' => 3]);
            } else {
                $query->andWhere(['IN', 'permission_coso', [1, 2]]);
            }
            /*$query_used = self::find()
                ->select([self::tableName() . '.id'])
                ->joinWith(['lichDieuTriHasManyByBacSi'])
                ->join('LEFT JOIN', 'rbac_auth_assignment', 'rbac_auth_assignment.user_id = user.id')
                ->where(PhongKhamLichDieuTri::tableName() . '.time_end IS null')
                ->andWhere(['in', self::tableName() . '.status', [User::STATUS_ACTIVE]])
                ->andWhere(['rbac_auth_assignment.item_name' => self::USER_BAC_SI])
                ->andWhere(PhongKhamLichDieuTri::tableName() . '.id IS NOT null')
                ->groupBy(self::tableName() . '.id')
                ->indexBy('id');
            if ($current_ekip != null) {
                $query_used->andWhere(['<>', PhongKhamLichDieuTri::tableName() . '.ekip', $current_ekip]);
            }
            $bacsi_used = $query_used->all();
            if (count($bacsi_used) > 0) {
                $query->andWhere(['NOT IN', self::tableName() . '.id', array_keys($bacsi_used)]);
            }*/
        }
        $data = $query->all();

        return $data;
    }

    /*
     * Lấy toàn bộ nhân viên là trợ thủ
     */
    public static function getNhanVienTroThu($current_trothu = null)
    {
        $query = self::find()->select('user.id, user.status, user.permission_coso, user_profile.fullname')->joinWith(['userProfile'])
            ->where(['in', 'user.status', [User::STATUS_ACTIVE]])
            ->join('LEFT JOIN', 'rbac_auth_assignment', 'rbac_auth_assignment.user_id = id')
            ->andFilterWhere(['rbac_auth_assignment.item_name' => self::USER_TRO_THU]);
        $user = new \backend\modules\user\models\User();
        $roleName = $user->getRoleName(Yii::$app->user->id);
        /*$query_used = self::find()
            ->select([self::tableName() . '.id'])
            ->joinWith(['userProfile'])
            ->join('LEFT JOIN', 'rbac_auth_assignment', 'rbac_auth_assignment.user_id = user.id')
            ->join('LEFT JOIN', PhongKhamLichDieuTri::tableName(), '')
            ->where(PhongKhamLichDieuTri::tableName() . '.time_end IS null')
            ->andWhere(['in', self::tableName() . '.status', [User::STATUS_ACTIVE]])
            ->andWhere(['rbac_auth_assignment.item_name' => self::USER_BAC_SI])
            ->andWhere(PhongKhamLichDieuTri::tableName() . '.id IS NOT null')
            ->groupBy(self::tableName() . '.id')
            ->indexBy('id');
//        if ($current_ekip != null) $query_used->andWhere(['<>', PhongKhamLichDieuTri::tableName() . '.ekip', $current_ekip]);
        $bacsi_used = $query_used->all();*/
        if (!in_array($roleName, [self::USER_ADMINISTRATOR, self::USER_DEVELOP])) {
//            if (Yii::$app->user->identity->permission_coso == 3) {
//                $query->andWhere(['permission_coso' => 3]);
//            } else {
//                $query->andWhere(['IN', 'permission_coso', [1, 2]]);
//            }
            $query->andWhere(['permission_coso' => Yii::$app->user->identity->permission_coso]);
        }
        $data = $query->all();

        return $data;
    }

    public static function getNhanVienTroThuArray()
    {
        $user = self::getNhanVienTroThu();
        $result = [];
        foreach ($user as $item) {
            if ($item->userProfile != null) {
                if ($item->userProfile->fullname == '') {
                    $result[$item->id] = '-';
                } else {
                    $result[$item->id] = $item->userProfile->fullname;
                }
            }
        }

        return $result;
    }

    /*
     * Lấy toàn bộ phòng khám
     */
    public static function getPhongKham($current_room = true)
    {
        $query = self::find()->select('user.id, user.status, user_profile.fullname')->joinWith(['userProfile'])
            ->where(['in', 'user.status', [User::STATUS_ACTIVE]])
            ->join('LEFT JOIN', 'rbac_auth_assignment', 'rbac_auth_assignment.user_id = id')
            ->andFilterWhere(['rbac_auth_assignment.item_name' => self::USER_PHONGKHAM]);
        $user = new \backend\modules\user\models\User();
        $roleName = $user->getRoleName(Yii::$app->user->id);
        if (!in_array($roleName, [
            self::USER_ADMINISTRATOR,
            self::USER_DEVELOP
        ])) {
            $query->andWhere(['permission_coso' => Yii::$app->user->identity->permission_coso]);
            /*$query_used = self::find()
                ->select([self::tableName() . '.id'])
                ->joinWith(['lichDieuTriHasManyByRoom'])
                ->join('LEFT JOIN', 'rbac_auth_assignment', 'rbac_auth_assignment.user_id = user.id')
                ->where(PhongKhamLichDieuTri::tableName() . '.time_end IS null')
                ->andWhere(['in', self::tableName() . '.status', [User::STATUS_ACTIVE]])
                ->andWhere(['rbac_auth_assignment.item_name' => self::USER_PHONGKHAM])
                ->andWhere([self::tableName().'.permission_coso' => Yii::$app->user->identity->permission_coso])
                ->andWhere(PhongKhamLichDieuTri::tableName() . '.id IS NOT null')
                ->groupBy(self::tableName() . '.id')
                ->indexBy('id');
            if ($current_room != null) {
                $query_used->andWhere(['<>', PhongKhamLichDieuTri::tableName() . '.room_id', $current_room]);
            }
            $room_used = $query_used->all();
            if (count($room_used) > 0) {
                $query->andWhere(['NOT IN', self::tableName() . '.id', array_keys($room_used)]);
            }*/
        }
        $data = $query->all();

        return $data;
    }

    /*
     * Lấy toàn bộ nhân viên chạy Advertising là manager
     */
    public static function getNhanVienChayAdvertisingManager()
    {
        $data = self::find()->select('user.id, user.status, user_profile.fullname')->joinWith(['userProfile'])
            ->where(['in', 'user.status', [User::STATUS_ACTIVE]])
            ->join('LEFT JOIN', 'rbac_auth_assignment', 'rbac_auth_assignment.user_id = id')
            ->andWhere(['rbac_auth_assignment.item_name' => self::USER_MANAGER_CHAY_ADS])
            ->all();

        return $data;
    }

    /*
     * Lấy toàn bộ nhân viên chạy Advertising
     */
    public static function getNhanVienChayAdvertising()
    {
        $data = self::find()->select('user.id, user.status, user_profile.fullname')->joinWith(['userProfile'])
            ->where(['in', 'user.status', [User::STATUS_ACTIVE]])
            ->join('LEFT JOIN', 'rbac_auth_assignment', 'rbac_auth_assignment.user_id = id')
            ->andWhere(['rbac_auth_assignment.item_name' => self::USER_MANAGER_CHAY_ADS]);

        return $data->all();
    }

    /*
     * Lấy toàn bộ nhân viên Direct sale đang hoạt động
     */
    public static function getNhanVienTuDirectSale($current_directsale = null)
    {
        $user = new \backend\modules\user\models\User();
        $roleName = $user->getRoleName(Yii::$app->user->id);
        $query = self::find()
            ->select('user.id, user.status, user_profile.fullname')
            ->joinWith(['userProfile'])
            ->join('LEFT JOIN', 'rbac_auth_assignment', 'rbac_auth_assignment.user_id = user.id')
            ->where(['in', 'user.status', [User::STATUS_ACTIVE]])
            ->andWhere('user.username IS NOT NULL')
            ->andWhere('user_profile.fullname IS NOT NULL')
            ->andWhere([
                'or',
                ['rbac_auth_assignment.item_name' => self::USER_DIRECT_SALE],
                ['rbac_auth_assignment.item_name' => self::USER_MANAGER_DIRECT_SALE],
            ]);
        if (!in_array($roleName, [
            self::USER_DEVELOP,
            self::USER_ADMINISTRATOR,
            self::USER_MANAGER_CHAY_ADS,
            self::USER_MANAGER_KE_TOAN,
        ])) {
            $query->andWhere(['permission_coso' => Yii::$app->user->identity->permission_coso]);
        }
        /*if (Yii::$app->user->identity->permission_coso == 3) {
            $query_used = self::find()
                              ->select([self::tableName() . '.id'])
                              ->joinWith(['customerOnlineByDirectSale'])
                              ->join('LEFT JOIN', 'rbac_auth_assignment', 'rbac_auth_assignment.user_id = user.id')
                              ->where(['in', 'user.status', [self::STATUS_ACTIVE]])
                              ->andWhere([
                                  'or',
                                  ['rbac_auth_assignment.item_name' => self::USER_DIRECT_SALE],
                                  ['rbac_auth_assignment.item_name' => self::USER_MANAGER_DIRECT_SALE]
                              ])
                              ->andWhere(Dep365CustomerOnline::tableName() . '.customer_come_time_to IS null')
                              ->andWhere(self::tableName() . '.id IS NOT null')
                              ->andWhere([
                                  Dep365CustomerOnline::tableName() . '.status'  => Dep365CustomerOnline::STATUS_DH,
                                  Dep365CustomerOnline::tableName() . '.dat_hen' => Dep365CustomerOnline::DAT_HEN_DEN
                              ])
                              ->groupBy(self::tableName() . '.id')
                              ->indexBy('id');
            if ($current_directsale != null) {
                $query_used->andWhere(['<>', Dep365CustomerOnline::tableName() . '.directsale', $current_directsale]);
            }
            $directsale_used = $query_used->all();
            if (count($directsale_used) > 0) {
                $query->andWhere(['NOT IN', self::tableName() . '.id', array_keys($directsale_used)]);
            }
        }*/
//        echo $query->createCommand()->rawSql;

//        $cache = Yii::$app->cache;
//        $key = 'get-nhan-vien-tu-direct-sale-clinic-' . Yii::$app->user->identity->permission_coso;
//
//        $data = $cache->get($key);
//
//        if ($data == false) {
        $data = $query->active()->all();
//            $cache->set($key, $data);
//        }


        return $data;
    }

    /*
     * Lay nhan vien Direct sale array
     */
    public static function getNhanVienTuDirectSaleIsActiveArray()
    {
        $user = self::getNhanVienTuDirectSale();
        $result = [];
        foreach ($user as $item) {
            if ($item->userProfile != null) {
                if ($item->userProfile->phone == '0000000000') {
                    continue;
                }
                if ($item->userProfile->fullname == '') {
                    $result[$item->id] = '-';
                } else {
                    $result[$item->id] = $item->userProfile->fullname;
                }
            }
        }

        return $result;
    }

    /*
     * Lấy toàn bộ nhân viên chạy ky thuat labo
     */
    public static function getNhanVienKyThuatLabo()
    {
        $data = self::find()->select('user.id, user.status, user_profile.fullname')->joinWith(['userProfile'])
            ->where(['in', 'user.status', [User::STATUS_ACTIVE]])
            ->join('LEFT JOIN', 'rbac_auth_assignment', 'rbac_auth_assignment.user_id = id')
            ->andWhere(['rbac_auth_assignment.item_name' => self::USER_KY_THUAT_LABO])
            ->all();

        return $data;
    }


    public static function getNhanVienOnline()
    {
        $cache = Yii::$app->cache;
        $key = 'resdis-get-nhanvien-tu-van-filter';

        $result = $cache->get($key);
        if ($result === false) {
            $userProfile = self::getNhanVienTuVanOnlineDate();
            $result = [];
            foreach ($userProfile as $item) {
                if ($item->userProfile->fullname == '') {
                    $result[$item->id] = '-';
                } else {
                    $result[$item->id] = $item->userProfile->fullname;
                }
            }
            $cache->set($key, $result);
        }

        return $result;
    }

    public static function getNhanVienTuVanOnlineDate($date = null)
    {
        if ($date == null) {
            $strdate = strtotime(date('01-m-Y'));
        } else {
            $strdate = strtotime($date);
        }
        $data = self::find()->select('user.id, user.status, user_profile.fullname')->joinWith(['userProfile'])
            ->where(['in', 'user.status', [User::STATUS_ACTIVE, User::STATUS_NOT_ACTIVE]])
            ->andWhere('user.logged_at > ' . $strdate)
            ->join('LEFT JOIN', 'rbac_auth_assignment', 'rbac_auth_assignment.user_id = id')
            ->andFilterWhere([
                'or',
                ['rbac_auth_assignment.item_name' => self::USER_NHANVIEN_ONLINE],
                ['rbac_auth_assignment.item_name' => self::USER_MANAGER_ONLINE]
            ])
            ->all();

//        var_dump($data->createCommand()->getSql());die;
        return $data;
    }

    public static function getNhanVienTuVanOnline($user_status = [User::STATUS_ACTIVE, User::STATUS_NOT_ACTIVE])
    {
        $data = self::find()->select('user.id, user.status, user_profile.fullname')->joinWith(['userProfile'])
            ->where(['in', 'user.status', $user_status])
            ->join('LEFT JOIN', 'rbac_auth_assignment', 'rbac_auth_assignment.user_id = id')
            ->andFilterWhere([
                'or',
                ['rbac_auth_assignment.item_name' => self::USER_NHANVIEN_ONLINE],
                ['rbac_auth_assignment.item_name' => self::USER_MANAGER_ONLINE]
            ]);
        $data = $data->all();

//        var_dump($data->createCommand()->getSql());die;
        return $data;
    }


    public static function getNhanVienOnlineNLeTan($date = null)
    {
        if ($date == null) {
            $strdate = strtotime(date('01-m-Y'));
        } else {
            $strdate = strtotime($date);
        }
        $data = self::find()->select('user.id, user.status, user_profile.fullname')->joinWith(['userProfile'])
            ->where(['IN', 'user.status', [User::STATUS_ACTIVE, User::STATUS_NOT_ACTIVE]])
            ->andWhere('user.logged_at > ' . $strdate)
            ->join('LEFT JOIN', 'rbac_auth_assignment', 'rbac_auth_assignment.user_id = id')
            ->andFilterWhere([
                'or',
                ['rbac_auth_assignment.item_name' => self::USER_NHANVIEN_ONLINE],
                ['rbac_auth_assignment.item_name' => self::USER_MANAGER_ONLINE],
                ['rbac_auth_assignment.item_name' => self::USER_LE_TAN],
                ['rbac_auth_assignment.item_name' => self::USER_MANAGER_LE_TAN]
            ])
            ->all();

        return $data;
    }

    public static function getNhanVienIsActive()
    {
        $data = self::find()->select('user.id, user.status, user_profile.fullname')->joinWith(['userProfile'])
            ->where(['in', 'user.status', [User::STATUS_ACTIVE]])
            ->join('LEFT JOIN', 'rbac_auth_assignment', 'rbac_auth_assignment.user_id = id')
            ->andFilterWhere([
                'or',
                ['rbac_auth_assignment.item_name' => self::USER_NHANVIEN_ONLINE],
                ['rbac_auth_assignment.item_name' => self::USER_MANAGER_ONLINE]
            ])
            ->all();

        return $data;
    }

    public static function getNhanVienIsActiveArray()
    {
        $cache = Yii::$app->cache;
        $key = 'resdis-get-nhanvien-tu-van-active';

        $result = $cache->get($key);
        if ($result === false) {
            $user = self::getNhanVienIsActive();
            $result = [];
            foreach ($user as $item) {
                if ($item->userProfile != null) {
                    if ($item->userProfile->fullname == '') {
                        $result[$item->id] = '-';
                    } else {
                        $result[$item->id] = $item->userProfile->fullname;
                    }
                }
            }
            $cache->set($key, $result);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::find()
            ->active()
            ->andWhere(['access_token' => $token])
            ->one();
    }

    /**
     * Finds user by username
     *
     * @param string $username
     *
     * @return User|array|null
     */
    public static function findByUsername($username)
    {
        return static::find()
            ->active()
            ->andWhere(['username' => $username])
            ->one();
    }

    /**
     * Finds user by username or email
     *
     * @param string $login
     *
     * @return User|array|null
     */
    public static function findByLogin($login)
    {
        return static::find()
            ->active()
            ->andWhere(['or', ['username' => $login], ['email' => $login]])
            ->one();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            'auth_key' => [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'auth_key'
                ],
                'value' => Yii::$app->getSecurity()->generateRandomString()
            ],
            'access_token' => [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'access_token'
                ],
                'value' => Yii::$app->getSecurity()->generateRandomString(40)
            ],
        ];
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        return ArrayHelper::merge(
            parent::scenarios(),
            [
                'oauth_create' => [
                    'oauth_client',
                    'oauth_client_user_id',
                    'email',
                    'username',
                    '!status'
                ]
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => '\yii\helpers\Html::encode'],
            ['username', 'required'],
            [
                'username',
                'unique',
                'targetClass' => '\common\models\User',
                'message' => Yii::t('frontend', 'This username has already been taken.'),
                'filter' => function ($query) {
                    $query->andWhere(['not', ['id' => $this->getId()]]);
                },
            ],


            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            [
                'email',
                'unique',
                'targetClass' => '\common\models\User',
                'message' => Yii::t('frontend', 'This email address has already been taken.'),
                'filter' => function ($query) {
                    $query->andWhere(['not', ['id' => $this->getId()]]);
                },
            ],

            [
                'permission_coso',
                'required',
                'when' => function ($model) {
                    return $model->role_name == User::USER_LE_TAN;
                },
                'whenClient' => "function (attribute, value) {
                return $('#user-role-name').val() == 'user_le_tan';
            }"
            ],

            ['status', 'default', 'value' => self::STATUS_NOT_ACTIVE],
            ['status', 'in', 'range' => array_keys(self::statuses())],
            [['team'], 'integer'],
            [
                ['avatar'],
                'file',
                'extensions' => ['png', 'jpeg', 'jpg'],
                'maxSize' => 2 * 1024 * 1024,
                'wrongExtension' => 'Chỉ chấp nhận định dạng {extensions}'
            ],
            [
                ['cover'],
                'file',
                'extensions' => ['png', 'jpeg', 'jpg'],
                'maxSize' => 5 * 1024 * 1024,
                'wrongExtension' => 'Chỉ chấp nhận định dạng {extensions}'
            ],
        ];
    }

    /**
     * Returns user statuses list
     * @return array|mixed
     */
    public static function statuses()
    {
        return [
            self::STATUS_NOT_ACTIVE => Yii::t('common', 'Tạm ngưng'),
            self::STATUS_ACTIVE => Yii::t('common', 'Hoạt động'),
            self::STATUS_DELETED => Yii::t('common', 'Đã xóa')
        ];
    }

    /*
     * Trả về list Role theo người login
     */
    public static function roleName()
    {
        $auth = Yii::$app->authManager;

        $result = array_keys($auth->getAssignments(Yii::$app->user->id));
        $roleUser = $result[0] != null ? $result[0] : User::USER_USERS;

        $result = $auth->getChildRoles($roleUser);
        $listRole = array();
        foreach ($result as $key => $item) {
            $listRole[$item->name] = $item->description;
        }

        return $listRole != null ? $listRole : User::USER_USERS;
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfile()
    {
        return $this->hasOne(UserProfile::class, ['user_id' => 'id']);
    }


    /**
     * HasOne subrole
     */
    public function getSubroleHasOne()
    {
        $res = $this->hasOne(UserSubRole::class, ['user_id' => 'id']);

        return $res;
    }

    public function getLichDieuTriHasManyByBacSi()
    {
        return $this->hasMany(PhongKhamLichDieuTri::class, ['ekip' => 'id']);
    }

    public function getLichDieuTriHasManyByRoom()
    {
        return $this->hasMany(PhongKhamLichDieuTri::class, ['room_id' => 'id']);
    }

    public function getCustomerOnlineByDirectSale()
    {
        return $this->hasMany(Dep365CustomerOnline::class, ['directsale' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     *
     * @return boolean if password provided is valid for current user
     * Kiểm tra password cũ nhập vào có giống password cũ không
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password_hash);
    }


    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->getSecurity()->generatePasswordHash($password);
    }


    public function afterSignup(array $profileData = [])
    {
        $this->refresh();

        $profile = new UserProfile();
        $profile->locale = Yii::$app->language;
        $profile->load($profileData, '');
        $this->link('userProfile', $profile);
        $this->trigger(self::EVENT_AFTER_SIGNUP);
        // Default role
        $auth = Yii::$app->authManager;
        $auth->assign($auth->getRole(User::USER_USERS), $this->getId());
    }

    public function afterDelete()
    {
        $this->deleteCache('delete');
        parent::afterDelete(); // TODO: Change the autogenerated stub
    }

    public function afterSave($insert, $changedAttributes)
    {

        $this->deleteCache('save');
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    private function deleteCache($action = 'save')
    {
        $cache = Yii::$app->cache;
        $coso = Dep365CoSo::getCoSo();

        $cache->delete('get-list-user-id-profile');

        switch ($action) {
            case 'save':
                foreach ($coso as $value) {
                    $key = 'get-nhan-vien-tu-direct-sale-clinic-' . $value->id;
                    $cache->delete($key);
                }
                $id = $this->primaryKey;
                $key1 = 'get-avatar-user-profile-' . $id;
                $cache->delete($key1);

                $key2 = 'get-user-one-in-common-' . $id;
                $cache->delete($key2);

                $key3 = 'find-identity-user-' . $id;
                $cache->delete($key3);
                break;
            case 'delete':

                foreach ($coso as $value) {
                    $key = 'get-nhan-vien-tu-direct-sale-clinic-' . $value->id;
                    $cache->delete($key);
                }

                $id = $this->primaryKey;
                $key1 = 'get-avatar-user-profile-' . $id;
                $cache->delete($key1);

                $key2 = 'get-user-one-in-common-' . $id;
                $cache->delete($key2);

                $key3 = 'find-identity-user-' . $id;
                $cache->delete($key3);
                break;
        }

    }

    public static function getUserOne($id)
    {
        $cache = Yii::$app->cache;
        $key = 'get-user-one-in-common-' . $id;
        $data = $cache->get($key);
        if ($data == false) {
            $data = static::find()->joinWith(['subroleHasOne'])->where([static::tableName() . '.id' => $id])->one();
            $cache->set($key, $data, 30 * 86400);
        }

        return $data;
    }


    /**
     * @return string
     */
    public function getPublicIdentity()
    {
        if ($this->userProfile && $this->userProfile->getFullname()) {
            return $this->userProfile->getFullname();
        }
        if ($this->username) {
            return $this->username;
        }

        return $this->email;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuths()
    {
        return $this->hasMany(Auth::class, ['user_id' => 'id']);
    }

    public static function getListNameUserByListId($listId = [])
    {
        if (empty($listId)) {
            return [];
        }
        $query = self::find()->select('user.id, user_profile.fullname')->joinWith(['userProfile'])
            ->where(['in', 'user.id', $listId]);
        $data = $query->all();
        $result = [];
        foreach ($data as $item) {
            $result[$item->id] = $item->fullname;
        }

        return $result;
    }

    public static function getById($id)
    {
        return self::find()->where(['id' => $id])->one();
    }

    public static function getAllUsers($exclude = [])
    {
        if (is_string($exclude)) $exclude = [$exclude];
        if (!is_array($exclude)) $exclude = [];
        $query = self::find()
            ->select([self::tableName() . '.id', UserProfile::tableName() . '.fullname'])
            ->joinWith(['rbacItemHasMany', 'userProfile'])
            ->where([self::tableName() . '.status' => self::STATUS_ACTIVE])
            ->andWhere(['<>', UserProfile::tableName() . '.fullname', ''])
            ->andWhere(UserProfile::tableName() . '.fullname IS NOT NULL');
        if (count($exclude) > 0) $query->andWhere(['NOT IN', 'rbac_auth_assignment.item_name', $exclude]);
        return $query->all();
    }

    public static function getUsersByRoles($roles)
    {
        if (is_string($roles)) $roles = [$roles];
        if (!is_array($roles)) $roles = [];
        $query = self::find()
            ->select([self::tableName() . '.id', UserProfile::tableName() . '.fullname'])
            ->joinWith(['userProfile', 'rbacItemHasMany'])
            ->where(['IN', 'rbac_auth_assignment.item_name', $roles])
            ->andWhere([self::tableName() . '.status' => self::STATUS_ACTIVE])
            ->andWhere(['<>', UserProfile::tableName() . '.fullname', ''])
            ->andWhere(UserProfile::tableName() . '.fullname IS NOT NULL');
        return $query->all();
    }

    public function getRbacItemHasMany()
    {
        return $this->hasMany(RbacAuthItem_::class, ['name' => 'item_name'])
            ->viaTable('rbac_auth_assignment', ['user_id' => 'id']);
    }
}
