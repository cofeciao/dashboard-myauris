<?php

namespace backend\modules\quytac;

/**
 * quytac module definition class
 */
class Quytac extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\quytac\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . '/config/quytac.php'));
        \Yii::$app->setComponents([

        ]);
    }
}
