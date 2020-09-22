<?php

namespace backend\modules\support;

/**
 * support module definition class
 */
class Support extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\support\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . '/config/support.php'));
        \Yii::$app->setComponents([

        ]);
    }
}
