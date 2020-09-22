<?php

namespace backend\modules\api\modules\v3;

use yii\web\ErrorHandler;

/**
 * v3 module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\api\modules\v3\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        \Yii::$app->user->enableSession = false;

        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . '/config/v3.php'));
        \Yii::$app->setComponents([
        ]);
    }
}
