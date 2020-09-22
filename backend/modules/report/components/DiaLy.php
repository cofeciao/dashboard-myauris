<?php

namespace backend\modules\report\components;

use backend\models\CustomerModel;
use backend\models\doanhthu\DonHangModel;
use backend\models\doanhthu\ThanhToanModel;
use backend\modules\baocao\models\BaocaoLocation;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\customer\models\Dep365CustomerOnlineCome;
use backend\modules\customer\models\Dep365CustomerOnlineDathenTime;
use backend\modules\location\models\District;
use backend\modules\location\models\Province;
use backend\modules\report\models\CustomerBaoCao;
use backend\modules\setting\models\Dep365CoSo;
use common\models\User;
use yii\base\Component;
use yii\helpers\ArrayHelper;

class DiaLy extends Component
{
    public static function getDataOnline($from, $to, $page_online = null, $var_province = null)
    {
        //test
//        $from = "1-1-2018";
//        $to   = "30-12-2019";

        $from = strtotime($from);
        $to = strtotime($to);
        $aResult_KhachDen = $aResult_KhachChot = $aResult_ChotFail = $aResult_KhongDen = $aResult_LichMoi = $listNameProvince = $aResult_LichHen = [];
        $listUser = User::getNhanVienIsActiveArray();
        $listUser = array_keys($listUser);
        $listProvince = [];


        // Khach Den
//        $queryNgaytao = CustomerBaoCao::find()
//            ->where(['between', 'customer_come_date', $from, $to]);
//        if ($page_online) {
//            $queryNgaytao->andWhere(["face_fanpage" => $page_online]);
//        }
//        $dataAll = $queryNgaytao->select(['COUNT(*) AS id', 'province'])
//                ->andWhere(['in', 'status', [CustomerModel::STATUS_DH]])
//                ->andWhere(['in', 'dat_hen', [CustomerModel::DA_DEN]])
//                ->andWhere(['in', 'permission_user', $listUser])
//                ->groupBy([$province != null ? 'district' : 'province])->all();
//        foreach ($dataAll as $item) {
//            $province = !empty($item->province) ? $item->province : 0;
//            $aResult_KhachDen[] = [
//                'province' => $province,
//                'count' => $item->id,
//            ];
//            $listProvince[$province] = $province;
//        }

        $queryNgaytao = CustomerBaoCao::find()
            ->where(['between', 'date_lichhen', $from, $to]);
        if ($page_online) {
            $queryNgaytao->andWhere(["face_fanpage" => $page_online]);
        }
        $queryNgaytao->select(['COUNT(*) AS id', $var_province != null ? 'district AS province' : 'province'])
            ->andWhere(['in', 'status', [CustomerModel::STATUS_DH]])
            ->andWhere(['in', 'dat_hen', [CustomerModel::DA_DEN]])
            ->groupBy([$var_province != null ? 'district' : 'province']);
        if ($var_province != null) $queryNgaytao->andWhere([CustomerModel::tableName() . '.province' => $var_province]);
        $dataAll = $queryNgaytao->all();
        foreach ($dataAll as $item) {
            $province = !empty($item->province) ? $item->province : 0;
            $aResult_KhachDen[] = [
                'province' => $province,
                'count' => $item->id,
            ];
            $listProvince[$province] = $province;
        }

        // KH Chot Thành Công
        $listCustomerComeName = Dep365CustomerOnlineCome::getCustomerOnlineComeArrayAccept();
        $listCustomerCome = array_keys($listCustomerComeName);

        $queryNgaytao = CustomerBaoCao::find()
            ->where(['between', 'date_lichhen', $from, $to]);
        if ($page_online) {
            $queryNgaytao->andWhere(["face_fanpage" => $page_online]);
        }
        $dataAll = $queryNgaytao->select(['COUNT(*) AS id', $var_province != null ? 'district AS province' : 'province'])
            ->andWhere(["in", "customer_come_time_to", $listCustomerCome])
            ->andWhere(["dat_hen" => Dep365CustomerOnline::DAT_HEN_DEN])
            ->andWhere(['dep365_customer_online.status' => Dep365CustomerOnline::STATUS_DH]);
        if ($var_province != null) $queryNgaytao->andWhere([CustomerModel::tableName() . '.province' => $var_province]);
        $dataAll = $queryNgaytao->groupBy([$var_province != null ? 'district' : 'province'])->all();
        foreach ($dataAll as $item) {
            $province = !empty($item->province) ? $item->province : 0;
            $aResult_KhachChot[] = [
                'province' => $province,
                'count' => $item->id,
            ];
            $listProvince[$province] = $province;
        }

        // Ly do chot fail
        $queryNgaytao = CustomerBaoCao::find()
            ->where(['between', 'date_lichhen', $from, $to]);
        if ($page_online) {
            $queryNgaytao->andWhere(["face_fanpage" => $page_online]);
        }
        $dataAll = $queryNgaytao->select(['COUNT(*) AS id', $var_province != null ? 'district AS province' : 'province'])
            ->andWhere(["NOT IN", "customer_come_time_to", $listCustomerCome])
            ->andWhere(["dat_hen" => Dep365CustomerOnline::DAT_HEN_DEN])
            ->andWhere(['dep365_customer_online.status' => Dep365CustomerOnline::STATUS_DH]);
        if ($var_province != null) $queryNgaytao->andWhere([CustomerModel::tableName() . '.province' => $var_province]);
        $dataAll = $queryNgaytao->groupBy([$var_province != null ? 'district' : 'province'])->all();
        foreach ($dataAll as $item) {
            $province = !empty($item->province) ? $item->province : 0;
            $aResult_ChotFail[] = [
                'province' => $province,
                'count' => $item->id,
            ];
            $listProvince[$province] = $province;
        }

        // Ly do khong den

//        $query = Dep365CustomerOnlineDathenTime::find()->select(['dep365_customer_online.province as time_lichhen']);
//        $query->innerJoinWith(['customerHasOne']);
//        $query->andWhere(['between', 'dep365_customer_online_dathen_time.date_lichhen_new', $from, $to]);
//        $query->andWhere(['dep365_customer_online.status' => Dep365CustomerOnline::STATUS_DH]);
//        $query->andWhere(['dep365_customer_online.dat_hen' => Dep365CustomerOnline::DAT_HEN_KHONG_DEN]);  // khong den
//
//        if ($page_online != null && $page_online != '') {
//            $query->andWhere(["dep365_customer_online.face_fanpage" => $page_online]);
//        }
//        $query->andWhere(['in', 'dep365_customer_online_dathen_time.user_id', $listUser]);
//
//        $dataKhongDen = $query->all();
//        $dataAll = [];
//        foreach ($dataKhongDen as $item){
//            $province = !empty($item->time_lichhen) ? (int)$item->time_lichhen : 0;
//            if(isset($dataAll[$province])){
//                $dataAll[$province] += 1;
//            }else{
//                $dataAll[$province] = 1;
//            }
//        }
//        foreach ($dataAll as $key => $value) {
//            $aResult_KhongDen[] = [
//                'province' => $key,
//                'count' => (int)$value,
//            ];
//            $listProvince[$key] = $key;
//        }


        $queryNgaytao = CustomerBaoCao::find()
            ->where(['between', 'date_lichhen', $from, $to]);
        if ($page_online) {
            $queryNgaytao->andWhere(["face_fanpage" => $page_online]);
        }
        $dataAll = $queryNgaytao->select(['COUNT(*) AS id', $var_province != null ? 'district AS province' : 'province'])
            ->andWhere(["dat_hen" => Dep365CustomerOnline::DAT_HEN_KHONG_DEN]) // khong den
            ->andWhere(['dep365_customer_online.status' => Dep365CustomerOnline::STATUS_DH]);
        if ($var_province != null) $queryNgaytao->andWhere([Dep365CustomerOnline::tableName() . '.province' => $var_province]);
        $dataAll = $queryNgaytao->groupBy([$var_province != null ? 'district' : 'province'])->all();
        foreach ($dataAll as $item) {
            $province = !empty($item->province) ? $item->province : 0;
            $aResult_KhongDen[] = [
                'province' => $province,
                'count' => $item->id,
            ];
            $listProvince[$province] = $province;
        }

        // Lich hen
//        $query = Dep365CustomerOnlineDathenTime::find()->select(['dep365_customer_online.province as time_lichhen']);
//        $query->innerJoinWith(['customerHasOne']);
//        $query->andWhere(['between', 'dep365_customer_online_dathen_time.date_lichhen_new', $from, $to]);
//        $query->andWhere(['dep365_customer_online.status' => Dep365CustomerOnline::STATUS_DH]);
//
//        if ($page_online != null && $page_online != '') {
//            $query->andWhere(["dep365_customer_online.face_fanpage" => $page_online]);
//        }
//        $query->andWhere(['in', 'dep365_customer_online_dathen_time.user_id', $listUser]);
//
//        $dataLichHen = $query->all();
//        $dataAll = [];
//        foreach ($dataLichHen as $item){
//            $province = !empty($item->time_lichhen) ? (int)$item->time_lichhen : 0;
//            if(isset($dataAll[$province])){
//                $dataAll[$province] += 1;
//            }else{
//                $dataAll[$province] = 1;
//            }
//        }
//
//        foreach ($dataAll as $key => $value) {
//            $aResult_LichHen[] = [
//                'province' => $key,
//                'count' => (int)$value,
//            ];
//            $listProvince[$key] = $key;
//        }

        $queryNgaytao = CustomerBaoCao::find()
            ->where(['between', 'date_lichhen', $from, $to]);
        if ($page_online) {
            $queryNgaytao->andWhere(["face_fanpage" => $page_online]);
        }
        $queryNgaytao->select(['COUNT(*) AS id', $var_province != null ? 'district AS province' : 'province'])
            ->andWhere(['dep365_customer_online.status' => Dep365CustomerOnline::STATUS_DH])
            ->andWhere(["in", "dat_hen", [Dep365CustomerOnline::DAT_HEN_KHONG_DEN, Dep365CustomerOnline::DAT_HEN_DEN]])
            ->groupBy([$var_province != null ? 'district' : 'province']);
        if ($var_province != null) $queryNgaytao->andWhere([Dep365CustomerOnline::tableName() . '.province' => $var_province]);
//        echo $queryNgaytao->createCommand()->rawSql;
        $dataAll = $queryNgaytao->all();
        foreach ($dataAll as $item) {
            $province = !empty($item->province) ? $item->province : 0;
            $aResult_LichHen[] = [
                'province' => $province,
                'count' => $item->id,
            ];
            $listProvince[$province] = $province;
        }

        // doanh thu
        $aResult_DoanhThu = [];
        $queryNgaytao = ThanhToanModel::find()
            ->joinWith(['customerHasOne'])
            ->where(['between', ThanhToanModel::tableName() . '.ngay_tao', $from, $to])
            ->andWhere([ThanhToanModel::tableName() . '.tam_ung' => [ThanhToanModel::THANH_TOAN, ThanhToanModel::DAT_COC]]);
        if ($page_online) {
            $queryNgaytao->andWhere(["face_fanpage" => $page_online]);
        }
        $queryNgaytao->select(['SUM(tien_thanh_toan) AS id', $var_province != null ? CustomerModel::tableName() . '.district AS province' : CustomerModel::tableName() . '.province'])
            ->groupBy([$var_province != null ? 'district' : 'province']);
        if ($var_province != null) $queryNgaytao->andWhere([CustomerModel::tableName() . '.province' => $var_province]);
//        echo $queryNgaytao->createCommand()->rawSql;die;
        $dataAll = $queryNgaytao->all();
//            ->groupBy([CustomerBaoCao::tableName().'.province']);
//        echo $dataAll->createCommand()->rawSql;
//        die;
        foreach ($dataAll as $item) {

//            $province = !empty($item->province) ? (int)$item->province : 0;
            $province = !empty($item->province) ? (int)$item->province : 0;
            $sum = !empty($item->id) ? $item->id : 0;
            $aResult_DoanhThu[] = [
                'province' => $province,
                'sum' => $sum,
            ];
            $listProvince[$province] = $province;
        }

        // xu ly province
        if ($var_province == null) $alistProvince = Province::getArrayProvinceByListId(array_values($listProvince));
        else $alistProvince = ArrayHelper::map(District::getDistrictByProvince($var_province), 'id', 'name');
        foreach ($alistProvince as $key => $value) {
            $listNameProvince[] = [
                'province' => $key,
                'name' => $value
            ];
        }
        $listNameProvince[] = [
            'province' => 0,
            'name' => "Không xác định",
        ];

        return [
            'aResult_KhachDen' => $aResult_KhachDen,
            'aResult_KhachChot' => $aResult_KhachChot,
            'aResult_ChotFail' => $aResult_ChotFail,
            'aResult_KhongDen' => $aResult_KhongDen,
            'aResult_LichHen' => $aResult_LichHen,
            'listNameProvince' => $listNameProvince,
            'aResult_DoanhThu' => $aResult_DoanhThu,
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
