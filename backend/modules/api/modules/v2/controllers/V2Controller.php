<?php


namespace backend\modules\api\modules\v2\controllers;

use backend\modules\api\modules\v2\components\RestfullController;

class V2Controller extends RestfullController
{
    public $modelClass = '';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['bootstrap']['only'] = ['index'];
        return $behaviors;
    }

    public function actionIndex()
    {
        return ['status' => 403, 'message' => 'Trang không tồn tại.'];
    }
}
