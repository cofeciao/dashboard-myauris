<?php

namespace frontend\models;

use Yii;
use himiklab\yii2\recaptcha\ReCaptchaValidator;


class User extends \common\models\User
{

    public $manager;

    public $reCaptcha;

    public static function tableName()
    {
        return 'user';
    }

    public function __construct()
    {
        $this->manager = Yii::$app->authManager;

        parent::__construct();
    }

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
            [['reCaptcha', 'user_phone'], 'required'],
            [['user_phone'], 'string'],
            [['reCaptcha'], ReCaptchaValidator2::class, 'secret' => RECAPTCHA_GOOGLE_SECRETKEY, 'uncheckedMessage' => 'I am not robot']
        ];
    }

    public function attributeLabels()
    {
        return [
            'reCaptcha' => Yii::t('frontend', 'Mã bảo vệ'),
            'user_phone' => 'Số điện thoại',
        ];
    }

    /*
     * Đưa vào User ID
     * Trả về tên Role của User id đó
     */
    public function getRoleName($id)
    {
        $assignment = array_keys($this->manager->getAssignments($id));
        return $assignment != null ? $assignment[0] : User::USER_USERS;
    }

    /*
     * Kiểm tra parent - child
     * $role đưa vào cần kiểm tra
     * $roleUser role của người đang kiểm tra
     */
    public function checkParent(string $role, string $roleUser): bool
    {
        if ($roleUser == 'user_develop') {
            return true;
        }
        $result = $this->manager->getChildRoles($roleUser);
        foreach ($result as $roleName) {
            if ($role == $roleName->name) {
                return true;
            }
        }
        return false;
    }
}
