<?php

namespace backend\modules\api\modules\v3\controllers;

use Yii;
use common\helpers\MyHelper;
use backend\modules\api\components\RestAccessTokenController;
use backend\modules\api\modules\v3\components\RestfullController;
use yii\helpers\Url;

class AppController extends RestfullController
{
    public $modelClass = 'backend\modules\api\modules\v1\models\UserApi';
    //
    public function actionTest()
    {
        return ["ok"];
    }
}
