<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 08-Jan-19
 * Time: 2:54 PM
 */

namespace backend\models;

use backend\modules\customer\models\Dep365CustomerOnlineDathenTime;
use backend\modules\customer\models\Dep365SendSms;
use Yii;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\customer\models\Dep365CustomerOnlineTree;

class SiteModel
{
    /*
     * Tính tổng số người đã đặt hẹn mà không được cập nhật trạng thái ngày hôm đó.
     */
    public function getDatHenMiss($day = null): int
    {
        $query = Dep365CustomerOnline::find()->where(['status' => Dep365CustomerOnline::STATUS_DH]);
        $query->andWhere('dat_hen IS NULL');
        $query->andWhere(['between', 'time_lichhen', strtotime($day), strtotime($day) + 86400])->findCustomerOfOnline();

        return $query->count();
    }

    /*
     * Tính tổng số sms bị thiếu, chưa nhắn tin
     */
    public function getMissSmsNumber($day = null)
    {
        if ($day == null) {
            $day = date('d-m-Y');
        }
        $datetime = new \DateTime($day);
        $datetime->setTimezone(new \DateTimeZone('Asia/Ho_Chi_Minh'));

        $fromw = strtotime($day);
        $tow = $fromw + 86399;
        $query = Dep365CustomerOnline::find()->where('dep365_customer_online.province != 97')->findCustomerOfOnline();

        $query->andWhere('(`dep365_customer_online`.`time_lichhen` between ' . $fromw . ' and ' . $tow . ' 
        and `dep365_customer_online`.`updated_at` between ' . $fromw . ' and ' . $tow . ')
        or (`dep365_customer_online`.`time_lichhen` between ' . ($fromw + 86400) . ' and ' . ($tow + 86400) . ')
        or (`dep365_customer_online`.`time_lichhen` between ' . ($fromw + 3 * 86400) . ' and ' . ($tow + 3 * 86400) . ')
        or (`dep365_customer_online`.`time_lichhen` between ' . ($fromw + 7 * 86400) . ' and ' . ($tow + 7 * 86400) . ')');

        $querySms = Dep365SendSms::find()->where(['status' => 0])->select('customer_id');
        $querySms->andWhere(['in', 'sms_lanthu', [1, 3, 7]]);
        $querySms->andWhere(['between', 'created_at', $fromw, $tow]);
        $querySms->groupBy('customer_id');

        $cache = Yii::$app->cache;
        $key1 = 'get-miss-sms-number-customer-total';
        $key2 = 'get-miss-sms-number-sms-total';

        $customerTotal = $cache->get($key1);
        $smsTotal = $cache->get($key2);

        $timeCache = strtotime(date('d-m-Y') . '+1 day') - time();

        if ($customerTotal == false || $smsTotal == false) {
            $customerTotal = count($query->asArray()->all());
            $smsTotal = count($querySms->asArray()->all());
            $cache->set($key1, $customerTotal, $timeCache);
            $cache->set($key2, $smsTotal, $timeCache);
        }
        //Trường hợp nhắn tin rồi nhưng khách hàng lại dời lịch hẹn thì total < 0
        $total = $customerTotal - $smsTotal;
        if ((int)($total) < 0) {
            $total = 0;
        }

        return $total;
    }

    /*
     * Tổng số điện thoại gọi được
     */
    public function callSuccess($startDate = null): int
    {
        $startDate = strtotime($startDate);
        $endDate = $startDate + 86399;

        $query = Dep365CustomerOnline::find();
        $query = $query->andWhere(['between', 'dep365_customer_online.created_at', $startDate, $endDate]);
        $query = $query->andWhere(['in', 'dep365_customer_online.status', [Dep365CustomerOnline::STATUS_DH, Dep365CustomerOnline::STATUS_FAIL]])->findCustomerOfOnline();
        $data = $query->count();

        return $data;
    }

    /*
     * Tổng số điện thoại mới
     */
    public function newPhone($startDate = null): int
    {
        $startDate = strtotime($startDate);
        $endDate = $startDate + 86399;
        $query = Dep365CustomerOnline::find();
        $query = $query->andWhere(['between', 'dep365_customer_online.created_at', $startDate, $endDate])->findCustomerOfOnline();
        $data = $query->count();

        return $data;
    }

    /*
     * Tổng khách đặt hẹn mới
     */
    public function khachDH($startDate = null): int
    {
        $startDate = strtotime($startDate);
        $endDate = $startDate + 86399;

        $query = Dep365CustomerOnlineDathenTime::find();
        $query->joinWith(['customerHasOne']);
        $query->andWhere(['between', 'dep365_customer_online_dathen_time.time_change', $startDate, $endDate]);
        $query->andWhere('dep365_customer_online_dathen_time.time_lichhen is null');
        $query->andWhere(['between', 'dep365_customer_online.created_at', $startDate, $endDate]);
        $data = $query->count();

        return $data;
    }

    /*
     * Tổng khách hàng cũ đặt hẹn
     */
    public function khachOldFailToDatHen($startDate = null): int
    {
        $startDate = strtotime($startDate);
        $endDate = $startDate + 86399;

        $cache = Yii::$app->cache;
        $key = 'redis-khach-old-fail-to-dathen-' . $endDate;
        $data = $cache->get($key);
        if ($data === false) {
            $query = Dep365CustomerOnlineTree::find()->select('dep365_customer_online_tree.customer_online_id');
            $query = $query->innerJoinWith(['customerOnlineHasOne', 'failStatusTreeHasOne']);

            $query = $query->andWhere(['between', 'dep365_customer_online_tree.time_change', $startDate, $endDate]);
            $query = $query->andWhere(['not between', 'dep365_customer_online.created_at', $startDate, $endDate]);

            $query = $query->andWhere(['in', 'dep365_customer_online_tree.status_id', [Dep365CustomerOnline::STATUS_FAIL, Dep365CustomerOnline::STATUS_KBM]]);
            $query = $query->andWhere(['dep365_customer_online_tree.status_id_new' => Dep365CustomerOnline::STATUS_DH]);

            $query = $query->groupBy('customer_online_id');
            $data = $query->count();
            $cache->set($key, $data, 86400 * 7 * 30);
        }
        return $data;
    }

    /*
     * Tổng khách đặt hẹn theo cơ sở
     */

    public function khachDatHenWithCoSo($startDate = null)
    {
        $startDate = strtotime($startDate);
        $endDate = $startDate + 86399;

        $connection = Yii::$app->getDb();

        $command = $connection->createCommand("SELECT `dep365_co_so`.`name`, COUNT(`co_so`) as numberCS
            FROM `dep365_customer_online`
            INNER JOIN `dep365_customer_online_tree` ON `dep365_customer_online`.`id` = `dep365_customer_online_tree`.`customer_online_id`
            INNER JOIN `dep365_co_so` ON `dep365_customer_online`.`co_so` = `dep365_co_so`.`id`
            
            WHERE (`dep365_customer_online_tree`.`time_change` BETWEEN :startDate AND :endDate)
            
            AND ((`dep365_customer_online_tree`.`status_id` IS NULL or `dep365_customer_online_tree`.`status_id` in (2,3)) AND (`dep365_customer_online_tree`.`status_id_new` = 1))
            AND ((`dep365_customer_online`.`is_customer_who` = 1))
            GROUP BY `co_so`", [':startDate' => $startDate, ':endDate' => $endDate]);

        $result = $command->queryAll();
        return $result;
    }
}
