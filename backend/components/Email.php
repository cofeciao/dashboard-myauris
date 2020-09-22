<?php
/**
 * Created by PhpStorm.
 * User: Kem Bi
 * Date: 01-Aug-18
 * Time: 1:03 PM
 */

namespace backend\components;

use cheatsheet\Time;
use common\commands\SendEmailCommand;
use common\models\User;
use common\models\UserToken;

class Email
{
    /**
     *
     * Gửi mail lấy lại mật khẩu
     */
    public static function sendEmail($mail)
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $mail,
        ]);
        if ($user) {
            $token = UserToken::create($user->id, UserToken::TYPE_PASSWORD_RESET, Time::SECONDS_IN_A_DAY);
            if ($user->save()) {
                return \Yii::$app->commandBus->handle(new SendEmailCommand([
                    'to' => $mail,
                    'subject' => \Yii::t('frontend', 'Yêu cầu lấy lại mật khẩu từ {name}', ['name' => \Yii::$app->name]),
                    'view' => 'passwordResetToken',
                    'params' => [
                        'user' => $user,
                        'token' => $token->token,
                    ]
                ]));
            }
            return false;
        }
        return false;
    }
}
