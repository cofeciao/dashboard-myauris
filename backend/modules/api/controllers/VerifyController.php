<?php


namespace backend\modules\api\controllers;

use backend\modules\api\modules\v1\models\UserApi;
use backend\modules\api\components\RestVerifyController;
use yii\web\Response;

class VerifyController extends RestVerifyController
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
        \Yii::$app->response->format = Response::FORMAT_HTML;
        $post = \Yii::$app->request->post();
        if (!isset($post['username'])  || !isset($post['password'])) {
            return [
                'status' => 0,
                'code' => 404,
                'data' => '',
                'message' =>  "Unknown value for 'username' or 'password'",
            ];
        }
        $user = UserApi::findByUsername($post['username']);
        if ($user !== null && $user->validatePassword($post['password'])) {
            $roleUser = $user->getRoleName($user->id);
            if ($roleUser == UserApi::USER_API) {
                return $user->access_token;
            }
        }
        return false;
    }
}
