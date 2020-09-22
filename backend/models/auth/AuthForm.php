<?php

namespace backend\models\auth;

use cheatsheet\Time;
use common\models\User;
use himiklab\yii2\recaptcha\ReCaptchaValidator2;
use Yii;
use yii\base\Model;

class AuthForm extends Model
{
    public $username;
    public $auth;
    public $reCaptcha;

    public function rules()
    {
        return [
            [['username', 'auth'], 'required'],
            [['username', 'auth'], 'string'],
            [['auth'], 'checkAuth'],
        ];
    }

    public function checkAuth()
    {
        $user = User::find()->where(['username' => $this->username, 'status' => User::STATUS_ACTIVE])->one();
        if ($this->username == null || $this->auth == null || $user == null) {
            $this->addError('auth', \Yii::t('backend', 'Lỗi xác thực!'));
        } else {
            $userAuth = UserAuth::find()->where(['used' => UserAuth::NOT_USED, 'pin' => $this->auth])->andWhere(['>', 'expired_at', time()])->one();
            if ($userAuth == null) {
                $this->addError('auth', \Yii::t('backend', 'Mã xác thực chưa có hoặc đã hết hạn!'));
            }
        }
    }

    public function attributeLabels()
    {
        return [
            'username' => \Yii::t('backend', 'Tài khoản'),
            'auth' => \Yii::t('backend', 'Mã xác thực'),
        ];
    }

    public function getUser()
    {
        return User::find()
            ->andWhere(['or', ['username' => $this->username], ['email' => $this->username]])
            ->one();
    }

    public function login()
    {
        if (!$this->validate()) {
            return false;
        }
        if (Yii::$app->user->login($this->getUser(), Time::SECONDS_IN_A_MONTH)) {
            if (!Yii::$app->user->can('loginToBackend')) {
                Yii::$app->user->logout();
                return false;
            }
            return true;
        }

        return false;
    }
}
