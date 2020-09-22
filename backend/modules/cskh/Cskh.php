<?php

namespace backend\modules\cskh;

class Cskh extends \yii\base\Module
{
    /**
     * @var string
     */
    public $controllerNamespace = 'backend\modules\cskh\controllers';
//    public $defaultController='home';

    /**
     * @var bool Is users should be activated by email
     */
    public $shouldBeActivated = false;
    /**
     * @var bool Enables login by pass from backend
     */
    public $enableLoginByPass = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        \Yii::configure($this, require(__DIR__ . '/config/cskh.php'));
        \Yii::$app->setComponents([

        ]);
    }
}
