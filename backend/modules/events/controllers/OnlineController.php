<?php


namespace backend\modules\events\controllers;

use backend\components\MyController;
use backend\modules\clinic\models\Clinic;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\user\models\UserTimelineModel;
use Yii;

class OnlineController extends MyController
{
    public function actionListener()
    {
        if (Yii::$app->request->isGet) {
            header('Content-Type: text/event-stream');
            header("Cache-Control: no-cache");

//            $id = 'null';
            $cache = Yii::$app->cache;
            $key = 'redis-screen-online';
            $data = $cache->get($key);
            if ($data !== false) {
//                $id = $data['customer_id'];
//                $status = $data['status'];
//                $customer = Dep365CustomerOnline::find()->where(['id' => $id])->one();
//                $coso = [1 => 'một', 2 => 'hai', 3 => 'ba'];
//                $userCreate = Clinic::getUserCreatedBy($customer->created_by);
//                $customerName = $userCreate->fullname;
//                $cosoName = $coso[$customer->co_so];
//                $data['action'] = $status;
//                if ($status == UserTimelineModel::ACTION_TAO) {
//                    $data['notification'] = "Chúc mừng {$customerName} vừa có một lịch hẹn mới tại cơ sở {$cosoName}" ;
//                } elseif ($status == UserTimelineModel::ACTION_CAP_NHAT) {
//                    $data['notification'] = "Chúc mừng cơ sở {$cosoName} vừa có một khách đến";
//                }

                $cache->delete($key);
            }

            echo "data: " . json_encode($data, JSON_UNESCAPED_UNICODE) . "\n\n";
            echo "retry: 1000\n";
            echo "\n\n";

            ob_flush();
            flush();
            die();
        }
    }
}
