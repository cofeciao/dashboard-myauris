<?php


namespace backend\modules\api\modules\v3\controllers;

use backend\modules\api\modules\v3\components\RestfullController;

class V3Controller extends RestfullController
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
