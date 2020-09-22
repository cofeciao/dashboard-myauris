<?php
/**
 * Created by PhpStorm.
 * User: mongd
 * Date: 29-Apr-18
 * Time: 4:54 PM
 */

namespace frontend\components;

use yii\captcha\CaptchaAction;

class MathCaptchaAction extends CaptchaAction
{
    public $minLength = 0;
    public $maxLength = 100;

    /**
     * @inheritdoc
     */
    protected function generateVerifyCode()
    {
        return mt_rand((int)$this->minLength, (int)$this->maxLength);
    }

    /**
     * @inheritdoc
     */
    protected function renderImage($code)
    {
        return parent::renderImage($this->getText($code));
    }

    protected function getText($code)
    {
        $code = (int)$code;
        $rand = mt_rand(min(1, $code - 1), max(1, $code - 1));
        $operation = mt_rand(0, 1);
        if ($operation === 1) {
            return $code - $rand . '+' . $rand;
        } else {
            return $code + $rand . '-' . $rand;
        }
    }

}