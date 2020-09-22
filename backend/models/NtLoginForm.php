<?php

namespace backend\models;

use cheatsheet\Time;
use common\models\User;
use himiklab\yii2\recaptcha\ReCaptchaValidator2;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class NtLoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $user = false;

    public $reCaptcha;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username'], 'required', 'message' => 'Email không được để trống'],
            [['password'], 'required', 'message' => 'Mật khẩu không được để trống'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            [['reCaptcha'], ReCaptchaValidator2::class,
                'secret' => RECAPTCHA_GOOGLE_SECRETKEY,
                'uncheckedMessage' => \Yii::t('backend', 'Vui lòng đánh dấu vào ô trên')]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('backend', 'Tài Khoản'),
            'password' => Yii::t('backend', 'Mật Khẩu'),
            'rememberMe' => Yii::t('backend', 'Giữ Tôi Đăng Nhập'),
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validatePassword()
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError('password', Yii::t('backend', 'Tài khoản hoặc mật khẩu sai.'));
            }
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->user === false) {
            $this->user = User::find()
                ->andWhere(['or', ['username' => $this->username], ['email' => $this->username]])
                ->one();
        }

        return $this->user;
    }

    public function login()
    {
        if (!$this->validate()) {
            return false;
        }
        $duration = $this->rememberMe ? Time::SECONDS_IN_A_MONTH : 0;
        if (Yii::$app->user->login($this->getUser(), $duration)) {
            if (!Yii::$app->user->can('loginToBackend')) {
                Yii::$app->user->logout();
                return false;
            }
            return true;
        }

        return false;
    }
}
