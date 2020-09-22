<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 15-Jan-19
 * Time: 10:27 AM
 */

namespace console\controllers;

use backend\models\SiteModel;
use backend\modules\general\models\Dep365Notification;
use backend\modules\user\models\UserSubRole;
use yii\console\Controller;

class GeneralController extends Controller
{
    public function actionIndex()
    {
        return 'test';
    }

    public function actionFindSmsMiss()
    {
        $siteModel = new SiteModel();

        $datetimeS = new \DateTime();
        $day = $datetimeS->modify('-1 day')->format('d-m-Y');
        $smsMiss = $siteModel->getMissSmsNumber($day);
        if ($smsMiss > 0) {
            $notification = new Dep365Notification();
            $notification->name = 'Tin nhắn bị thiếu';
            $notification->icon = 'ft-mail';
            $notification->is_bg = '3';
            $notification->description = 'Có ' . $smsMiss . ' tin nhắn đã không được gửi trong ngày ' . $day;
            $notification->for_who = UserSubRole::ROLE_TRUONG_PHONG;
            $notification->save();

            $cache = \Yii::$app->cache;
            $key = 'get-total-notif-not-seen';
            $cache->delete($key);

            $key5notifshow = 'get-5-notif-final';
            $cache->delete($key5notifshow);
        }
    }

    public function actionFindUpdateDatHen()
    {
        $siteModel = new SiteModel();
        $datetimeS = new \DateTime();
        $day = $datetimeS->modify('-1 day')->format('d-m-Y');
        $dathenMiss = $siteModel->getDatHenMiss($day);
        if ($dathenMiss > 0) {
            $notification = new Dep365Notification();
            $notification->name = 'Lịch hẹn không cập nhật';
            $notification->icon = 'ft-alert-circle';
            $notification->is_bg = '3';
            $notification->description = 'Có ' . $dathenMiss . ' lịch hẹn không được cập nhật trong ngày ' . $day;
            $notification->for_who = UserSubRole::ROLE_TRUONG_PHONG;
            $notification->save();

            $cache = \Yii::$app->cache;
            $key = 'get-total-notif-not-seen';
            $cache->delete($key);

            $key5notifshow = 'get-5-notif-final';
            $cache->delete($key5notifshow);
        }
    }
}
