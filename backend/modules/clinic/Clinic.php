<?php

namespace backend\modules\clinic;

/**
 * clinic module definition class
 */
class Clinic extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\clinic\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . '/config/clinic.php'));
        \Yii::$app->setComponents([

        ]);
    }
}
