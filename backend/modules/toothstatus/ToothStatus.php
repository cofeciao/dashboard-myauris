<?php

namespace backend\modules\toothstatus;

/**
 * toothstatus module definition class
 */
class ToothStatus extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\toothstatus\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . '/config/toothstatus.php'));
        \Yii::$app->setComponents([

        ]);
    }
}
