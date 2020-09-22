<?php

namespace backend\modules\directsale;

/**
 * directsale module definition class
 */
class DirectSale extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\directsale\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . '/config/directsale.php'));
        \Yii::$app->setComponents([

        ]);
    }
}
