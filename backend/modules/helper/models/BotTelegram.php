<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 27-Apr-19
 * Time: 9:38 AM
 */

namespace backend\modules\helper\models;

use backend\models\CustomerModel;
use backend\modules\clinic\models\PhongKhamDonHang;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\customer\models\Dep365CustomerOnlineCome;
use backend\modules\report\models\CustomerBaoCao;
use backend\modules\setting\models\Dep365CoSo;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use Yii;


class BotTelegram extends Model
{
    public static function sendMessage(){
//        $day = strtotime(date('9-10-2019'));
        $day = strtotime(date('d-m-Y'));
        $dayTo = $day + 86399;
        $strDay = date('d-m-Y');

        $doanhThuTrongNgay =  number_format(PhongKhamDonHang::sumTienThanhToan($day,$day), 0, ',', '.');

        $lichhen = CustomerBaoCao::find()
            ->select(['COUNT(*) AS id','co_so' ])
            ->where(['between', 'date_lichhen', $day, $dayTo ])
            ->andWhere(['dep365_customer_online.status' => Dep365CustomerOnline::STATUS_DH])
            ->andWhere(["in", "dat_hen" ,[Dep365CustomerOnline::DAT_HEN_KHONG_DEN,Dep365CustomerOnline::DAT_HEN_DEN] ])
            ->groupBy(['co_so'])->all();
        $aLichHen = ArrayHelper::map($lichhen,'co_so','id');
        //
        $khachden = CustomerBaoCao::find()
            ->select(['COUNT(phone) AS id','co_so' ])
            ->where(['between', 'date_lichhen', $day, $dayTo])
            ->andWhere(['in', 'status', [CustomerModel::STATUS_DH]])
            ->andWhere(['in', 'dat_hen', [CustomerModel::DA_DEN]])
            ->groupBy(['co_so'])->all();

        $aKhachDen = ArrayHelper::map($khachden,'co_so','id');
        //
        $listCustomerComeName = Dep365CustomerOnlineCome::getCustomerOnlineComeArrayAccept();
        $listCustomerCome = array_keys($listCustomerComeName);
        $khachchot = CustomerBaoCao::find()
            ->select(['COUNT(*) AS id','co_so' ])
            ->where(['between', 'date_lichhen', $day, $dayTo])
            ->andWhere(["dat_hen" => Dep365CustomerOnline::DAT_HEN_DEN])
            ->andWhere(['dep365_customer_online.status' => Dep365CustomerOnline::STATUS_DH])
            ->andWhere(["in","customer_come_time_to",$listCustomerCome])
            ->groupBy(['co_so'])->all();
        $aKhachChot = ArrayHelper::map($khachchot,'co_so','id');
        //
        $text = "BC DOANH THU <i>".$strDay."</i> \n".
            "Tổng: <b>".$doanhThuTrongNgay."</b> \n".
            "Hẹn: ".array_sum($aLichHen)." - Đến: ".array_sum($aKhachDen)." - Chốt: ".array_sum($aKhachChot)." \n";

        $listCoSo = ArrayHelper::map(Dep365CoSo::getCoSo(), 'id', 'name');
        $strCoSo = '';
        asort($listCoSo);
        foreach ($listCoSo as $key => $name){
            $doanhThuCoSo = number_format(PhongKhamDonHang::sumTienThanhToanByCoSo($day,$day,$key), 0, ',', '.');
            $sumLichHen = isset($aLichHen[$key]) ? $aLichHen[$key] : 0;
            $sumKhachDen = isset($aKhachDen[$key]) ? $aKhachDen[$key] : 0;
            $sumKhachChot = isset($aKhachChot[$key]) ? $aKhachChot[$key] : 0;
            $strCoSo .= "CS".$name." : ".$doanhThuCoSo." \n".
                "Hẹn: ".$sumLichHen." - Đến: ".$sumKhachDen." - Chốt: ".$sumKhachChot." \n";
        }

        $TextMessage = $text.$strCoSo;
        Yii::$app->telegram->sendMessage([
            'chat_id' => -434612391, //816332630, NGhia test
//            'chat_id' => 816332630, //816332630, NGhia test
            'text' => $TextMessage,
            'parse_mode' => 'html'
        ]);

    }
}
