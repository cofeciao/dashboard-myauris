<?php

namespace backend\modules\helper\controllers;

use backend\components\MyController;
use backend\models\CustomerElastic;
use backend\models\CustomerModel;
use backend\modules\location\models\District;
use backend\modules\location\models\Province;
use Yii;
use yii\elasticsearch\Connection;
use yii\elasticsearch\Exception;
use yii\helpers\ArrayHelper;

class DemoController extends MyController
{
    public function actionIndex()
    {
        return $this->render('index', [

        ]);
    }

}
