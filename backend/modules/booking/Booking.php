<?php

namespace backend\modules\booking;

/**
 * booking module definition class
 */
class Booking extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\booking\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . '/config/booking.php'));
        \Yii::$app->setComponents([

        ]);
    }
}
