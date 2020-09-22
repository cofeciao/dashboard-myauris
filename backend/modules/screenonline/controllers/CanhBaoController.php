<?php


namespace backend\modules\screenonline\controllers;

use backend\components\MyController;
use backend\components\WarningComponent;
use yii\web\Response;
use yii\web\View;

class CanhBaoController extends MyController
{
    public function actionIndex()
    {
        $warning = new WarningComponent();
        $warning->warningCreate();

        $data = $warning->getWarning();

        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionGetWarning($page = null)
    {
        if (\Yii::$app->request->isAjax) {
            $warning = new WarningComponent();
            $data = $warning->getWarning($page);
            return $this->renderAjax('warning', ['data' => $data]);
        }
    }
}
