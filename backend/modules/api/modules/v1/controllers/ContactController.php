<?php

namespace backend\modules\api\modules\v1\controllers;

use Yii;
use backend\modules\api\components\RestController;
use backend\modules\api\modules\v1\models\ContactApiModel;
use yii\bootstrap\ActiveForm;

class ContactController extends RestController
{
    public $modelClass = 'backend\modules\api\modules\v1\models\ContactApiModel';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['bootstrap']['only'] = ['create-contact', 'index'];
        return $behaviors;
    }

    public function actionIndex()
    {
        return [];
    }

    public function actionCreateContact()
    {
        $contact = new ContactApiModel();
        $request = Yii::$app->request;

        if (isset($request)) {
            $contact->fullname = $request->getBodyParam('fullname');
            $contact->phone = $request->getBodyParam('phone');
            $contact->title = $request->getBodyParam('title');
            $contact->content = $request->getBodyParam('content');
            $contact->ip = $request->getBodyParam('ip');
            $contact->type = $request->getBodyParam('type');

            if ($contact->validate() && $contact->save()) {
                $msg = 'Thành công';
                return ['status' => 200, 'msg' => $msg];
            } else {
                return ActiveForm::validate($contact);
            }
        }
    }
}
