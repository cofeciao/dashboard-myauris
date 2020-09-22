<?php

namespace backend\modules\test\controllers;

use backend\components\MyController;

class StatController extends MyController
{
    public function actionIndex()
    {
        return $this->render('view');
    }
}
