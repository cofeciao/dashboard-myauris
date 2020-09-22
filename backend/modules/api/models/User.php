<?php

namespace backend\modules\api\modules\models;

use common\models\query\UserQuery;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_NOT_ACTIVE = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_DELETED = 3;

    const SCENARIO_CREATE = 'create';

    const EVENT_AFTER_SIGNUP = 'afterSignup';
    const EVENT_AFTER_LOGIN = 'afterLogin';

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

    const USER_STUDIO = 'user_studio'; //manager
    const USER_CHUP_HINH = 'user_chup_hinh';
    const USER_TK_NU_CUOI = 'user_tk_nu_cuoi';

    const USER_MANAGER_DIRECT_SALE = 'user_manager_direct_sale';
    const USER_DIRECT_SALE = 'user_direct_sale';

    const USER_MANAGER_CHAY_ADS = 'user_manager_chay_ads';
    const USER_CHAY_ADS = 'user_chay_ads';

    const USER_MANAGER_BAC_SI = 'user_manager_bac_si';
    const USER_BAC_SI = 'user_bac_si';

    const USER_KE_TOAN = 'user_ke_toan';
    const USER_MANAGER_KE_TOAN = 'user_manager_ke_toan';

    const USER_MYAURIS = 'user_myauris';

    const USER_BIEN_TAP = 'user_bien_tap';
    const USER_MANAGER_BIEN_TAP = 'user_manager_bien_tap';

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

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::find()
            ->active()
            ->andWhere(['id' => $id])
            ->one();
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
        $user = User::find()->where(['id' => $id])->one();
        if (isset($user)) {
            return $user;
        }
    }

    /*
     * Lấy toàn bộ nhân viên là bác sĩ
     */
    public static function getNhanVienBacSi()
    {
        $query = self::find()->select('user.id, user.status, user_profile.fullname')->joinWith(['userProfile'])
            ->where(['in', 'user.status', [User::STATUS_ACTIVE]])
            ->join('LEFT JOIN', 'rbac_auth_assignment', 'rbac_auth_assignment.user_id = id')
            ->andFilterWhere(['or', ['rbac_auth_assignment.item_name' => self::USER_BAC_SI], ['rbac_auth_assignment.item_name' => self::USER_MANAGER_BAC_SI]]);
        if (Yii::$app->user->can(self::USER_LE_TAN)) {
            if (Yii::$app->user->identity->permission_coso == 3) {
                $query->andWhere(['permission_coso' => 3]);
            } else {
                $query->andWhere(['IN', 'permission_coso', [1, 2]]);
            }
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
            ->andFilterWhere(['or', ['rbac_auth_assignment.item_name' => self::USER_CHAY_ADS], ['rbac_auth_assignment.item_name' => self::USER_MANAGER_CHAY_ADS]])
            ->all();
        return $data;
    }

    /*
     * Lấy toàn bộ nhân viên Direct sale đang hoạt động
     */
    public static function getNhanVienTuDirectSale()
    {
        $assignment = array_keys(Yii::$app->authManager->getAssignments(Yii::$app->user->id));
        $assignment = $assignment[0];

        $query = self::find()->select('user.id, user.status, user_profile.fullname')->joinWith(['userProfile'])
            ->where(['in', 'user.status', [User::STATUS_ACTIVE]])
            ->join('LEFT JOIN', 'rbac_auth_assignment', 'rbac_auth_assignment.user_id = id')
            ->andFilterWhere(['or', ['rbac_auth_assignment.item_name' => self::USER_DIRECT_SALE], ['rbac_auth_assignment.item_name' => self::USER_MANAGER_DIRECT_SALE]]);
        if ($assignment == self::USER_LE_TAN) {
            $query->andWhere(['permission_coso' => Yii::$app->user->identity->permission_coso]);
        }
        $data = $query->active()->all();
        return $data;
    }

    public static function getNhanVienOnline()
    {
        $cache = Yii::$app->cache;
        $key = 'resdis-get-nhanvien-tu-van-filter';

        $result = $cache->get($key);
        if ($result === false) {
            $userProfile = self::getNhanVienTuVanOnline();
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

    public static function getNhanVienTuVanOnline($date = null)
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
            ->andFilterWhere(['or', ['rbac_auth_assignment.item_name' => self::USER_NHANVIEN_ONLINE], ['rbac_auth_assignment.item_name' => self::USER_MANAGER_ONLINE]])
            ->all();
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
            ->andFilterWhere(['or',
                ['rbac_auth_assignment.item_name' => self::USER_NHANVIEN_ONLINE],
                ['rbac_auth_assignment.item_name' => self::USER_MANAGER_ONLINE],
                ['rbac_auth_assignment.item_name' => self::USER_LE_TAN],
                ['rbac_auth_assignment.item_name' => self::USER_MANAGER_LE_TAN]])
            ->all();
        return $data;
    }

    public static function getNhanVienIsActive()
    {
        $data = self::find()->select('user.id, user.status, user_profile.fullname')->joinWith(['userProfile'])
            ->where(['in', 'user.status', [User::STATUS_ACTIVE]])
            ->join('LEFT JOIN', 'rbac_auth_assignment', 'rbac_auth_assignment.user_id = id')
            ->andFilterWhere(['or', ['rbac_auth_assignment.item_name' => self::USER_NHANVIEN_ONLINE], ['rbac_auth_assignment.item_name' => self::USER_MANAGER_ONLINE]])
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
                'value' => function () {
                    return Yii::$app->getSecurity()->generateRandomString(40);
                }
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
                    'oauth_client', 'oauth_client_user_id', 'email', 'username', '!status'
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
            ['username', 'unique',
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
            ['email', 'unique',
                'targetClass' => '\common\models\User',
                'message' => Yii::t('frontend', 'This email address has already been taken.'),
                'filter' => function ($query) {
                    $query->andWhere(['not', ['id' => $this->getId()]]);
                },
            ],

            ['permission_coso', 'required', 'when' => function ($model) {
                return $model->role_name == User::USER_LE_TAN;
            }, 'whenClient' => "function (attribute, value) {
                return $('#user-role-name').val() == 'user_le_tan';
            }"],

            ['status', 'default', 'value' => self::STATUS_NOT_ACTIVE],
            ['status', 'in', 'range' => array_keys(self::statuses())],
            [['team'], 'integer'],
            [['avatar'], 'file', 'extensions' => ['png', 'jpeg', 'jpg'], 'maxSize' => 2 * 1024 * 1024, 'wrongExtension' => 'Chỉ chấp nhận định dạng {extensions}'],
            [['cover'], 'file', 'extensions' => ['png', 'jpeg', 'jpg'], 'maxSize' => 5 * 1024 * 1024, 'wrongExtension' => 'Chỉ chấp nhận định dạng {extensions}'],
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
}
