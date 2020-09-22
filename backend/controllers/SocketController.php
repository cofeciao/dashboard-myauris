<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 25-Jan-19
 * Time: 2:03 PM
 */

namespace backend\controllers;

use backend\components\MyController;

class SocketController extends MyController
{
    public function actionIndex()
    {
        return $this->render('index', []);
    }

    public function actionStream()
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');

        echo "id: 01";
        ob_flush();
        flush();
    }
}
