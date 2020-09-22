<?php
/**
 * Created by PhpStorm.
 * User: abc
 * Date: 3/26/2020
 * Time: 5:25 PM
 */

namespace backend\modules\helper\controllers;


use yii\web\Controller;
use yii\web\Response;

class ConsoleController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionGetdata()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $post = \Yii::$app->request->post();
        $data = '';
        if (isset($post['data'])) {
            $data = $this->getData($post['data']);
        }
        return [
            'data' => $data,
            'error' => ''
        ];
    }

    public function getData($sql)
    {
        if (is_string($sql)) {
            $sql = str_replace(['delete', 'drop', 'alter', 'insert', 'update'], '', $sql);
            return \Yii::$app->db->createCommand($sql)->queryAll();
        }
    }
/*
*/

}