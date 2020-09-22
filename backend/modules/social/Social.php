<?php

namespace backend\modules\social;

/**
 * social module definition class
 */
class Social extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\social\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . '/config/social.php'));
        \Yii::$app->setComponents([

        ]);
    }
}
