<?php

namespace backend\modules\quytac\controllers;

use yii\web\Controller;

/**
 * Default controller for the `quytac` module
 */
class NhanTinController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
