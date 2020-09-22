<?php

namespace backend\modules\testab;

/**
 * testab module definition class
 */
class Testab extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\testab\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . '/config/testab.php'));
        \Yii::$app->setComponents([

        ]);
    }
}
