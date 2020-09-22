<?php

namespace backend\models\form;

use backend\modules\user\models\User;
use Yii;

class UserSettingsForm extends \yii\base\Model
{
    public $auth_message;
    public $auth_mail;

    public function rules()
    {
        return [
            [['auth_message', 'auth_mail'], 'integer'],
            [['auth_message'], 'checkAuthMessage'],
            [['auth_mail'], 'checkAuthMail'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'auth_message' => Yii::t('backend', 'Xác thực qua tin nhắn'),
            'auth_mail' => Yii::t('backend', 'Xác thực qua email'),
        ];
    }

    public function checkAuthMessage($attribute)
    {
        if ($this->auth_message == 1) {
            $user = User::find()->where(['id' => Yii::$app->user->getId()])->joinWith(['userProfile'])->one();
            if ($user->userProfile->phone == null || $user->userProfile->phone == '') {
                $this->addError($attribute, 'Bạn phải cập nhật số điện thoại trước!');
            }
        }
    }
    public function checkAuthMail($attribute)
    {
        if ($this->auth_mail == 1) {
            $user = User::find()->where(['id' => Yii::$app->user->getId()])->one();
            if ($user->email == null || $user->email == '') {
                $this->addError($attribute, 'Bạn phải cập nhật email trước!');
            }
        }
    }
}
