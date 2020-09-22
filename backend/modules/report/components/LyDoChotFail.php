<?php
namespace backend\modules\report\components;

use backend\modules\baocao\models\BaocaoLocation;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\customer\models\Dep365CustomerOnlineCome;
use Yii;
use backend\modules\customer\models\Dep365CustomerOnlineFailStatus;
use backend\modules\report\models\CustomerBaoCao;
use yii\base\Component;

class LyDoChotFail extends Component
{
    public static function getDataOnline($from, $to, $direct_sale= null, $id_location = null, $reason_cancel = null, $id_page =null, $id_coso =null)
    {
        //test
//        $from = "1-1-2019";
//        $to = "20-10-2019";
        $from = strtotime($from);
        $to = strtotime($to);
        $listReasonCancel = Yii::$app->params["ly-do-khong-lam"];
        $result = $ngay_tao = $listStatusFail = $listAll = $list_Date_LyDo = $list_StatusFail_Date = [];

        $listCustomerComeName = Dep365CustomerOnlineCome::getCustomerOnlineComeArrayAccept();
        $listCustomerCome = array_keys($listCustomerComeName);

        $queryNgaytao = CustomerBaoCao::find()
            ->select(['COUNT(*) AS id','date_lichhen','ly_do_khong_lam'])
            ->where(['between', 'date_lichhen', $from, $to])
            ->andWhere(["NOT IN", "customer_come_time_to", $listCustomerCome])
            ->andWhere(["dat_hen" => Dep365CustomerOnline::DAT_HEN_DEN])
            ->andWhere(['dep365_customer_online.status' => Dep365CustomerOnline::STATUS_DH]);

        if ($direct_sale) {
            $queryNgaytao->andWhere(["=","directsale",$direct_sale]);
        }

        if ($id_page) {
            $queryNgaytao->andWhere(["=","face_fanpage",$id_page]);
        }

        if ($id_coso) {
            $queryNgaytao->andWhere(["=","co_so",$id_coso]);
        }

        if ($id_location) {
            $location = self::getLoc($id_location);
            if ($location === null) {
                $listProvince = [];
            } else {
                $listProvince = json_decode($location->list_province);
            }
            $queryNgaytao->andWhere(['in', 'province', $listProvince]);
        }

        if ($reason_cancel) {
            $queryNgaytao->andWhere(["=","ly_do_khong_lam",$reason_cancel]);
            $listReasonCancel = [$reason_cancel => $listReasonCancel[$reason_cancel]];
        }
        $dataAll = $queryNgaytao->groupBy(['date_lichhen','ly_do_khong_lam'])->all();
        foreach ($dataAll as $item) {
            if ($item->ly_do_khong_lam) {
                $date = date('d-m-Y', $item->date_lichhen);
                $count = $item->id;
                $list_Date_LyDo[$date][$item->ly_do_khong_lam] = $count;
                $ngay_tao[$date] = $date;
            }
        }

        foreach ($listReasonCancel as $key => $name_ly_do) {
            $arrForReason = [];
            foreach ($ngay_tao as $date) {
                if (isset($list_Date_LyDo[$date][$key])) {
                    $arrForReason[$date] = $list_Date_LyDo[$date][$key];
                } else {
                    $arrForReason[$date] = 0;
                }
            }
            $result[$key] = $arrForReason;
        }

        $enddata = $tableData = [];
        foreach ($listReasonCancel as $key => $name_ly_do) {
            $list = array_values($result[$key]);
            $enddata[] = [
                'name' => $name_ly_do,
                'type' => 'line',
                'stack'=> 'Total',
                'smooth' => true,
                'itemStyle' => [ 'normal' => [ 'areaStyle' => ['type' => 'default']]],
                'data' => $list
            ];
            $tableData[] = [
                'name' => $name_ly_do,
                'value' => array_sum($list)
            ];
        }

        return [
            'result' => $enddata,
            'date' => array_values($ngay_tao),
            'lable' => array_values($listReasonCancel),
            'tableData' => $tableData
        ];
    }

    /*
     * Lấy ra danh sách các tỉnh thành
     */
    protected static function getLoc($id_location)
    {
        return BaocaoLocation::find()->where(['id' => $id_location])->one();
    }
}
