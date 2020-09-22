<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 23-Apr-19
 * Time: 4:49 PM
 */

namespace backend\modules\report\components;

use backend\modules\customer\models\Dep365CustomerOnlineFailStatus;
use backend\modules\report\models\CustomerBaoCao;
use yii\base\Component;

class KhachHangFail extends Component
{
    public static function getDataOnline($from, $to, $page_online = null, $reason_fail = null)
    {
        //test
//        $from = "1-1-2019";
//        $to = "20-9-2019";
        $from = strtotime($from);
        $to = strtotime($to);

        $listReasonFail = Dep365CustomerOnlineFailStatus::getListOnlineStatusFailArray();
        $result = $ngay_tao = $listStatusFail = $listAll = $list_Date_StatusFail = $list_StatusFail_Date = [];

        $queryNgaytao = CustomerBaoCao::find()
            ->select(['COUNT(*) AS id','ngay_tao','status_fail'])
            ->where(['between', 'ngay_tao', $from, $to])
            ->andWhere(["=","status",2]);
        if ($page_online) {
            $queryNgaytao->andWhere(["=","face_fanpage",$page_online]);
        }
        if ($reason_fail) {
            $queryNgaytao->andWhere(["=","status_fail",$reason_fail]);
            $listReasonFail = [$reason_fail => $listReasonFail[$reason_fail]];
        }

        $dataAll = $queryNgaytao->groupBy(['ngay_tao','status_fail'])->all();
        foreach ($dataAll as $item) {
            $date = date('d-m-Y', $item->ngay_tao);
            $count = $item->id;
            $list_Date_StatusFail[$date][$item->status_fail] = $count;
            $ngay_tao[$date] = $date;
        }

        foreach ($listReasonFail as $key => $name_status_fail) {
            $arrForReason = [];
            foreach ($ngay_tao as $date) {
                if (isset($list_Date_StatusFail[$date][$key])) {
                    $arrForReason[$date] = $list_Date_StatusFail[$date][$key];
                } else {
                    $arrForReason[$date] = 0;
                }
            }
            $result[$key] = $arrForReason;
        }

        $enddata = $tableData = [];
        foreach ($listReasonFail as $key => $name_status_fail) {
            $list = array_values($result[$key]);
            $enddata[] = [
                'name' => $name_status_fail,
                'type' => 'line',
                'stack'=> 'Total',
                'smooth' => true,
                'itemStyle' => [ 'normal' => [ 'areaStyle' => ['type' => 'default']]],
                'data' => $list
            ];
            $tableData[] = [
                'name' => $name_status_fail,
                'value' => array_sum($list)
            ];
        }
        return [
            'result' => $enddata,
            'date' => array_values($ngay_tao),
            'lable' => array_values($listReasonFail),
            'tableData' => $tableData
        ];
    }
}
