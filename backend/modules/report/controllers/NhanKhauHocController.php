<?php

namespace backend\modules\report\controllers;

use backend\components\MyController;
use backend\models\CustomerModel;
use backend\modules\clinic\models\PhongKhamSanPham;
use backend\modules\customer\models\Dep365CustomerOnlineFanpage;
use backend\modules\report\components\DateRangePickerHelper;
use yii\db\Query;
use yii\web\Response;

class NhanKhauHocController extends MyController
{
    public $data;
    public $dateRange = [];
    public $datetimeStamp = [];

    public function actionIndex()
    {

//        $sum = (new Query())->select(["count(id) as sum_customer"])->from(CustomerModel::tableName())->limit(1)->one();
//        $data = $sum;
        return $this->render('index', ['data' => $this->data]);
    }

    public function receiveDataForm()
    {
        $this->data = [];
        $subfilter = \Yii::$app->request->get();
        $start_date_report = isset($subfilter['startDateReport']) ?
            ($subfilter['startDateReport']) :
            date('01-m-Y');
        $end_date_report = isset($subfilter['endDateReport']) ?
            ($subfilter['endDateReport']) :
            date('d/m/Y', time());
        $this->data = $this->convertArrayDataToChartData($subfilter['data']);
        $dataAttribute = array(
            'dotuoi' => '',
            'gioitinh' => '',
            'filter_dimension' => '',
            'fanpage' => '',
            'end_date_report' => $end_date_report,
            'start_date_report' => $start_date_report,
        );

        $this->data = array_merge($dataAttribute, $this->data);
        $dateRange = DateRangePickerHelper::getListDateRange($start_date_report, $end_date_report);
        $this->data['dataLabel'] = $this->dateRange = $dateRange;
        $this->data['datetimeStamp'] = $this->datetimeStamp = ['startDate' => DateRangePickerHelper::formatTimestampToDateTimeObject($this->data['start_date_report'])->getTimestamp(), 'endDate' => DateRangePickerHelper::formatTimestampToDateTimeObject($this->data['end_date_report'])->getTimestamp()];
        $this->data['dataSet'] = $this->dataBuilder($this->data);
    }

    public function actionGetData()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $this->receiveDataForm();

        $this->data['table_html'] = $this->getTableOverview();
        return $this->data;
    }

    public function dataBuilder($data)
    {
        $res = [];
        //Tổng số khách được tạo từ ngày đến ngày
        //$sql = "SELECT ngay_tao as name, count(ngay_tao) as value FROM `dep365_customer_online` where ngay_tao between 1572541200 and 1575046800 group by ngay_tao";
        $query = (new  Query())
            ->select(['ngay_tao as name', 'count(ngay_tao) as value'])
            ->from(CustomerModel::tableName())
            ->where(['between', 'ngay_tao', $data['datetimeStamp']['startDate'], $data['datetimeStamp']['endDate']])
            ->groupBy('ngay_tao');
        $res['total_customer'] = $this->buildDataForChart($query, 'Tổng Khách Được Tạo');


        /* //Đặt hẹn
         //$sql = "select date_lichhen_new ,count( date_lichhen_new ) as tong from dep365_customer_online_dathen_time  where date_lichhen_new between 1572541200 and 1573837200 group by date_lichhen_new";
         $query = (new  Query())
             ->select(['date_lichhen_new as name', 'count(date_lichhen_new) as value'])
             ->from('dep365_customer_online_dathen_time')
             ->where(['between', 'date_lichhen_new', $data['datetimeStamp']['startDate'], $data['datetimeStamp']['endDate']])
             ->groupBy('date_lichhen_new');
         $res['dathen'] = $this->buildDataForChart($query, 'Đặt Hẹn');*/

        //$sql = "select ngay_tao as label, count(ngay_tao) from dep365_customer_online where status=2 and ngay_tao between 1572541200 and 1575046800 group by ngay_tao";
        $query = (new  Query())
            ->select(['ngay_tao as name', ' count(ngay_tao) as value'])
            ->from(CustomerModel::tableName())
            ->where(['status' => 1])
            ->andWhere(['between', 'ngay_tao', $data['datetimeStamp']['startDate'], $data['datetimeStamp']['endDate']])
            ->groupBy('ngay_tao');
        $res['dathen'] = $this->buildDataForChart($query, 'Đặt Hẹn');

        //$sql = "select ngay_tao as label, count(ngay_tao) from dep365_customer_online where status=2 and ngay_tao between 1572541200 and 1575046800 group by ngay_tao";
        $query = (new  Query())
            ->select(['ngay_tao as name', ' count(ngay_tao) as value'])
            ->from(CustomerModel::tableName())
            ->where(['status' => 2])
            ->andWhere(['between', 'ngay_tao', $data['datetimeStamp']['startDate'], $data['datetimeStamp']['endDate']])
            ->groupBy('ngay_tao');
        $res['dathenfail'] = $this->buildDataForChart($query, 'Đặt Hẹn Fail');


        //end đặt hẹn--------------------------


        //đến không
        //đến dat_hen = 1
        //lấy customer_come_date
//        $sql = "SELECT customer_come_date as label, COUNT(customer_come_date) as value FROM dep365_customer_online WHERE status=1 AND dat_hen=1 AND customer_come_date BETWEEN 1572541200 AND 1575133199  group by customer_come_date";
        $query = (new  Query())
            ->select(['customer_come_date as name', 'count(customer_come_date) as value'])
            ->from(CustomerModel::tableName())
            ->where(['between', 'customer_come_date', $data['datetimeStamp']['startDate'], $data['datetimeStamp']['endDate']])
            ->andWhere(['status' => 1, 'dat_hen' => 1])
            ->andWhere(['is_customer_who' => 1])
            ->groupBy('customer_come_date');
        $res['den'] = $this->buildDataForChart($query, 'Đến');


        //không đến dat_hen=2
        // Lấy theo date_lichhen
//        $sql = "SELECT date_lichhen as label, COUNT(date_lichhen) as valcreated_atue FROM dep365_customer_online WHERE status=1 AND dat_hen=2 AND date_lichhen BETWEEN 1572541200 AND 1575133199  group by date_lichhen";
        $query = (new  Query())
            ->select(['date_lichhen as name', 'count(date_lichhen) as value'])
            ->from(CustomerModel::tableName())
            ->where(['between', 'date_lichhen', $data['datetimeStamp']['startDate'], $data['datetimeStamp']['endDate']])
            ->andWhere(['dat_hen' => 2])
            ->andWhere(['status' => 1])
            ->andWhere(['is_customer_who' => 1])
            ->groupBy('date_lichhen');
        $res['khongden'] = $this->buildDataForChart($query, 'Không Đến');
        //đến không --------------------
        // Làm không
        //Làm
//        $sql = "SELECT COUNT(*) FROM dep365_customer_online co WHERE co.status=1 AND co.dat_hen=1 AND co.customer_come_time_to IN (1, 3, 5, 6) AND co.customer_come_date BETWEEN 1572541200 AND 1575133199";
        $query = (new  Query())
            ->select(['customer_come_date as name', 'count(customer_come_date) as value'])
            ->from(CustomerModel::tableName())
            ->where(['between', 'customer_come_date', $data['datetimeStamp']['startDate'], $data['datetimeStamp']['endDate']])
            ->andWhere(['in', 'customer_come_time_to', [1, 3, 5, 6]])
            ->groupBy('customer_come_date');
        $res['lam'] = $this->buildDataForChart($query, 'Làm');


        // Không
        $query = (new  Query())
            ->select(['customer_come_date as name', 'count(customer_come_date) as value'])
            ->from(CustomerModel::tableName())
            ->where(['between', 'customer_come_date', $data['datetimeStamp']['startDate'], $data['datetimeStamp']['endDate']])
            ->andWhere(['not in', 'customer_come_time_to', [1, 3, 5, 6]])
            ->groupBy('customer_come_date');
        $res['khonglam'] = $this->buildDataForChart($query, 'Không Làm');


        // Làm không  ------------------------------------


        return $res;
    }

    public function buildDataForChart($query, $label)
    {
        $data['label'] = !empty($label) ? $label : 'Default Label';
        $data['debugsql'] = $query->createCommand()->rawSql;
        $query = $query->all();
        $data['data'] = $this->convertArrayDataToChartData($query);
        $data['data'] = $this->fillEmptyDateData($data['data']);
        return $data;
    }


    public function fillEmptyDateData($data)
    {
        if (!empty($this->dateRange)) {
            foreach ($this->dateRange as $value) {
                $data[$value] = !empty($data[$value]) ? $data[$value] : 0;
            }
            return $data;
        }
    }

    public function convertArrayDataToChartData($data)
    {
        if (!empty($data)) {
            foreach ($data as $value) {
                $res[$value['name']] = $value['value'];
            }
            return $res;
        }
        return [];
    }

    private function getTableOverview()
    {
        $this->receiveDataForm();

        //Table Overview
        switch ($this->data['filter_dimension']) {
            case 'thanhpho':
                $query = (new Query())->select(['d.province as id', 'p.name as name'])->from([CustomerModel::tableName() . ' d', 'province p'])
                    ->where(['between', 'customer_come_date', $this->data['datetimeStamp']['startDate'], $this->data['datetimeStamp']['endDate']])
                    ->andWhere('p.id = d.province')
                    ->andWhere(['d.status' => 1])
                    ->groupBy(['d.province', 'p.name']);
                $this->data['filter']['sql'] = $query->createCommand()->rawSql;

                $this->data['filter']['data'] = $query->all();
                break;
            case 'dotuoi':
                $this->data['filter']['data'] =
                    [['id' => 0, 'name' => '-18 tuổi'],
                        ['id' => 1, 'name' => '19-36 tuổi'],
                        ['id' => 2, 'name' => '36+ tuổi']];
                break;
            case 'gioitinh':
                $this->data['filter']['data'] =
                    [
                        ['id' => 0, 'name' => 'Nam'],
                        ['id' => 1, 'name' => 'Nữ']
                    ];
                break;
            case 'coso':
//                $sql = 'select distinct co_so as id, concat("Cơ Sở ",co_so) as name from dep365_customer_online where co_so is not null';
                $query = (new Query())->select('co_so as id')->distinct()
                    ->addSelect(['concat("Cơ Sở ",co_so) as name'])
                    ->from([CustomerModel::tableName()])
                    ->where(['between', 'customer_come_date', $this->data['datetimeStamp']['startDate'], $this->data['datetimeStamp']['endDate']])
                    ->andWhere('co_so is not null')
                    ->groupBy(['co_so']);
                $this->data['filter']['sql'] = $query->createCommand()->rawSql;

                $this->data['filter']['data'] = $query->all();
                break;
            case 'sanpham':
//                $sql = "SELECT ps.id,ps.name FROM phong_kham_san_pham ps, phong_kham_don_hang_w_order po, phong_kham_don_hang_w_thanh_toan ptt WHERE (`ptt`.`ngay_tao` BETWEEN 1572541200 AND 1575046800) AND (po.id = ptt.phong_kham_don_hang_id) and ps.id=po.san_pham";
                $query = (new Query())->select('ps.id, ps.name')->distinct()
                    ->from('phong_kham_san_pham ps, phong_kham_don_hang_w_order po, phong_kham_don_hang_w_thanh_toan ptt')
                    ->where(['between', 'ptt.ngay_tao', $this->data['datetimeStamp']['startDate'], $this->data['datetimeStamp']['endDate']])
                    ->andWhere('po.id = ptt.phong_kham_don_hang_id')
                    ->andWhere('ps.id = po.san_pham')
                    ->groupBy('ps.id, ps.name');
                $this->data['filter']['sql'] = $query->createCommand()->rawSql;
                $query = $query->all();
                $this->data['filter']['data'] = $query;
                break;
            case 'dichvu':
//                $sql = "SELECT ps.id,ps.name FROM phong_kham_dich_vu pd, phong_kham_don_hang_w_order po, phong_kham_don_hang_w_thanh_toan ptt WHERE (`ptt`.`ngay_tao` BETWEEN 1572541200 AND 1575046800) AND (po.id = ptt.phong_kham_don_hang_id) and ps.id=po.san_pham";
                $query = (new Query())->select('pd.id, pd.name')->distinct()
                    ->from('phong_kham_dich_vu pd, phong_kham_don_hang_w_order po, phong_kham_don_hang_w_thanh_toan ptt')
                    ->where(['between', 'ptt.ngay_tao', $this->data['datetimeStamp']['startDate'], $this->data['datetimeStamp']['endDate']])
                    ->andWhere('po.id = ptt.phong_kham_don_hang_id')
                    ->andWhere('pd.id = po.dich_vu')
                    ->groupBy('pd.id, pd.name');
                $this->data['filter']['sql'] = $query->createCommand()->rawSql;
                $query = $query->all();
                $this->data['filter']['data'] = $query;
                break;
            case 'fanpage':
                $fanpage = Dep365CustomerOnlineFanpage::find()->all();
                $this->data['filter']['data'] = $fanpage;
                break;
            default:
                $query = (new Query())->select(['d.province as id', 'p.name as name'])->from([CustomerModel::tableName() . ' d', 'province p'])
                    ->where(['between', 'customer_come_date', $this->data['datetimeStamp']['startDate'], $this->data['datetimeStamp']['endDate']])
                    ->andWhere('p.id = d.province')
                    ->andWhere(['d.status' => 1])
                    ->groupBy(['d.province', 'p.name']);
                $this->data['filter']['sql'] = $query->createCommand()->rawSql;

                $this->data['filter']['data'] = $query->all();
                break;
        }

        foreach ($this->data['filter']['data'] as $value) {
            $res = $this->getDataTableOverview($value['id']);
            $this->data['filter']['dataSet'][] = array($value['name'], $res['dathen'], $res['khongdat'], $res['den'], $res['khongden'], $res['lam'], $res['khonglam'], $res['doanhthu']);
//            $this->data['filter']['sql'][] = is_array($res['sql']) ? $res['sql'] : [];
        }
        //end table overview-------------------------
        return $this->renderPartial(
            'table_overview',
            ['data' => $this->data, 'dateRange' => $this->dateRange]
        );
    }

    public function getDataTableOverview($value)
    {
        $arr_nofilter = array('dichvu', 'sanpham');
        $str_no = '0';
        $res = array('dathen' => $str_no, 'khongdat' => $str_no, 'den' => $str_no, 'khongden' => $str_no, 'lam' => $str_no, 'khonglam' => $str_no);
        if (!in_array($this->data['filter_dimension'], $arr_nofilter)) {
            $query = (new Query())->select(['count(*) as dathen'])
                ->from(CustomerModel::tableName())
                ->where(['between', 'ngay_tao', $this->datetimeStamp['startDate'], $this->datetimeStamp['endDate']])
                ->andWhere(['status' => 1])->limit(1);
            $query = $this->getDataFilterTableOverview($query, $value);
            $res['sql']['dathen'] = $query->createCommand()->rawSql;
            $query = $query->one();
            $res['dathen'] = array_shift($query);

            $query = (new  Query())
                ->select(['count(*) as dathen'])
                ->from(CustomerModel::tableName())
                ->where(['between', 'ngay_tao', $this->datetimeStamp['startDate'], $this->datetimeStamp['endDate']])
                ->andWhere(['status' => 2])->limit(1);
            $query = $this->getDataFilterTableOverview($query, $value);
            $res['sql']['khongdat'] = $query->createCommand()->rawSql;
            $query = $query
                ->one();
            $res['khongdat'] = array_shift($query);

            //den
            $query = (new Query())->select(['count(*) as dathen'])
                ->from(CustomerModel::tableName())
                ->where(['between', 'date_lichhen', $this->datetimeStamp['startDate'], $this->datetimeStamp['endDate']])
                ->andWhere(['status' => 1, 'dat_hen' => 1])->limit(1);
            $query = $this->getDataFilterTableOverview($query, $value);
            $res['sql']['den'] = $query->createCommand()->rawSql;
            $query = $query
                ->one();
            $res['den'] = array_shift($query);


            $query = (new Query())->select(['count(*) as dathen'])
                ->from(CustomerModel::tableName())
                ->where(['between', 'date_lichhen', $this->datetimeStamp['startDate'], $this->datetimeStamp['endDate']])
                ->andWhere(['status' => 1, 'dat_hen' => 2])->limit(1);
            $query = $this->getDataFilterTableOverview($query, $value);
            $res['sql']['khongden'] = $query->createCommand()->rawSql;
            $query = $query->one();
            $res['khongden'] = array_shift($query);

            $query = (new  Query())
                ->select(['count(*) as value'])
                ->from(CustomerModel::tableName())
                ->where(['between', 'customer_come_date', $this->datetimeStamp['startDate'], $this->datetimeStamp['endDate']])
                ->andWhere(['in', 'customer_come_time_to', [1, 3, 5, 6]])->limit(1);
            $query = $this->getDataFilterTableOverview($query, $value);
            $res['sql']['lam'] = $query->createCommand()->rawSql;
            $query = $query->one();
            $res['lam'] = array_shift($query);
            // Không
            $query = (new  Query())
                ->select(['count(*) as value'])
                ->from(CustomerModel::tableName())
                ->where(['between', 'customer_come_date', $this->datetimeStamp['startDate'], $this->datetimeStamp['endDate']])
                ->andWhere(['not in', 'customer_come_time_to', [1, 3, 5, 6]])->limit(1);
            $query = $this->getDataFilterTableOverview($query, $value);
            $res['sql']['khonglam'] = $query->createCommand()->rawSql;
            $query = $query->one();
            $res['khonglam'] = array_shift($query);
        } else {
            $this->data['filter']['hiden_column'] = [1, 2, 3, 4, 5, 6];
        }

//        $query = "select sum(ptt.tien_thanh_toan) as tienthanhtoan from phong_kham_don_hang_w_thanh_toan ptt, dep365_customer_online d,province p where d.id = ptt.customer_id and p.id=d.province and p.id=" . $value . " and d.ngay_tao between " . $this->datetimeStamp['startDate'] . " and " . $this->datetimeStamp['endDate'];
        $query = (new Query())->select(' sum(phong_kham_don_hang_w_thanh_toan.tien_thanh_toan) as tienthanhtoan ')
            ->from('phong_kham_don_hang_w_thanh_toan,dep365_customer_online,province')
            ->where('dep365_customer_online.id = phong_kham_don_hang_w_thanh_toan.customer_id')
            ->andWhere(' province.id = dep365_customer_online.province')
            ->andWhere(['between', 'dep365_customer_online.ngay_tao', $this->datetimeStamp['startDate'], $this->datetimeStamp['endDate']]);
        $query = $this->getDataFilterTableOverview($query, $value);
        $res['sql']['doanhthu'] = $query->createCommand()->rawSql;

        $query = $query->limit(1)
            ->one();
//        $query = \Yii::$app->db->createCommand($query);
//        $query = $query->queryOne();
        $res['doanhthu'] = number_format(array_shift($query), 0, '', ',');

        return $res;
    }

    public function getDataFilterTableOverview(Query $query, $value)
    {
        switch ($this->data['filter_dimension']) {
            case 'thanhpho':
                $query = $query->andFilterWhere(['province' => $value]);
                break;
            case 'dotuoi':
                $query = $query->andWhere('birthday is not null');
                switch ($value) {
                    case 0:
                        $query = $query->andWhere('(year(curdate()) - right(birthday,4)) < 18');
                        break;
                    case 1:
                        $query = $query->andWhere(['between', '(year(curdate()) - right(birthday,4))', 18, 36]);
                        break;
                    case 2:
                        $query = $query->andWhere('(year(curdate()) - right(birthday,4)) > 36');
                        break;
                }
                break;
            case 'gioitinh':
                $query = $query->andFilterWhere(['sex' => (string)$value]);
                break;
            case 'coso':
                $query = $query->andFilterWhere(['dep365_customer_online.co_so' => (string)$value]);
                break;
            case 'dichvu':
                $query = (new Query())->select('sum(ptt.tien_thanh_toan)')->distinct()
                    ->from('phong_kham_dich_vu pd, phong_kham_don_hang_w_order po, phong_kham_don_hang_w_thanh_toan ptt')
                    ->where(['between', 'ptt.ngay_tao', $this->data['datetimeStamp']['startDate'], $this->data['datetimeStamp']['endDate']])
                    ->andWhere('po.id = ptt.phong_kham_don_hang_id')
                    ->andWhere('pd.id = po.dich_vu')
                    ->andWhere(['po.dich_vu' => $value])->limit(1);
                break;
            case 'sanpham':
                $query = (new Query())->select('sum(ptt.tien_thanh_toan)')->distinct()
                    ->from('phong_kham_san_pham ps, phong_kham_don_hang_w_order po, phong_kham_don_hang_w_thanh_toan ptt')
                    ->where(['between', 'ptt.ngay_tao', $this->data['datetimeStamp']['startDate'], $this->data['datetimeStamp']['endDate']])
                    ->andWhere('po.id = ptt.phong_kham_don_hang_id')
                    ->andWhere('ps.id = po.san_pham')
                    ->andWhere(['po.san_pham' => $value])->limit(1);
                break;
            case 'fanpage':
                $query = $query->andWhere(['face_fanpage' => $value]);
                break;
            default:
                $query = $query->andFilterWhere(['province' => $value]);
                break;
        }

        return $query;
    }
}
