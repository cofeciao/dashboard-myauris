<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 09-Mar-19
 * Time: 11:56 AM
 */

namespace backend\controllers;

use backend\components\MyComponent;
use backend\components\MyController;
use yii\web\Response;

class ConfigController extends MyController
{
    /*
     * On or Off Menu Icon
     */
    public function actionSetMenuIcon()
    {
        if (\Yii::$app->request->isAjax) {
            $icon = \Yii::$app->request->post('icon');
            $status = '200';

            if ($icon == 0) {
                $icon = 1;
            } else {
                $icon = 0;
            }

            MyComponent::setCookies('icon', $icon);

            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['status' => $status, 'result' => $icon];
        }
    }

    public function actionCustomFieldCustomer()
    {
        if (\Yii::$app->request->isAjax) {
            $field = \Yii::$app->request->post('field');
            $fieldVal = \Yii::$app->request->post('fieldVal');
            $status = '200';
            MyComponent::setCookies($field, $fieldVal);

            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['status' => $status];
        }
    }

    public function actionCustomFieldToggle()
    {
        if (\Yii::$app->request->isAjax) {
            $arrayField = \Yii::$app->request->post('arrayField');
            $status = '200';
            foreach ($arrayField as $object) {
                $arrayValue = json_decode(json_encode($object), true);
                foreach ($arrayValue as $key => $value) {
                    if (MyComponent::getCookies($key) != $value) {
                        MyComponent::setCookies($key, $value);
                    }
                }
            }
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['status' => $status];
        }
    }
}
