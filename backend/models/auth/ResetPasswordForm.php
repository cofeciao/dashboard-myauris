<?php

namespace backend\models\auth;

use common\models\UserToken;
use himiklab\yii2\recaptcha\ReCaptchaValidator2;
use Yii;
use yii\base\InvalidArgumentException ;
use yii\base\Model;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    /**
     * @var
     */
    public $password;
    public $confirm_password;
    public $reCaptcha;

    /**
     * @var \common\models\UserToken
     */
    private $token;

    /**
     * Creates a form model given a token.
     *
     * @param  string $token
     * @param  array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidArgumentException('Password reset token cannot be blank.');
        }
        /** @var UserToken $tokenModel */
        $this->token = UserToken::find()
            ->notExpired()
            ->byType(UserToken::TYPE_PASSWORD_RESET)
            ->byToken($token)
            ->one();

        if (!$this->token) {
            throw new InvalidArgumentException('Wrong password reset token.');
        }
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password', 'confirm_password'], 'required'],
            [['password', 'confirm_password'], 'string', 'min' => 6],
            ['confirm_password', 'compare', 'compareAttribute' => 'password', 'skipOnEmpty' => false, 'message'=> Yii::t('backend', 'Passwords do not match')],
            [['reCaptcha'], ReCaptchaValidator2::class, 'secret' => RECAPTCHA_GOOGLE_SECRETKEY, 'uncheckedMessage' => \Yii::t('backend', 'I am not robot')]
        ];
    }

    public function resetPassword()
    {
        $user = $this->token->user;
        $user->password = $this->password;
        if ($user->save()) {
            $this->token->delete();
        };

        return true;
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'password' => Yii::t('backend', 'Password'),
            'confirm_password' => Yii::t('backend', 'Password Confirm')
        ];
    }
}
