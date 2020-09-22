<?php


namespace backend\modules\api\modules\v1\controllers;

use backend\modules\api\components\RestController;

class V1Controller extends RestController
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
