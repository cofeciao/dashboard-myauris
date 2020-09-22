<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tran
 * Date: 07-05-2019
 * Time: 03:13 PM
 */

namespace backend\models\auth;

use himiklab\yii2\recaptcha\ReCaptchaValidator2;
use yii\base\Model;

class AuthenticationLoginForm extends Model
{
    public $pin;
    public $reCaptcha;

    public function rules()
    {
        return [
            [['pin', 'reCaptcha'], 'required'],
            ['pin', 'string', 'max' => 6],
            [['reCaptcha'], ReCaptchaValidator2::class,
                'secret' => RECAPTCHA_GOOGLE_SECRETKEY,
                'uncheckedMessage' => \Yii::t('backend', 'Vui lòng đánh dấu vào ô trên')]
        ];
    }

    public function attributeLabels()
    {
        return [
            'pin' => 'Mã đăng nhập'
        ];
    }
}
