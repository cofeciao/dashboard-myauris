<?php

namespace backend\modules\api\modules\v2;

use yii\web\ErrorHandler;

/**
 * v1 module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\api\modules\v2\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        \Yii::$app->user->enableSession = false;

        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . '/config/v2.php'));
        \Yii::$app->setComponents([
        ]);
    }
}
