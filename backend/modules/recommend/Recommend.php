<?php

namespace backend\modules\recommend;

class Recommend extends \yii\base\Module
{
    /**
     * @var string
     */
    public $controllerNamespace = 'backend\modules\recommend\controllers';
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
        \Yii::configure($this, require(__DIR__ . '/config/recommend.php'));
        \Yii::$app->setComponents([

        ]);
    }
}
