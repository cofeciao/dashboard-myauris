<?php
/**
 * Created by PhpStorm.
 * User: luken
 * Date: 8/12/2020
 * Time: 10:46
 */

namespace backend\modules\report\controllers;


use backend\components\MyController;
use backend\modules\chi\models\DanhMucChi;
use backend\modules\chi\models\DeXuatChi;
use backend\modules\chi\models\KhoanChi;
use backend\modules\chi\models\NhomChi;
use backend\modules\customer\components\CustomerComponents;
use yii\web\Response;

class SmartReportControllerBk extends MyController
{
    public function actionIndex()
    {
        //Lấy danh sách danh mục chi
        $danhMucChi = DanhMucChi::getDanhMucChi();

        return $this->render('index', [
            'danhMucChi' => $danhMucChi,
        ]);
    }

    public function actionView()
    {

    }

    public function actionGetExpensesGroup()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $idDMChi = \Yii::$app->request->post('idDMChi');

            $dataExpensesGroup = NhomChi::getNhomChiByDanhMuc($idDMChi);

            $expensesGroup = [];
            foreach ($dataExpensesGroup as $key => $value) {
                $expensesGroup[] = [
                    'id' => $value->id,
                    'name' => $value->name,
                ];
            }

            return [
                'expensesGroup' => $expensesGroup
            ];
        }
    }

    public function actionGetExpenses()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $idExpensesGroup = \Yii::$app->request->post('idNhomChi');

            $dataExpenses = KhoanChi::getListKhoanChiByNhomChi($idExpensesGroup);

            $expenses = [];
            foreach ($dataExpenses as $key => $value) {
                $expenses[] = [
                    'id' => $value->id,
                    'name' => $value->name,
                ];
            }

            return [
                'expenses' => $expenses
            ];
        }
    }

    /**
     * @param int|null $idDMChi
     * @param int|null $idNhomChi
     * @param string|null $from
     * @param string|null $to
     * @return string
     */
    public function actionGetData()
    {
        if (\Yii::$app->request->isAjax) {
            $getIdDMChi = \Yii::$app->request->get('idDMChi');
            $getIdNhomChi = \Yii::$app->request->get('idNhomChi');
            $from = strtotime(\Yii::$app->request->get('from'));
            $to = strtotime(\Yii::$app->request->get('to')) + 86399;

            //Lấy danh sách nhóm chi
            $listKhoanChi = KhoanChi::find()->all();
            $nhomChi = KhoanChi::find()->joinWith(['deXuatChiHasMany']);
            if ($getIdNhomChi != null) {
                $nhomChi->joinWith(['nhomChiHasOne'])->andWhere([NhomChi::tableName() . '.id' => $getIdNhomChi]);
            } elseif ($getIdDMChi != null) {
                $nhomChi->joinWith(['danhMucChiHasOne'])->andWhere([DanhMucChi::tableName() . '.id' => $getIdDMChi]);
            }
            $nhomChi->andWhere(['BETWEEN', DeXuatChi::tableName() . '.thoi_han_thanh_toan', $from, $to]);
            $nhomChi = $nhomChi->indexBy('id')->all();
            $data = [];
            $doanh_thu = 0;
            $doanhThuTheoThangData = CustomerComponents::getRevenue($from, $to);
            foreach ($doanhThuTheoThangData as $idCoSo => $doanhThuCoSo) {
                $doanh_thu += $doanhThuCoSo->tien;
            }
            foreach ($listKhoanChi as $item) {
                $itemKhoanChi = array_key_exists($item->id, $nhomChi) ? $nhomChi[$item->id] : null;
                $idDMChi = $item->nhomChiHasOne->danhMucHasOne->id;
                $idNhomChi = $item->nhomChiHasOne->id;
                if (($getIdDMChi != null && $getIdDMChi != $idDMChi) || ($getIdNhomChi != null && $getIdNhomChi != $idNhomChi)) continue;
                $da_chi = $itemKhoanChi != null && $itemKhoanChi->deXuatChiDaChi != null ? $itemKhoanChi->deXuatChiDaChi : 0;
                $cho_duyet = $itemKhoanChi != null && $itemKhoanChi->deXuatChiChoDuyet != null ? $itemKhoanChi->deXuatChiChoDuyet : 0;
                $sau_duyet = $da_chi + $cho_duyet;

                if (!array_key_exists($idDMChi, $data)) $data[$idDMChi] = [
                    'name' => $item->nhomChiHasOne->danhMucHasOne->name,
                    'nhom_chi' => []
                ];
                if (!array_key_exists($idNhomChi, $data[$idDMChi]['nhom_chi'])) $data[$idDMChi]['nhom_chi'][$idNhomChi] = [
                    'name' => $item->nhomChiHasOne->name,
                    'code' => $item->nhomChiHasOne->code,
                    'da_chi' => 0,
                    'cho_duyet' => 0,
                    'sau_duyet' => 0,
                    'khoan_chi' => []
                ];
                $data[$idDMChi]['nhom_chi'][$idNhomChi]['da_chi'] += $da_chi;
                $data[$idDMChi]['nhom_chi'][$idNhomChi]['cho_duyet'] += $cho_duyet;
                $data[$idDMChi]['nhom_chi'][$idNhomChi]['sau_duyet'] += $sau_duyet;
                $data[$idDMChi]['nhom_chi'][$idNhomChi]['khoan_chi'][] = [
                    'name' => $item->name,
                    'code' => $item->code,
                    'da_chi' => $da_chi,
                    'cho_duyet' => $cho_duyet,
                    'sau_duyet' => $sau_duyet
                ];
            }

            return $this->renderAjax('_html_table', [
                'data' => $data,
                'doanh_thu' => $doanh_thu
            ]);
        }

    }
}