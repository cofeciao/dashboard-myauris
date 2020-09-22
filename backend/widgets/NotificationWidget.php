<?php

namespace backend\widgets;

use backend\modules\general\models\Dep365Notification;
use backend\modules\user\models\User;
use backend\modules\user\models\UserSubRole;
use yii\base\Widget;
use yii\helpers\Html;

class NotificationWidget extends Widget
{
    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
    }

    public function run()
    {
        $userInfo = User::getUserInfo(\Yii::$app->user->id);

        if($userInfo == null) return null;

        $for_who = [
            Dep365Notification::FOR_EVERYONE,
            'user-'.\Yii::$app->user->id
        ];
        if ($userInfo->item_name != null) {
            $for_who[] = $userInfo->item_name;
        }
        if ($userInfo->subroleHasOne != null && $userInfo->subroleHasOne->role != null) {
            $for_who[] = $userInfo->subroleHasOne->role;
        }

        /* for manager online - remove later */
        if ($userInfo->item_name != null && $userInfo->item_name == User::USER_MANAGER_ONLINE) {
            $for_who[] = 'online';
        }
        /* for manager online - remove later */

        $cache = \Yii::$app->cache;
        $key = 'get-total-notif-not-seen-' . \Yii::$app->user->id;

        $time_change = $cache->get('time-change-notification');
        $user_time_change = $cache->get('time-change-notification-' . \Yii::$app->user->id);

        if ($time_change != $user_time_change) {
            $cache->set('time-change-notification-' . \Yii::$app->user->id, $time_change);
            $keys = [
                'get-user-info-noti-' . \Yii::$app->user->id,
                'get-total-notif-not-seen-' . \Yii::$app->user->id,
                'get-5-notif-final-' . \Yii::$app->user->id
            ];

            foreach ($keys as $item) {
                $cache->delete($item);
            }
        }
        $total_notif_not_seen = $cache->get($key);
        if ($total_notif_not_seen == false) {
            $query = Dep365Notification::find()
                ->joinWith('notificationSeenHasMany')
                ->andWhere(['IN', 'for_who', $for_who])
                ->andWhere("(SELECT COUNT(*) FROM dep365_notification_seen WHERE notification_id=dep365_notification.id AND user_id='" . \Yii::$app->user->id . "')=0");
            $total_notif_not_seen = $query
                ->count(Dep365Notification::tableName() . '.id');
            $cache->set($key, $total_notif_not_seen);
        }


        $key5notifshow = 'get-5-notif-final-' . \Yii::$app->user->id;

        $model = $cache->get($key5notifshow);
        if ($model == false || !is_array($model)) {
            $model = Dep365Notification::find()
                ->select(
                    Dep365Notification::tableName() . ".id," .
                    Dep365Notification::tableName() . ".icon," .
                    Dep365Notification::tableName() . ".name," .
                    Dep365Notification::tableName() . ".created_at," .
                    Dep365Notification::tableName() . ".description," .
                    "(SELECT COUNT(*) FROM dep365_notification_seen WHERE notification_id=dep365_notification.id AND user_id='" . \Yii::$app->user->id . "') AS seen"
                )
                ->joinWith('notificationSeenHasMany')
                ->where(['is_new' => Dep365Notification::IS_NEW])
                ->andWhere(['IN', 'for_who', $for_who])
                ->orderBy([Dep365Notification::tableName() . '.created_at' => SORT_DESC])
                ->offset(0)
                ->limit(5);
            $model = $model->all();
            $cache->set($key5notifshow, $model);
        }
        return $this->render('notificationWidget', [
            'model' => $model,
            'userInfo' => $userInfo,
            'total_notif_not_seen' => $total_notif_not_seen
        ]);
    }
}