<?php

namespace backend\modules\baocao;

/**
 * baocao module definition class
 */
class Baocao extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\baocao\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . '/config/baocao.php'));
        \Yii::$app->setComponents([

        ]);
    }
}
