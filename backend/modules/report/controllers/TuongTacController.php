<?php


namespace backend\modules\report\controllers;

use backend\components\MyController;
use backend\modules\baocao\models\BaocaoLocation;
use backend\modules\customer\models\Dep365CustomerOnlineDichVu;
use backend\modules\customer\models\Dep365CustomerOnlineFanpage;
use common\models\User;
use common\models\UserProfile;
use Yii;
use yii\web\Response;

class TuongTacController extends MyController
{
    public function actionIndex()
    {
        return $this->render('index', []);
    }

    public function actionExport()
    {
        echo "ahihi";
    }

    public function actionGetData()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $startDateReport = \Yii::$app->request->post('startDateReport');
            $endDateReport = \Yii::$app->request->post('endDateReport');
            $data = $this->getData($startDateReport, $endDateReport);
            return ['data' => $data];
        }
    }

    protected function getData($from, $to)
    {
        $from = strtotime($from);
        $to = strtotime($to) + 86399;

        $command = \Yii::$app->db->createCommand(" SELECT up.fullname,
    (SELECT COUNT(*) FROM dep365_customer_online co WHERE co.permission_user=u.id AND co.status=1 AND co.ngay_tao BETWEEN :startDate AND :endDate ) AS lich_moi,
    (SELECT COUNT(*) FROM dep365_customer_online_dathen_time t WHERE t.user_id=u.id AND t.time_lichhen_new BETWEEN :startDate AND :endDate ) AS tong_lich_hen,
    (SELECT COUNT(*) FROM dep365_customer_online co WHERE co.permission_user=u.id AND co.status=1 AND co.dat_hen=1 AND co.time_lichhen BETWEEN :startDate AND :endDate ) AS tong_khach_den,
    (SELECT COUNT(*) FROM dep365_customer_online co WHERE co.permission_user=u.id AND co.status=1 AND co.dat_hen=1 AND co.customer_come_time_to IN (1, 3, 5, 6) AND co.time_lichhen BETWEEN :startDate AND :endDate ) AS tong_khach_lam,
    (SELECT SUM(pancake.number_pancake) FROM pancake WHERE pancake.user_id=u.id AND pancake.date_import BETWEEN :startDate AND :endDate ) AS tuong_tac
FROM user u
    LEFT JOIN user_profile up ON up.user_id=u.id
    LEFT JOIN rbac_auth_assignment raa ON raa.user_id=u.id
WHERE raa.item_name IN ('user_nhanvien_online', 'user_manager_online')
    AND u.status=2 ", [':startDate' => $from, ':endDate' => $to]);

        // Yii::warning($command->rawSql);
        $data = $command->queryAll();
        if (empty($data)) {
            return false;
        }

        return $data;
    }
}
