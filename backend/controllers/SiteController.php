<?php

namespace backend\controllers;

use backend\components\MyController;
use backend\models\CustomerModel;
use backend\models\Dep365CustomerOnlineRemindCall;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\customer\models\Dep365CustomerOnlineCome;
use backend\modules\customer\models\Dep365CustomerOnlineDathenTime;
use backend\modules\testab\models\AbCampaign;
use backend\modules\user\models\User;
use backend\modules\user\models\UserTimelineModel;
use Yii;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends MyController
{
    public function actionBaotri()
    {
        $this->layout = false;
        return $this->render('baotri', []);
    }

    public function actionIndex()
    {
        $result = [];
        $tyLeNhanvienLamViec = [];
        $user = new User();
        $roleUser = $user->getRoleName(Yii::$app->user->id);
        if (in_array($roleUser, [User::USER_CHAY_ADS, User::USER_MANAGER_CHAY_ADS])) {
            $from = strtotime('-3 days', strtotime(date('d-m-Y')));
            $to = $from + 86399;
            $checkCampaign = AbCampaign::find()->where(['between', 'created_at', $from, $to])->all();
            return $this->render('chayAds', [
                'checkCampaign' => $checkCampaign
            ]);
        }

        if ($roleUser == User::USER_SALE_RANG) {
            return $this->redirect(['/toothstatus/tooth-status']);
        }

        if ($roleUser == User::USER_TEST) {
            return $this->render('user_test');
        }
        if ($roleUser == User::USER_MANAGER) {
            return $this->render('user_manager');
        }

        if ($roleUser == User::USER_COVAN) {
            return $this->render('covan');
        }

        if ($roleUser == User::USER_QUANLY_PHONGKHAM) {
            $doctors = \common\models\User::getNhanVienBacSi();
            $assistants = \common\models\User::getNhanVienTroThuArray();
            $direct_sales = \common\models\User::getNhanVienTuDirectSaleIsActiveArray();

            return $this->render('quanly_phongkham', [
                'doctors' => $doctors,
                'assistants' => $assistants,
                'direct_sales' => $direct_sales
            ]);
        }

        if ($roleUser == User::USER_NHANSU) {
            return $this->render('nhansu');
        }

        if ($roleUser == User::USER_CHUP_HINH) {
            return $this->render('chupHinh');
        }

        if (in_array($roleUser, [User::USER_KE_TOAN, User::USER_MANAGER_KE_TOAN])) {
            return $this->render('ketoan');
        }

        if ($roleUser == User::USER_BIEN_TAP) {
            return $this->render('bientap');
        }

        if (in_array($roleUser, [User::USER_NHANVIEN_ONLINE, User::USER_MANAGER_ONLINE])) {
            return $this->render('nhanvienOnline');
        }

        if (in_array($roleUser, [User::USER_LE_TAN, User::USER_MANAGER_LE_TAN])) {
            return $this->render('letan');
        }

        if ($roleUser == User::USER_DATHEN) {
            return $this->render('datHen');
        }

        if ($roleUser == User::USER_ADMINISTRATOR) {
            return $this->render('administrator');
        }

        if ($roleUser == User::USER_REPORT) {
            return $this->render('report');
        }

        if (in_array($roleUser, [User::USER_SEO, User::USER_MANAGER_SEO])) {
            return $this->render('seo');
        }

        if (in_array($roleUser, [User::USER_MANAGER_KIEM_SOAT, User::USER_KIEM_SOAT])) {
            return $this->render('kiem-soat');
        }

        if ($roleUser == User::USER_MYAURIS) {
            return $this->render('myauris');
        }

        if ($roleUser == User::USER_SCREEN) {
            return $this->render('screen');
        }

        if ($roleUser == User::USER_DEVELOP) {
            return $this->render('index', [
                'dataTuVanOnline' => $result,
                'tyLeNhanvienLamViec' => $tyLeNhanvienLamViec,
            ]);
        }

        // Phan danh rieng cho ky thuat Labo
        if ($roleUser == User::USER_KY_THUAT_LABO) {
            return $this->redirect(['/labo/labo-don-hang']);
        }

        // Pham Thanh Nghia 9-6-2020
        // if ($roleUser == User::USER_BAC_SI) {
        //     return $this->render('bacSi');
        // }

        // if (in_array($roleUser, [User::USER_DIRECT_SALE, User::USER_MANAGER_DIRECT_SALE])) {
        //     return $this->render('directsale');
        // }

        // Sale Direct, Tro Thu can xem cong hang ngay de check lai vs le tan
        if (in_array($roleUser, [User::USER_DIRECT_SALE, User::USER_MANAGER_DIRECT_SALE])) {
            return $this->redirect(['/clinic/clinic-check/index']);
        }
        if (in_array($roleUser, [User::USER_TRO_THU, User::USER_BAC_SI])) {
            return $this->redirect(['/clinic/clinic-dieu-tri/index']);
        }
    }

    public function actionError()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/auth/login');
        } else {
            $exception = Yii::$app->errorHandler->exception;
            if ($exception !== null) {
                return $this->render('error', ['exception' => $exception]);
            }
        }
    }

    public function actionLoadDataCountCustomer()
    {
        $CustomerFrom = strtotime(date('01-m-Y'));
        $CustomerTo = time();
        $data_report = [];

        $cache = Yii::$app->cache;
        $key1 = 'load-data-count-customer-all';

        $data1 = $cache->get($key1);
        if ($data1 == false) {
            $data1 = CustomerModel::find()->where(['between', 'created_at', $CustomerFrom, $CustomerTo])->count();
            $cache->set($key1, $data1, 3600 * 2);
        }
        $data_report['all'] = $data1;

        $key2 = 'load-data-count-customer-booking';

        $data2 = $cache->get($key2);
        if ($data2 == false) {
            $data2 = Dep365CustomerOnlineDathenTime::find()->where(['between', 'date_change', $CustomerFrom, $CustomerTo])->count();
            $cache->set($key2, $data2, 3600 * 2);
        }
        $data_report['booking'] = $data2;

        $query = CustomerModel::find()->where(['between', 'customer_come', $CustomerFrom, $CustomerTo]);

        $key3 = 'load-data-count-customer-come';

        $data3 = $cache->get($key3);
        if ($data3 == false) {
            $data3 = $query->andWhere(['dat_hen' => 1])->count();
            $cache->set($key3, $data3, 3600 * 2);
        }
        $data_report['come'] = $data3;

        $key4 = 'load-data-count-customer-done';
        $data4 = $cache->get($key4);
        if ($data4 == false) {
            $data4 = $query->andWhere(['NOT IN', 'customer_come_time_to', [2, 4]])->count();
            $cache->set($key4, $data4, 3600 * 2);
        }
        $data_report['done'] = $data4;
        return $this->renderAjax('home/reportCountCustomer', [
            'data_report' => $data_report
        ]);
    }

    public function actionLoadDataReport()
    {
        $cache = Yii::$app->cache;
        $key = 'load-data-report-site';

        $data = $cache->get($key);
        if ($data == false) {
            $query = CustomerModel::find()->joinWith(['statusCustomerHasOne', 'statusDatHenHasOne', 'statusCustomerGotoAurisHasOne', 'provinceHasOne'])->limit('10');
            $data = $query->Orderby(['customer_come' => SORT_DESC])->all();
            $cache->set($key, $data, 3600);
        }

        return $this->renderPartial('home/recentCustomerCome', [
            'data_report' => $data
        ]);
    }

    public function actionLoadData()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $d = date('d');
        $first_lm = strtotime(date('1-m-Y') . ' -1 months');
        $this_lm = strtotime(date('d-m-Y') . ' -1 months');
        $from = strtotime(date('01-m-Y', time()));
        $today = time();
        $data = [];
        $date = [];
        $this_month = [];
        $last_month = [];
        $this_m = self::getLichHen($from, $today);
        $last_m = self::getLichHen($first_lm, $this_lm);
        for ($i = 1; $i <= $d; $i++) {
            $date[$i] = $i . '-' . date('m-Y');
            $this_month[$i] = 0;
            $last_month[$i] = 0;
            foreach ($this_m as $item) {
                if (strtotime(date('d-m-Y', $item->date_lichhen)) == strtotime($i . '-' . date('m-Y'))) {
                    //                if ($item->date_lichhen_new == strtotime($i . '-' . date('m-Y')))
                    $this_month[$i] = $item->user;
                }
            }
            foreach ($last_m as $item) {
                if (strtotime(date('d-m-Y', $item->date_lichhen)) == strtotime($i . '-' . date('m-Y') . '- 1 months')) {
                    //                if ($item->date_lichhen_new == strtotime($i . '-' . date('m-Y') . '- 1 months')) {
                    $last_month[$i] = $item->user;
                }
            }
        }
        $data['date'] = $date;
        $data['this_month'] = $this_month;
        $data['last_month'] = $last_month;
        return ['data_report' => $data];
    }

    protected static function getLichHen($from, $to)
    {
        $timeCache = strtotime(date('d-m-Y') . '+1 day') - time();
        $cache = Yii::$app->cache;
        $key = 'get-lich-hen-in-site-' . $from . '-' . $to;

        $data = $cache->get($key);
        if ($data == false) {
            $query = CustomerModel::find()->select('date_lichhen, count(*) as user')->where([
                CustomerModel::tableName() . '.is_customer_who' => CustomerModel::IS_CUSTOMER_TV_ONLINE
            ])->andWhere([
                '>', CustomerModel::tableName() . '.time_lichhen', $from
            ])->andWhere([
                '<', CustomerModel::tableName() . '.time_lichhen', $to
            ])->groupBy(Dep365CustomerOnline::tableName() . '.date_lichhen');

            $data = $query->all();
            $cache->set($key, $data, $timeCache);
        }

        return $data;
    }

    protected static function getNhanVienOnline()
    {
        return \common\models\User::getNhanVienIsActiveArray();
    }

    public function actionLoadUserTimeline()
    {
        $cache = Yii::$app->cache;
        $key = 'load-user-timeline-site';

        $data = $cache->get($key);
        if ($data == false) {
            $data = UserTimelineModel::find()->joinWith(['nameCustomerHasOne', 'nameUserHasOne'])->limit('20')
                ->orderBy(['user_timeline.created_at' => SORT_DESC])->all();
            $cache->set($key, $data, 86400);
        }

        return $this->renderAjax('home/userTimeline', [
            'data' => $data,
        ]);
    }

    public function actionLoadDataNewCustomer()
    {
        $from = strtotime(date('d-m-Y') . ' -7 days');
        $to = time();
        $data_report = [];

        $cache = Yii::$app->cache;
        $key = 'load-data-new-customer';

        $new_customer = $cache->get($key);

        if ($new_customer == false) {
            $new_customer = CustomerModel::find()->select('ngay_tao, count(id) AS TOTALKH')
                ->where(['between', 'created_at', $from, $to])
                ->andWhere(['>', 'ngay_tao', 0])
                ->andWhere('ngay_tao IS not NULL')
                ->groupBy(['ngay_tao'])->all();
            $cache->set($key, $new_customer, 6 * 3600);
        }

        foreach ($new_customer as $item) {
            $date = $item->ngay_tao;
            $data_report[] = [
                "date" => date('Y-m-d', $date),
                "total" => $item->TOTALKH,
            ];
        }
        return $this->renderAjax('home/reportNewCustomer', [
            'data_report' => $data_report,
        ]);
    }

    public function actionLoadProvinceCustomer()
    {
        $from = strtotime(date('01-m-Y', time()));
        $today = time();
        $data_report = [];
        $data = [];
        $query = CustomerModel::find()->select('province.name,count(dep365_customer_online.id) AS TOTALKH')
            ->joinWith(['provinceHasOne'])->where(['between', 'date_lichhen', $from, $today]);

        $cache = Yii::$app->cache;
        $key = 'load-province-customer-total-site';
        $data_total = $cache->get($key);

        if ($data_total == false) {
            $data_total = $query->andwhere(['!=', 'dat_hen', 'NULL'])
                ->groupBy('province')->Orderby(['TOTALKH' => SORT_DESC])->all();
            $cache->set($key, $data_total, 6 * 3600);
        }

        $key1 = 'load-province-customer-come-site';

        $data_come = $cache->get($key1);

        if ($data_come == false) {
            $data_come = $query->andwhere(['dat_hen' => 1])
                ->groupBy('province')->Orderby(['TOTALKH' => SORT_DESC])->all();
            $cache->set($key1, $data_come, 6 * 3600);
        }

        foreach ($data_come as $come) {
            $data[$come->name] = $come->TOTALKH;
        }
        foreach ($data_total as $total) {
            $come = 0;
            if (array_key_exists($total->name, $data)) {
                $come = $data[$total->name];
            }
            $per_come = $come / $total->TOTALKH * 100;
            $data_report[] = [
                'province' => $total->name,
                'total' => $total->TOTALKH,
                'come' => $come,
                'not_come' => $total->TOTALKH - $come,
                'per_come' => round($per_come),
                'per_not_come' => round(100 - $per_come)
            ];
        }
        return $this->renderAjax('home/reportProvinceCustomer', [
            'data_report' => $data_report,
        ]);
    }

    public function actionLoadUserAlert()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $today = time();
        $lasweek = date('d-m-Y', strtotime(date('d-m-Y') . ' -7 days'));
        $yesterday = date('d-m-Y', strtotime(date('d-m-Y') . ' -1 days'));
        $userID = Yii::$app->user->id;
        $user = new User();
        $roleUser = $user->getRoleName($userID);
        $data = [];

        $cache = Yii::$app->cache;
        $key1 = 'load-user-alert-care-' . $userID;

        $care = $cache->get($key1);
        $sql = '';
        if ($care == false) {
            $query = Dep365CustomerOnlineRemindCall::find()
                ->where(['between', 'remind_call_time', strtotime($lasweek), $today])
                ->published()
                ->andFilterWhere(['!=', 'dep365_customer_online_remind_call.remind_call_time', 'null'])
                ->andWhere(['type' => Dep365CustomerOnlineRemindCall::TYPE_CUSTOMER_ONLINE])
                ->orderBy([new \yii\db\Expression('FIELD (remind_call_time, ' . strtotime(date('d-m-Y')) . ') DESC'), 'remind_call_time' => SORT_DESC]);
            if (!in_array($roleUser, [User::USER_ADMINISTRATOR, User::USER_DEVELOP])) {
                $query->andWhere(['dep365_customer_online_remind_call.permission_user' => $userID]);
            }
            $sql = $query->createCommand()->rawSql;
            $care = $query->count();
            $cache->set($key1, $care, 12 * 3600);
        }

        $key2 = 'load-user-alert-booking-fail';

        $booking_fail = $cache->get($key2);
        if ($booking_fail == false) {
            $booking_fail = CustomerModel::find()
                ->where(['dat_hen' => 2])
                ->andwhere(['=', 'date_lichhen', $yesterday])
                ->andWhere(['permission_user' => $userID])->count();
            $cache->set($key2, $booking_fail, 12 * 3600);
        }
        $data['care'] = $care;
        $data['booking_fail'] = $booking_fail - $care;
        return ['data' => $data, 'sql' => $sql];
    }

    public function actionGetDataCustomerCompareChart()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $startDateReport = strtotime(Yii::$app->request->post('startDateReport'));
            $endDateReport = strtotime(Yii::$app->request->post('endDateReport')) + 86399;
            $co_so = Yii::$app->user->identity->permission_coso;

            $dataReturn = [];
            $date = [];
            $customerCome = [];
            $customerDone = [];

            //Lấy khách đến từng cơ sở theo thời gian
            $khachDen = CustomerModel::find()
                ->select(['customer_come_date', 'COUNT(id) AS user'])
                ->where(['BETWEEN', 'customer_come_date', $startDateReport, $endDateReport])
                ->andWhere(['co_so' => $co_so])
                ->andWhere(['IN', 'status', [CustomerModel::STATUS_DH]])
                ->andWhere(['IN', 'dat_hen', [CustomerModel::DA_DEN]])
                ->groupBy(['customer_come_date'])->indexBy('customer_come_date')->all();

            //Lấy khách làm từng cơ sở theo thời gian
            $customerDoneAccept = Dep365CustomerOnlineCome::find()
                ->select('id')
                ->where(['accept' => Dep365CustomerOnlineCome::STATUS_ACCEPT])
                ->asArray()
                ->indexBy('id')->all();
            $khachLam = CustomerModel::find()
                ->select(["date_lichhen", 'COUNT(id) AS user'])
                ->where(['BETWEEN', 'date_lichhen', $startDateReport, $endDateReport])
                ->andWhere(['co_so' => $co_so])
                ->andWhere(['IN', 'customer_come_time_to', array_keys($customerDoneAccept)])
                ->groupBy(['date_lichhen'])->indexBy('date_lichhen')->all();

            for ($i = $startDateReport; $i <= $endDateReport; $i += 86400) {
                $date[] = date('d-m-Y', $i);

                $customerCome[] = !array_key_exists($i, $khachDen) ? 0 : $khachDen[$i]->user;
                $customerDone[] = !array_key_exists($i, $khachLam) ? 0 : $khachLam[$i]->user;

            }

            $dataReturn['date'] = $date;
            $dataReturn['come'] = $customerCome;
            $dataReturn['done'] = $customerDone;

            return [
                'dataReturn' => $dataReturn,
            ];
        }
    }

    public function actionGetDataAppointment()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $startDateReport = strtotime(Yii::$app->request->post('startDateReport'));
            $endDateReport = strtotime(Yii::$app->request->post('endDateReport')) + 86399;
            $co_so = Yii::$app->user->identity->permission_coso;

            $customers = Dep365CustomerOnline::find()
                ->select(['id','name','full_name','forename','phone','note','dat_hen',"FROM_UNIXTIME(time_lichhen, '%Y-%m-%d\\T%h:%i:%s') as time_lichHen"])
                ->where(['status' => Dep365CustomerOnline::STATUS_DH, 'co_so' => $co_so])
                ->andWhere(['BETWEEN', 'time_lichhen', $startDateReport, $endDateReport])
                ->groupBy('id');
//            $sql = $customers->createCommand()->getRawSql();
            $customers = $customers->all();

            $dataReturn = [];
            $color = '';
            foreach ($customers as $customer) {
                if ($customer->dat_hen == Dep365CustomerOnline::DAT_HEN_DEN)
                    $color = '#16D39A';
                elseif ($customer->dat_hen == Dep365CustomerOnline::DAT_HEN_KHONG_DEN)
                    $color = '#FF7588';
                else
                    $color = '#1890ff';

                $dataReturn[] = [
                    'title' => $customer->name,
                    'call' => $customer->phone,
                    'start' => $customer->time_lichHen,
                    'description' => $customer->note,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                ];
            }

            return [
                'customers' => $dataReturn,
            ];
        }
    }
}
