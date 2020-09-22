<?php

namespace backend\modules\general;

/**
 * general module definition class
 */
class General extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\general\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . '/config/general.php'));
        \Yii::$app->setComponents([

        ]);
    }
}
