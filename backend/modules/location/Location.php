<?php

namespace backend\modules\location;

/**
 * location module definition class
 */
class Location extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\location\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        \Yii::configure($this, require(__DIR__ . '/config/location.php'));
        \Yii::$app->setComponents([

        ]);
    }
}
