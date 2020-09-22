<?php

namespace backend\modules\events;

/**
 * events module definition class
 */
class Event extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\events\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . '/config/event.php'));
        \Yii::$app->setComponents([

        ]);
    }
}
