<?php

namespace backend\modules\helper;

use yii\base\ActionEvent;

/**
 * helper module definition class
 */
class Helper extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\helper\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . '/config/helper.php'));
        \Yii::$app->setComponents([

        ]);
    }
}
