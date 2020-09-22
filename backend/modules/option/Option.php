<?php

namespace backend\modules\option;

/**
 * option module definition class
 */
class Option extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\option\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . '/config/option.php'));
        \Yii::$app->setComponents([

        ]);
    }
}
