<?php
namespace backend\modules\report\components;

use backend\modules\baocao\models\BaocaoLocation;
use backend\modules\customer\models\Dep365CustomerOnlineFailDathen;
use backend\modules\customer\models\Dep365CustomerOnlineFanpage;
use common\models\User;
use Yii;
use backend\modules\report\models\CustomerBaoCao;
use yii\base\Component;

class LichMoi extends Component
{
    public static function getDataOnline($from, $to, $id_online = null, $id_location = null, $id_page = null, $id_coso = null)
    {
        //test
//        $from = "1-1-2018";
//        $to = "30-10-2019";
        $from = strtotime($from);
        $to = strtotime($to);
        $NAME_LICH_MOI = "Lịch Mới";
        $list_id_Page = Dep365CustomerOnlineFanpage::getListFanpageArray();
        $list_Id_User = $ngay_tao  = $list_Date_LyDo = $tableNhanVienData = $list_Date_LichMoi = $list_NhanVien_LichMoi = $list_FanPage_LichMoi = [];

        $queryNgaytao = CustomerBaoCao::find()
            ->select(['COUNT(*) AS id','ngay_tao','permission_user','face_fanpage' ])
            ->where(['between', 'ngay_tao', $from, $to])
            ->andWhere(["=","status",1]); // lich moi

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

        $dataAll = $queryNgaytao->groupBy(['ngay_tao','permission_user','face_fanpage'])->all();
        foreach ($dataAll as $item) {
            $date = date('d-m-Y', $item->ngay_tao);
            $count = $item->id;
            if (isset($list_Date_LichMoi[$date])) {
                $list_Date_LichMoi[$date] += $count;
            } else {
                $list_Date_LichMoi[$date] = $count;
            }
            if (isset($list_NhanVien_LichMoi[$item->permission_user])) {
                $list_NhanVien_LichMoi[$item->permission_user] += $count;
            } else {
                $list_NhanVien_LichMoi[$item->permission_user] = $count;
            }
            if (isset($list_FanPage_LichMoi[$item->face_fanpage])) {
                $list_FanPage_LichMoi[$item->face_fanpage] += $count;
            } else {
                $list_FanPage_LichMoi[$item->face_fanpage] = $count;
            }
            $list_Id_User[$item->permission_user] = $item->permission_user;
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
            'name' => $NAME_LICH_MOI,
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
            'lable' => [$NAME_LICH_MOI],
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
