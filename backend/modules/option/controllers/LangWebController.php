<?php
/**
 * Created by PhpStorm.
 * User: Kem Bi
 * Date: 10-Oct-18
 * Time: 3:23 PM
 */

namespace backend\modules\option\controllers;

use backend\components\MyController;
use backend\modules\option\models\LangForm;
use yii\helpers\FileHelper;
use Yii;
use yii\web\NotFoundHttpException;

class LangWebController extends MyController
{
    public function actionIndex()
    {
        $fileAll = FileHelper::findFiles(\Yii::getAlias('@common/messages/vi'));

        return $this->render('index', [
            'file' => $fileAll,
        ]);
    }

    public function actionUpdate($file)
    {
        $model['file'] = $file;
        $file = \Yii::getAlias('@common/messages/vi/' . $file . '.php');

        $code = new LangForm(['file' => $file]);
        if ($code->load(Yii::$app->request->post()) && $code->validate()) {
            try {
                if ($code->saveFile($code->code)) {
                    Yii::$app->session->setFlash('alert', [
                        'body' => Yii::$app->params['update-success'],
                        'class' => 'alert-success',
                    ]);
                } else {
                    Yii::$app->session->setFlash('alert', [
                        'body' => Yii::$app->params['update-danger'],
                        'class' => 'alert-danger',
                    ]);
                }
            } catch (\yii\db\Exception $exception) {
                Yii::$app->session->setFlash('alert', [
                    'body' => $exception->getMessage(),
                    'class' => 'alert-danger',
                ]);
            }
        }
        return $this->render('update', [
            'model' => $model,
            'code' => $code,
        ]);
    }
}
