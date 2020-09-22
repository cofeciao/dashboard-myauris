<?php


namespace backend\modules\api\modules\v1\controllers;

use backend\modules\api\components\RestController;

class SiteController extends RestController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['bootstrap']['only'] = ['error'];
        return $behaviors;
    }
    public function actionError()
    {
        return ['status' => 404, 'message' => 'Not found.'];
    }
}
