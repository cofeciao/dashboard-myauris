<?php
namespace backend\modules\report\components;

use backend\modules\customer\models\Dep365CustomerOnlineDichVu;
use Yii;
use backend\modules\baocao\models\BaocaoLocation;
use backend\modules\report\models\CustomerBaoCao;
use yii\base\Component;

class SanPham extends Component
{
    public static function getDataOnline($from, $to, $direct_sale = null, $id_location = null, $id_page = null, $id_dichvu = null)
    {
        //test
//        $from = "1-1-2018";
//        $to   = "30-10-2019";
        $from = strtotime($from);
        $to   = strtotime($to);
        $result = $list_Id_User = $ngay_tao  = $tableNhanVienData = $list_Date_DichVu  = [];
        $listDichVu = Dep365CustomerOnlineDichVu::getSanPhamDichVuArray();

        $queryNgaytao = CustomerBaoCao::find()
            ->select(['COUNT(*) AS id','ngay_tao','id_dich_vu' ])
            ->where(['between', 'ngay_tao', $from, $to])
            ->andWhere(['not', ['id_dich_vu' => null]]);

        if ($id_dichvu) {
            $queryNgaytao->andWhere(["=","id_dich_vu",$id_dichvu]);
        }

        if ($direct_sale) {
            $queryNgaytao->andWhere(["=","directsale",$direct_sale]);
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

        $dataAll = $queryNgaytao->groupBy(['ngay_tao','id_dich_vu'])->all();
        foreach ($dataAll as $item) {
            $date = date('d-m-Y', $item->ngay_tao);
            $count = $item->id;
            if (isset($list_Date_DichVu[$date][$item->id_dich_vu])) {
                $list_Date_DichVu[$date][$item->id_dich_vu] += $count;
            } else {
                $list_Date_DichVu[$date][$item->id_dich_vu] = $count;
            }
            $ngay_tao[$date] = $date;
        }

        foreach ($listDichVu as $key => $name_dich_vu) {
            $arrForReason = [];
            foreach ($ngay_tao as $date) {
                if (isset($list_Date_DichVu[$date][$key])) {
                    $arrForReason[$date] = $list_Date_DichVu[$date][$key];
                } else {
                    $arrForReason[$date] = 0;
                }
            }
            $result[$key] = $arrForReason;
        }
        $enddata = $tableData = [];
        foreach ($listDichVu as $key => $name_dich_vu) {
            $list = array_values($result[$key]);
            $enddata[] = [
                'name' => $name_dich_vu,
                'type' => 'line',
                'stack'=> 'Total',
                'smooth' => true,
                'itemStyle' => [ 'normal' => [ 'areaStyle' => ['type' => 'default']]],
                'data' => $list
            ];
            $tableData[] = [
                'name' => $name_dich_vu,
                'value' => array_sum($list)
            ];
        }
        return [
            'result' => $enddata,
            'date' => array_values($ngay_tao),
            'lable' => array_values($listDichVu),
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
