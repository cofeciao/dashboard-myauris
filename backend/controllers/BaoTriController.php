<?php

namespace backend\controllers;

use backend\modules\setting\models\Setting;
use backend\modules\user\models\User;
use Yii;
use yii\web\Controller;

/**
 * Site controller
 */
class BaoTriController extends Controller
{
    public function actionIndex()
    {
        if (Yii::$app->user->id != null) {
            $system_maintenance = Setting::find()->where(['key_value' => 'system_maintenance'])->one();
            if ($system_maintenance != null && $system_maintenance->value == '0') {
                return $this->redirect(['/site']);
            }
        }
        $this->layout = false;
        return $this->render('index', []);
    }
}
