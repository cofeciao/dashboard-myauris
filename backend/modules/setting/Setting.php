<?php

namespace backend\modules\setting;

/**
 * setting module definition class
 */
class Setting extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\setting\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . '/config/coso.php'));
        \Yii::$app->setComponents([

        ]);
    }
}
