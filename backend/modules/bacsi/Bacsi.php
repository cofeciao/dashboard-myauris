<?php

namespace backend\modules\bacsi;

/**
 * bacsi module definition class
 */
class Bacsi extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\bacsi\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . '/config/option.php'));
    }
}
