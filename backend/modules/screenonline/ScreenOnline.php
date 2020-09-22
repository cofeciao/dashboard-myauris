<?php

namespace backend\modules\screenonline;

/**
 * screenonline module definition class
 */
class ScreenOnline extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\screenonline\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . '/config/screen.php'));
        \Yii::$app->setComponents([

        ]);
    }
}
