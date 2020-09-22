<?php
namespace backend\modules\report\components;

use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\customer\models\Dep365CustomerOnlineCome;
use Yii;
use backend\modules\baocao\models\BaocaoLocation;
use backend\modules\customer\models\Dep365CustomerOnlineFanpage;
use common\models\User;
use backend\modules\report\models\CustomerBaoCao;
use yii\base\Component;

class KhachChot extends Component
{
    public static function getDataOnline($from, $to, $direct_sale = null, $id_location = null, $id_page = null, $cusomer_come = null, $id_coso =null)
    {
        //test
//        $from = "1-1-2018";
//        $to   = "30-10-2019";
        $from = strtotime($from);
        $to   = strtotime($to);
        $NAME_KHACH_CHOT = "Khách Chốt";
        $list_id_Page = Dep365CustomerOnlineFanpage::getListFanpageArray();
        $list_Id_User = $ngay_tao  = $tableNhanVienData = $list_Date_LichMoi = $list_NhanVien_LichMoi = $list_FanPage_LichMoi = [];

        $queryNgaytao = CustomerBaoCao::find()
            ->select(['COUNT(*) AS id','date_lichhen','directsale','face_fanpage' ])
            ->where(['between', 'date_lichhen', $from, $to])
            ->andWhere(["dat_hen" => Dep365CustomerOnline::DAT_HEN_DEN])
            ->andWhere(['dep365_customer_online.status' => Dep365CustomerOnline::STATUS_DH]);

        if ($cusomer_come) {
            $queryNgaytao->andWhere(["customer_come_time_to" => $cusomer_come]);
        } else {
            $listCustomerComeName = Dep365CustomerOnlineCome::getCustomerOnlineComeArrayAccept();
            $listCustomerCome = array_keys($listCustomerComeName);
            $queryNgaytao->andWhere(["in","customer_come_time_to",$listCustomerCome]);
        }

        if ($direct_sale) {
            $queryNgaytao->andWhere(["directsale" => $direct_sale]);
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
            $queryNgaytao->andWhere(["face_fanpage" => $id_page]);
        }

        if ($id_coso) {
            $queryNgaytao->andWhere(["co_so" => $id_coso]);
        }
        $queryNgaytao->groupBy(['date_lichhen','directsale','face_fanpage']);
//        echo $queryNgaytao->createCommand()->rawSql;die;
        $dataAll = $queryNgaytao->all();
        foreach ($dataAll as $item) {
            $date = date('d-m-Y', $item->date_lichhen);
            $count = $item->id;
            if (isset($list_Date_LichMoi[$date])) {
                $list_Date_LichMoi[$date] += $count;
            } else {
                $list_Date_LichMoi[$date] = $count;
            }
            if (isset($list_NhanVien_LichMoi[$item->directsale])) {
                $list_NhanVien_LichMoi[$item->directsale] += $count;
            } else {
                $list_NhanVien_LichMoi[$item->directsale] = $count;
            }
            if (isset($list_FanPage_LichMoi[$item->face_fanpage])) {
                $list_FanPage_LichMoi[$item->face_fanpage] += $count;
            } else {
                $list_FanPage_LichMoi[$item->face_fanpage] = $count;
            }
            $list_Id_User[$item->directsale] = $item->directsale;
            $ngay_tao[$date] = $date;
        }

        $list_FanPage = [];
        foreach ($list_FanPage_LichMoi as $key => $value) {
            if (empty($key)) {
                $list_FanPage[] = [
                    'name' => "Không xác định",
                    'value' => $value
                ];
            } else {
                $list_FanPage[] = [
                    'name' =>  isset($list_id_Page[$key]) ? $list_id_Page[$key] : "Không xác định",
                    'value' => $value
                ];
            }
        }
        $array_NhanVien_LichMoi = [];
        foreach ($list_NhanVien_LichMoi as $key => $value) {
            $array_NhanVien_LichMoi[] = [
                'id' => $key,
                'value' => $value,
            ];
        }
        $enddata[] = [
            'name' => $NAME_KHACH_CHOT,
            'type' => 'line',
            'stack'=> 'Total',
            'smooth' => true,
            'itemStyle' => [ 'normal' => [ 'areaStyle' => ['type' => 'default']]],
            'data' => array_values($list_Date_LichMoi)
        ];
        $list_User = User::getListNameUserByListId(array_values($list_Id_User));

        return [
            'result' => $enddata,
            'date' => array_values($ngay_tao),
            'lable' => [$NAME_KHACH_CHOT],
            'array_NhanVien_LichMoi' => $array_NhanVien_LichMoi,
            'list_User' => $list_User,
            'list_FanPage' => $list_FanPage
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
