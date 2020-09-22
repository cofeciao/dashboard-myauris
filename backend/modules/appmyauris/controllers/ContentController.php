<?php

namespace backend\modules\appmyauris\controllers;

use backend\modules\appmyauris\models\AppMyauris;
use backend\modules\setting\models\Setting;
use Yii;
use yii\web\Controller;

/**
 * Default controller for the `filemanager` module
 */
class ContentController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $model = new AppMyauris();
        $model_setting_app_key_content = Setting::find()->where(['key_value' => AppMyauris::SETTING_APP_KEY_CONTENT])->one();
        if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post())){

            if ($model_setting_app_key_content != null ) {
                $model_setting_app_key_content->value = $model->content;

                try {
                    $model_setting_app_key_content->save(false);
                    Yii::$app->session->setFlash('alert', [
                        'body' => Yii::$app->params['create-success'],
                        'class' => 'bg-success',
                    ]);
                } catch (\yii\db\Exception $exception) {
                    Yii::$app->session->setFlash('alert', [
                        'body' => Yii::$app->params['create-danger'],
                        'class' => 'bg-danger',
                    ]);
                }
            }else{
                $model_setting_app_key_content = new Setting();
                $model_setting_app_key_content->value =  $model->content;
                $model_setting_app_key_content->key_value =  AppMyauris::SETTING_APP_KEY_CONTENT;
                $model_setting_app_key_content->param = AppMyauris::SETTING_APP_KEY_CONTENT;
                $model_setting_app_key_content->save(false);
            }

        }else{ // if post

            if ($model_setting_app_key_content != null ) {
                $model->content = $model_setting_app_key_content->value;
            }else{
                $model->content = "";
            }

        }
        return $this->render('index', [
            'model' => $model,
        ]);
    }
}
