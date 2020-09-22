<?php

namespace backend\modules\appmyauris;

/**
 * filemanager module definition class
 */
class AppMyauris extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\appmyauris\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        \Yii::configure($this, require(__DIR__ . '/config/app-config.php'));
        \Yii::$app->setComponents([

        ]);
    }
}
