<?php

namespace backend\modules\report\controllers;

use backend\components\MyController;
use backend\models\doanhthu\ThanhToanModel;
use backend\modules\clinic\models\PhongKhamDonHang;
use backend\modules\clinic\models\PhongKhamDonHangWOrder;
use backend\modules\clinic\models\PhongKhamDonHangWThanhToan;
use backend\modules\clinic\models\PhongKhamSanPham;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\customer\models\Dep365CustomerOnlineDichVu;
use backend\modules\customer\models\Dep365CustomerOnlineFanpage;
use backend\modules\location\models\Province;
use backend\modules\setting\models\Dep365CoSo;
use common\models\User;
use yii\db\Query;
use yii\web\Response;

class DoanhThuController extends MyController
{
    private $data_subfilter;
    private $subfilter_fanpage;
    private $subfilter_coso;
    private $subfilter_sanpham;
    private $subfilter_dichvu;
    private $subfilter_directsale;
    private $subfilter_onlinesale;

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionGetdata()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $start_date_report = isset($_GET['startDateReport']) ? ($_GET['startDateReport']) : date('d/m/Y', time());
        $end_date_report = isset($_GET['endDateReport']) ? ($_GET['endDateReport']) : date('d/m/Y', time());
        $compare_kind = isset($_GET['compare_kind']) ? $_GET['compare_kind'] : 0;

        $result = $this->getData($start_date_report, $end_date_report, $compare_kind);

        return $result;
    }

    public function getData($start_date_report, $end_date_report, $compare_kind)
    {
//        $start_date_report = "1/1/2018";
//        $end_date_report = "1/10/2019";

        $data = [];
        $startDate = $this->formatTimestampToDateTimeObject($start_date_report);
        $endDate = $this->formatTimestampToDateTimeObject($end_date_report);
        //prevent date modify modify all refenrence variable
        $dateRangeTemp = [
            $this->formatTimestampToDateTimeObject($start_date_report),
            $this->formatTimestampToDateTimeObject($end_date_report)
        ];
        $dateRange = [$startDate, $endDate];
        $data['debug'] = $dateRange;

        $data['dataCompareKey'] = $compare_kind;
        $data['dataDate'] = $this->getListDateRange($startDate, $endDate);
        $data['dataSet']['doanhthu']['label'] = 'Doanh Thu';
        $data['dataSet']['doanhthu']['first'] = [];


        //--------------------- data doanh thu first
        $data['dataSet']['doanhthu']['first'] = $this->getDoanhThuDataSet(
            $data['dataSet']['doanhthu']['first'],
            $dateRange,
            $this->buidQuery($dateRange)
        );
        //----------------------------end
        //---------------------------------- data doanhthu second for compare
        if ($compare_kind != 0) {
            $data['dataSet']['doanhthu']['second'] = [];
            $this->modifyRangeDate($dateRangeTemp, $compare_kind);
            $data['dataSet']['doanhthu']['second'] = $this->getDoanhThuDataSet(
                $data['dataSet']['doanhthu']['second'],
                $dateRangeTemp,
                $this->buidQuery($dateRangeTemp)
            );
        }
        //--------------------------------end

        // ----------- query tong tien, thanh toan, no

//        $sql = 'select p.name, sum(ph.thanh_tien-ph.chiet_khau) as thanh_tien, sum(tt.tien_thanh_toan) as tien_thuc_thu from dep365_customer_online d, province p, phong_kham_don_hang ph ,phong_kham_don_hang_w_thanh_toan tt where tt.phong_kham_don_hang_id = ph.id and d.province=p.id and ph.customer_id=d.id and ph.thanh_tien > 0 and tt.tam_ung in (0,1) and tt.ngay_tao between ' . $dateRange[0]->getTimestamp() . ' and ' . $dateRange[1]->getTimestamp() . ' group by p.name order by thanh_tien DESC';
        /*$query = (new Query())->select('p.name, sum(ph.thanh_tien-ph.chiet_khau) as thanh_tien,
        sum(tt.tien_thanh_toan) as tien_thuc_thu')
            ->from('dep365_customer_online d,
            province p, phong_kham_don_hang ph ,
            phong_kham_don_hang_w_thanh_toan tt')
            ->where('tt.phong_kham_don_hang_id = ph.id and d.province = p.id and ph.customer_id = d.id')
            ->andWhere(['>', 'ph.thanh_tien', 0])
            ->andWhere(['in', 'tt.tam_ung', [0, 1]])
            ->andWhere(['between', 'tt.ngay_tao', $dateRange[0]->getTimestamp(), $dateRange[1]->getTimestamp() + 86399])
            ->andFilterWhere(['d.co_so' => $this->subfilter_coso])
            ->groupBy('p.name')->orderBy('thanh_tien DESC');
//        $query = \Yii::$app->db->createCommand($sql);
        $data['debug_table'] = $query->createCommand()->rawSql;
        $query = $query->all();*/

        /*
         * Tâm fix code
         * Tính tổng thu trên đơn hàng theo thời gian
         */
        $query_thu_total = PhongKhamDonHang::find();
        $query_thu_total->select([
            Province::tableName() . '.id',
            Province::tableName() . '.name',
            'SUM(' . PhongKhamDonHang::tableName() . '.thanh_tien-' . PhongKhamDonHang::tableName() . '.chiet_khau) as thanh_tien'
        ]);
        $query_thu_total->leftJoin(Dep365CustomerOnline::tableName(), PhongKhamDonHang::tableName() . '.customer_id=' . Dep365CustomerOnline::tableName() . '.id');
        $query_thu_total->leftJoin(Province::tableName(), Dep365CustomerOnline::tableName() . '.province=' . Province::tableName() . '.id');
        $query_thu_total->where(['BETWEEN', PhongKhamDonHang::tableName() . '.created_at', $dateRange[0]->getTimestamp(), $dateRange[1]->getTimestamp() + 86399]);
        $query_thu_total->andFilterWhere([PhongKhamDonHang::tableName() . '.co_so' => $this->subfilter_coso]);
        $query_thu_total->andFilterWhere([Dep365CustomerOnline::tableName() . '.id_dich_vu' => $this->subfilter_dichvu]);
        $query_thu_total->andFilterWhere([Dep365CustomerOnline::tableName() . '.directsale' => $this->subfilter_directsale]);
        $query_thu_total->andFilterWhere([Dep365CustomerOnline::tableName() . '.permission_user' => $this->subfilter_onlinesale]);
        $query_thu_total->andFilterWhere([Dep365CustomerOnline::tableName() . '.face_fanpage' => $this->subfilter_fanpage]);
        $query_thu_total->groupBy(Province::tableName() . '.id, ' . Province::tableName() . '.name');
        $query_thu_total->indexBy('id');
        $query_thu_total = $query_thu_total->all();

        /*
         * Tính tổng thực thu trên đơn hàng theo thời gian
         */
        $tien_thu = self::tongThucThu();
        $tien_thu->andWhere(['BETWEEN', PhongKhamDonHang::tableName() . '.ngay_tao', $dateRange[0]->getTimestamp(), $dateRange[1]->getTimestamp() + 86399]);
        $tien_thu = $tien_thu->all();

        $tien_thuc_thu = self::tongThucThu();
        $tien_thuc_thu->andWhere(['BETWEEN', PhongKhamDonHangWThanhToan::tableName() . '.ngay_tao', $dateRange[0]->getTimestamp(), $dateRange[1]->getTimestamp() + 86399]);
        $tien_thuc_thu = $tien_thuc_thu->all();

        $doanhthuOutput = [];
        foreach ($tien_thu as $idProvince => $value) {
            if (array_key_exists($idProvince, $query_thu_total)) {
                $doanhthuOutput[$idProvince] = [
                    'name' => $query_thu_total[$idProvince]->name,
                    'tong_thu_hd' => $query_thu_total[$idProvince]->thanh_tien,
                    'thuc_thu_hd' => $value->tien_thuc_thu,
                    'tong_no_hd' => $query_thu_total[$idProvince]->thanh_tien - $value->tien_thuc_thu,
                ];
            }
        }
        foreach ($tien_thuc_thu as $idProvince => $value) {
            if (array_key_exists($idProvince, $query_thu_total) && array_key_exists($idProvince, $doanhthuOutput)) {
                $doanhthuOutput[$idProvince]['tong_no_hd_cu'] = $value->tien_thuc_thu - $doanhthuOutput[$idProvince]['thuc_thu_hd'];
                $doanhthuOutput[$idProvince]['tong_thuc_thu'] = $value->tien_thuc_thu;
            }
        }

        usort($doanhthuOutput, function ($a, $b) {
            if ($a['tong_thu_hd'] == $b['tong_thu_hd']) return 0;
            return $a['tong_thu_hd'] < $b['tong_thu_hd'] ? 1 : -1;
        });

        //---------------------------------data pie
        $i = 0;
        $sum_khac = 0;
        foreach ($doanhthuOutput as $value) {
            if ($i < 4) {
                $data['dataPie']['name'][] = $value['name'];
                $data['dataPie']['thuc_thu_hd'][] = $value['thuc_thu_hd'];
                $i++;
            } else {
                $sum_khac += $value['thuc_thu_hd'];
            }
        }
        $data['dataPie']['name'][] = 'Khác';
        $data['dataPie']['thuc_thu_hd'][] = $sum_khac;

//        $data['table_html'] = $this->renderPartial('table_overview', ['query' => $query, 'dateRange' => $dateRange, 'co_so' => isset($this->subfilter_coso) ? $this->subfilter_coso : '']);
        $data['table_html'] = $this->renderPartial('table_overview', ['doanhthuOutput' => $doanhthuOutput, 'dateRange' => $dateRange, 'co_so' => isset($this->subfilter_coso) ? $this->subfilter_coso : '']);

        //-----------------end
        $data['get'] = \Yii::$app->request->get();

        return $data;
    }

    protected function tongThucThu()
    {
        $query = PhongKhamDonHangWThanhToan::find();
        $query->select([
            Province::tableName() . '.id',
            Province::tableName() . '.name',
            'SUM(' . PhongKhamDonHangWThanhToan::tableName() . '.tien_thanh_toan) as tien_thuc_thu'
        ]);
        $query->leftJoin(PhongKhamDonHang::tableName(), PhongKhamDonHang::tableName() . '.id=' . PhongKhamDonHangWThanhToan::tableName() . '.phong_kham_don_hang_id');
        $query->leftJoin(Dep365CustomerOnline::tableName(), Dep365CustomerOnline::tableName() . '.id=' . PhongKhamDonHangWThanhToan::tableName() . '.customer_id');
        $query->leftJoin(Province::tableName(), Province::tableName() . '.id=' . Dep365CustomerOnline::tableName() . '.province');
        $query->where(['IN', PhongKhamDonHangWThanhToan::tableName() . '.tam_ung', [ThanhToanModel::THANH_TOAN, ThanhToanModel::DAT_COC]]);
        $query->andFilterWhere([PhongKhamDonHangWThanhToan::tableName() . '.co_so' => $this->subfilter_coso]);
        $query->andFilterWhere([Dep365CustomerOnline::tableName() . '.id_dich_vu' => $this->subfilter_dichvu]);
        $query->andFilterWhere([Dep365CustomerOnline::tableName() . '.directsale' => $this->subfilter_directsale]);
        $query->andFilterWhere([Dep365CustomerOnline::tableName() . '.permission_user' => $this->subfilter_onlinesale]);
        $query->andFilterWhere([Dep365CustomerOnline::tableName() . '.face_fanpage' => $this->subfilter_fanpage]);
        $query->groupBy(Province::tableName() . '.id, ' . Province::tableName() . '.name');
        $query->indexBy('id');

        return $query;
    }


    /**
     * Build Query
     * return $query
     */
    public function buidQuery($dateRange)
    {
        $query = (new \yii\db\Query())->select(['p.ngay_tao', 'sum(p.tien_thanh_toan) as thanh_tien'])
            ->from([
                PhongKhamDonHangWThanhToan::tableName() . ' p',
            ])
            ->join(
                'LEFT JOIN',
                Dep365CustomerOnline::tableName() . ' d',
                'p.customer_id=d.id'
            )
            ->where([
                'between',
                'p.ngay_tao',
                $dateRange[0]->getTimestamp(),
                $dateRange[1]->getTimestamp()
            ])
            ->andWhere('d.id = p.customer_id')
            ->groupBy('p.ngay_tao');

        $this->data_subfilter = $data_sub_filter = \Yii::$app->request->get('data_sub_filter');

        $data_sub_filter = \Yii::$app->request->get('data_sub_filter');

        foreach ($data_sub_filter as $sub_filter) {
            if ($sub_filter['value'] != '' && $sub_filter['value'] != null) {
                $filter_key = $sub_filter['name'];
                $subfilter = (int)$sub_filter['value'];
                switch ($filter_key) {
                    case 'fanpage':
                        $this->subfilter_fanpage = $subfilter;
                        $query = $query->andFilterWhere(['d.face_fanpage' => $subfilter]);
                        break;
                    case 'coso':
                        //Todo::Temp gain subfilter coso for table query; Need Fix.
                        $this->subfilter_coso = $subfilter;
                        $query = $query->andFilterWhere(['d.co_so' => $subfilter]);
                        break;
                    case 'sanpham':
//                  select o.ngay_tao, sum(o.thanh_tien - o.chiet_khau_order)
//                  as thanh_tien from phong_kham_don_hang_w_order o,
//                  phong_kham_san_pham sp where o.san_pham = sp.id and sp.id=2 group by o.ngay_tao
                        $this->subfilter_sanpham = $subfilter;
                        $query->join(
                            'LEFT JOIN',
                            PhongKhamDonHangWOrder::tableName() . ' o',
                            'p.phong_kham_don_hang_id=o.phong_kham_don_hang_id'
                        )
                            ->andWhere('o.san_pham=' . $subfilter);
                        break;
                    case 'dichvu':
//                  select o.ngay_tao, sum(o.thanh_tien - o.chiet_khau_order)
//                  as thanh_tien from phong_kham_don_hang_w_order o,
//                  phong_kham_san_pham sp where o.san_pham = sp.id and sp.id=2 group by o.ngay_tao
                        $this->subfilter_dichvu = $subfilter;
                        $query->join(
                            'LEFT JOIN',
                            PhongKhamDonHangWOrder::tableName() . ' o',
                            'p.phong_kham_don_hang_id=o.phong_kham_don_hang_id'
                        )
                            ->andWhere('o.dich_vu=' . $subfilter);
                        break;
                    case 'direct_sale':
                        $this->subfilter_directsale = $subfilter;
                        $query = $query->andFilterWhere(['d.directsale' => $subfilter]);

                        break;
                    case 'online_sale':
                        $this->subfilter_onlinesale = $subfilter;
                        $query = $query->andFilterWhere(['d.permission_user' => $subfilter]);
                        break;
                }
            }
        }

        return $query;
    }

    public function actionGetsubfilter()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $results = [];

        if (isset($_GET['subfilter'])) {
            $key = $_GET['subfilter'];
            switch ($key) {
                case 'fanpage':
                    $list = Dep365CustomerOnlineFanpage::find()->asArray()->all();
                    foreach ($list as $item) {
                        $results[] = ['id' => $item['id'], 'text' => $item['name']];
                    }
                    break;
                case 'coso':
                    $list = Dep365CoSo::find()->asArray()->all();
                    foreach ($list as $item) {
                        $results[] = ['id' => $item['id'], 'text' => $item['name']];
                    }
                    break;
                case 'sanpham':
                    $list = (new \yii\db\Query())
                        ->select(['id', 'name'])
                        ->from(PhongKhamSanPham::tableName())
                        ->all();
                    foreach ($list as $item) {
                        $results[] = ['id' => $item['id'], 'text' => $item['name']];
                    }
                    break;
                case 'dichvu':
                    $list = (new \yii\db\Query())
                        ->select(['id', 'name'])
//                        ->from(PhongKhamDichVu::tableName())
                        ->from(Dep365CustomerOnlineDichVu::tableName())
                        ->all();
                    foreach ($list as $item) {
                        $results[] = ['id' => $item['id'], 'text' => $item['name']];
                    }
                    break;
                case 'direct_sale':
                    $list = User::getNhanVienTuDirectSale();
                    foreach ($list as $item) {
                        $results[] = ['id' => $item['id'], 'text' => $item['fullname']];
                    }
                    break;
                case 'online_sale':
                    $list = User::getNhanVienTuVanOnline([User::STATUS_ACTIVE]);
                    foreach ($list as $item) {
                        $results[] = ['id' => $item['id'], 'text' => $item['fullname']];
                    }
                    break;
            }
        }

        $array_subfilter = ['results' => $results];

        return $array_subfilter;
    }


    public function getDoanhThuDataSet($data, $dateList, Query $query)
    {
        $dateList = $this->getListDateRange($dateList[0], $dateList[1]);

        if (!empty($query->select)) {
            $data['sql'] = $query->createCommand()->getRawSql();
            $query = $query->all();

//        $query = \Yii::$app->db->createCommand('select ngay_tao, sum(thanh_tien - chiet_khau) as thanh_tien from ' . PhongKhamDonHang::tableName() . ' where ngay_tao between ' . $startDate->getTimestamp() . ' and ' . $endDate->getTimestamp() . ' group by ngay_tao')->queryAll();

            foreach ($query as $i => $val) {
                $data['value'][$val['ngay_tao']] = $val['thanh_tien'];
            }

            foreach ($dateList as $value) {
                if (!isset($data['value'][$value])) {
                    $data['value'][$value] = 0;
                }
            }
        }

        return $data;
    }

    public function getListDateRange($start_date_report, $end_date_report)
    {
        //86400 = 1 day
        $date = [];
        for ($i = (int)$start_date_report->getTimestamp(); $i <= (int)$end_date_report->getTimestamp(); $i = $i + 86400) {
            $date[] = $i;
        }

        return $date;
    }

    public function formatTimestampToDateTimeObject($date)
    {
        $date = explode('/', $date);
        $datetime1 = date_create($date[2] . '-' . $date[1] . '-' . $date[0]);

        return $datetime1;
    }


    /*
     * Get compare range date
     * */
    public function modifyRangeDate($dateList, $compare_kind)
    {

        // Difference only in months
        switch ($compare_kind) {
            case 1:
                $interval = date_diff($dateList[0], $dateList[1]);
                date_modify($dateList[0], '-' . ($interval->d + 1) . ' day');
                date_modify($dateList[1], '-' . ($interval->d + 1) . ' day');
                break;
            case 2:
                date_modify($dateList[0], "-1 month");
                date_modify($dateList[1], "-1 month");
                break;
        }

//        echo $interval->format('%R%a days') . "\n";
//      $interval->d     // day diff number
        return $dateList;
    }
}
