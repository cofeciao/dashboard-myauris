<?php

namespace backend\modules\customer;

/**
 * customer module definition class
 */
class Customer extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\customer\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . '/config/customer.php'));
        \Yii::$app->setComponents([

        ]);
    }
}
