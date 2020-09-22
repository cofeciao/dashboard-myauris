<?php
namespace backend\modules\report\components;

use backend\modules\baocao\models\BaocaoLocation;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\customer\models\Dep365CustomerOnlineFailDathen;
use Yii;
use backend\modules\report\models\CustomerBaoCao;
use yii\base\Component;

class LyDoKhongDen extends Component
{
    public static function getDataOnline($from, $to, $id_online = null, $id_location = null, $fail_dathen = null, $id_page = null, $id_coso = null)
    {
        //test
//        $from = "1-1-2018";
//        $to = "30-10-2019";
        $from = strtotime($from);
        $to = strtotime($to);
        $listFailDatHen = Dep365CustomerOnlineFailDathen::getCustomerOnlineDatHenFailArray();

        $ngay_tao = $listStatusFail = $listAll = $list_Date_LyDo = $list_StatusFail_Date = [];

        $queryNgaytao = CustomerBaoCao::find()
//            ->select(['COUNT(*) AS id','date_lichhen','dat_hen_fail'])
            ->select(['COUNT(*) AS id','date_lichhen'])
            ->where(['between', 'date_lichhen', $from, $to])
            ->andWhere([ "dat_hen" => Dep365CustomerOnline::DAT_HEN_KHONG_DEN])
            ->andWhere(['dep365_customer_online.status' => Dep365CustomerOnline::STATUS_DH]);

        if ($id_online) {
            $queryNgaytao->andWhere(["=","permission_user",$id_online]);
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

        if ($id_page) {
            $queryNgaytao->andWhere(["=","face_fanpage",$id_page]);
        }

        if ($id_coso) {
            $queryNgaytao->andWhere(["=","co_so",$id_coso]);
        }

        if ($fail_dathen) {
            $queryNgaytao->andWhere(["=","dat_hen_fail",$fail_dathen]);
            $listFailDatHen = [$fail_dathen => $listFailDatHen[$fail_dathen]];
        }
        $dataAll = $queryNgaytao->groupBy(['date_lichhen'])->all();
        $list  = [];
        foreach ($dataAll as $item) {
            $date = date('d-m-Y', $item->date_lichhen);
            $count = $item->id;
            $ngay_tao[$date] = $date;
            $list[] = $count;
        }

        $enddata[] = [
            'name' => "Không đến",
            'type' => 'line',
            'stack'=> 'Total',
            'smooth' => true,
            'itemStyle' => [ 'normal' => [ 'areaStyle' => ['type' => 'default']]],
            'data' => $list,
        ];



        return [
            'result' => $enddata,
            'date' => array_values($ngay_tao),
            'lable' => ["Không đến"],
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
