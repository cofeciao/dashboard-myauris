<?php

namespace backend\modules\affiliate;

/**
 * affiliate module definition class
 */
class Affiliate extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\affiliate\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . '/config/affiliate.php'));
        \Yii::$app->setComponents([

        ]);
    }
}
