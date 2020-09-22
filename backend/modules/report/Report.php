<?php

namespace backend\modules\report;

/**
 * report module definition class
 */
class Report extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\report\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . '/config/report.php'));
        \Yii::$app->setComponents([

        ]);
    }
}
