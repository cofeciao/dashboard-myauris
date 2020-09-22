<?php

namespace backend\modules\api;

use yii\web\ErrorHandler;

/**
 * api module definition class
 */
class Api extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\api\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        \Yii::$app->user->enableSession = false;

        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . '/config/api.php'));
        \Yii::$app->setComponents([
        ]);
    }
}
