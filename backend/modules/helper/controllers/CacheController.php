<?php
/**
 * Created by PhpStorm.
 * User: abc
 * Date: 3/23/2020
 * Time: 10:45 AM
 */

namespace backend\modules\helper\controllers;


use yii\web\Controller;

class CacheController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionDeletecache()
    {
        try {
            \Yii::$app->cache->flush();
        } catch (\Exception $e) {
            echo '<pre>';
            print_r($e);
            echo '</pre>';
            die;
        }
        return $this->redirect('index');
    }


}