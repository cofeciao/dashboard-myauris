<?php

namespace backend\modules\setting\controllers;

use backend\components\MyController;
use backend\models\form\UserSettingsForm;
use backend\models\UserSettings;
use yii\web\Response;
use yii\widgets\ActiveForm;

class SettingController extends MyController
{
    public function actionIndex()
    {
        $model = new UserSettingsForm();
        $userSettings = UserSettings::find()->where(['user_id' => \Yii::$app->user->getId()])->one();
        if ($userSettings == null) {
            $userSettings = new UserSettings();
        } else {
            foreach ($userSettings->getAttributes() as $key => $value) {
                if (property_exists(UserSettingsForm::class, $key)) {
                    $model->$key = $value;
                }
            }
        }
        if ($model->load(\Yii::$app->request->post())) {
            if ($model->validate()) {
                $userSettings->auth_message = $model->auth_message;
                $userSettings->auth_mail = $model->auth_mail;
                if ($userSettings->save()) {
                    \Yii::$app->session->setFlash('alert', [
                        'class' => 'alert-success',
                        'body' => 'Lưu thành công'
                    ]);
                } else {
                    \Yii::$app->session->setFlash('alert', [
                        'class' => 'alert-warning',
                        'body' => 'Lưu thất bại'
                    ]);
                }
                return $this->refresh();
            }
        }
        return $this->render('index', [
            'model' => $model
        ]);
    }

    public function actionValidateSetting()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $model = new UserSettingsForm();
            if ($model->load(\Yii::$app->request->post())) {
                return ActiveForm::validate($model);
            }
        }
    }
}
