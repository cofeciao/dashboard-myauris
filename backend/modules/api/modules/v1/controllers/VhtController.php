<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 25-Dec-18
 * Time: 4:35 PM
 */

namespace backend\modules\api\modules\v1\controllers;

use backend\modules\api\components\RestController;
use backend\modules\api\modules\v1\models\VhtApi;

class VhtController extends RestController
{
    public $modelClass = 'backend\modules\api\modules\v1\models\VhtApi';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['bootstrap']['only'] = ['view', 'index'];
        return $behaviors;
    }

    public function actionIndex()
    {
        $vht = VhtApi::find()->one();

        return $vht;
    }
}
