<?php

namespace backend\modules\filemanager;

/**
 * filemanager module definition class
 */
class Filemanager extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\filemanager\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        \Yii::configure($this, require(__DIR__ . '/config/file.php'));
        \Yii::$app->setComponents([

        ]);
    }
}
