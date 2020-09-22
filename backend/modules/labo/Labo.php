<?php

namespace backend\modules\labo;

class Labo extends \yii\base\Module
{
    /**
     * @var string
     */
    public $controllerNamespace = 'backend\modules\labo\controllers';
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
        \Yii::configure($this, require(__DIR__ . '/config/labo-config.php'));
        \Yii::$app->setComponents([

        ]);
    }
}
