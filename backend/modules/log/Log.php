<?php

namespace backend\modules\log;

/**
 * log module definition class
 */
class Log extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\log\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . '/config/log.php'));
        \Yii::$app->setComponents([

        ]);
    }
}
