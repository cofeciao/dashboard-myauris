<?php

namespace backend\modules\report\controllers;

use backend\components\MyComponent;
use backend\components\MyController;
use backend\modules\chi\models\DanhMucChi;
use backend\modules\chi\models\DeXuatChi;
use backend\modules\chi\models\KhoanChi;
use backend\modules\chi\models\NhomChi;
use backend\modules\customer\components\CustomerComponents;
use backend\modules\report\models\form\SmartReportForm;
use backend\modules\report\models\SmartReportModel;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * SmartReportController implements the CRUD actions for SmartReportModel model.
 */
class SmartReportController extends MyController
{
    public function actionIndex()
    {
        //Lấy danh sách danh mục chi
        $danhMucChi = DanhMucChi::getDanhMucChi();

        return $this->render('index', [
            'danhMucChi' => $danhMucChi,
        ]);
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

    public function actionPerpage($perpage)
    {
        MyComponent::setCookies('pageSize', $perpage);
    }

    public function actionView($id)
    {
        if ($this->findModel($id)) {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
        return $this->redirect(['index']);
    }

    public function actionCreate(string $from = null, string $to = null)
    {
        if ($from == null) $from = date('1-m-Y');
        if ($to == null) $to = date('t-m-Y');
        $from = strtotime($from);
        $to = strtotime($to);

        $model = new SmartReportForm();
        $listKhoanChi = KhoanChi::find()->all();
        if ($from != null && $to != null) {
            $model->report_timestamp = date('m-Y', $from);
            $nhomChi = KhoanChi::find()->joinWith(['smartReportHasMany'])
                ->where(['BETWEEN', SmartReportModel::tableName() . '.report_timestamp', $from, $to])
                ->indexBy('id')->all();
        }
        $data = [];
        foreach ($listKhoanChi as $item) {
            if ($nhomChi) {
                $itemKhoanChi = array_key_exists($item->id, $nhomChi) ? $nhomChi[$item->id] : null;
                $da_chi = $itemKhoanChi != null && $itemKhoanChi->getTienDaChi($from, $to) != null ? $itemKhoanChi->getTienDaChi($from, $to) : 0;
                $cho_duyet = $itemKhoanChi != null && $itemKhoanChi->getTienChoDuyet($from, $to) != null ? $itemKhoanChi->getTienChoDuyet($from, $to) : 0;
                $model->data[$item->id]['tien_da_chi'] = number_format($da_chi, 0, '', '.');
                $model->data[$item->id]['tien_cho_duyet'] = number_format($cho_duyet, 0, '', '.');
            }
            $idDMChi = $item->nhomChiHasOne->danhMucHasOne->id;
            $idNhomChi = $item->nhomChiHasOne->id;

            if (!array_key_exists($idDMChi, $data)) $data[$idDMChi] = [
                'name' => $item->nhomChiHasOne->danhMucHasOne->name,
                'nhom_chi' => []
            ];
            if (!array_key_exists($idNhomChi, $data[$idDMChi]['nhom_chi'])) $data[$idDMChi]['nhom_chi'][$idNhomChi] = [
                'name' => $item->nhomChiHasOne->name,
                'code' => $item->nhomChiHasOne->code,
                'khoan_chi' => []
            ];
            $data[$idDMChi]['nhom_chi'][$idNhomChi]['khoan_chi'][$item->id] = [
                'name' => $item->name,
                'code' => $item->code,
            ];
        }

        return $this->render('create', [
            'model' => $model,
            'data' => $data,
        ]);
    }

    public function actionValidateSmartReport()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = new SmartReportModel();

            if ($model->load(Yii::$app->request->post())) {
                return ActiveForm::validate($model);
            }
        }
    }

    public function actionSubmitSmartReport()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $model = new SmartReportForm();
            $model->load(Yii::$app->request->post());
            $arrayMessage = [];
            foreach ($model->data as $key => $value) {
                $value['tien_da_chi'] = str_replace('.', '', $value['tien_da_chi']);
                $value['tien_cho_duyet'] = str_replace('.', '', $value['tien_cho_duyet']);

                $modelSR = SmartReportModel::find()->where(['id_khoan_chi' => $key, 'report_timestamp' => strtotime('01-' . $model->report_timestamp)])->one();

                if ($modelSR == null) {
                    $modelSR = new SmartReportModel([
                        'id_khoan_chi' => $key
                    ]);
                }
                $value['report_timestamp'] = $model->report_timestamp;
                $modelSR->setAttributes($value, false);

                if (!$modelSR->save()) {
                    $arrayMessage[] = 'Fail ' . $key;
                }
            }

            if (count($arrayMessage) == 0) {
                $status = 200;
                $msg = 'Success';
            } elseif (count($arrayMessage) == count($model->data)) {
                $status = 400;
                $msg = 'Danger:' . implode('<br>', $arrayMessage);
            } else {
                $status = 499;
                $msg = 'Warning:' . implode('<br>', $arrayMessage);
            }

            return [
                'status' => $status,
                'msg' => $msg
            ];
        }

    }

    /**
     * Updates an existing SmartReportModel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            try {
                $model->save();
                Yii::$app->session->setFlash('alert', [
                    'body' => Yii::$app->params['update-success'],
                    'class' => 'bg-success',
                ]);
            } catch (\yii\db\Exception $exception) {
                Yii::$app->session->setFlash('alert', [
                    'body' => $exception->getMessage(),
                    'class' => 'bg-danger',
                ]);
            }
            return $this->refresh();
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
            $id = Yii::$app->request->post('id');
            try {
                if ($this->findModel($id)->delete()) {
                    return [
                        "status" => "success"
                    ];
                } else {
                    return [
                        "status" => "failure"
                    ];
                }
            } catch (\yii\db\Exception $e) {
                return [
                    "status" => "exception"
                ];
            }
        }

        return $this->redirect(['index']);
    }

    public function actionShowHide()
    {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');

            $model = $this->findModel($id);
            try {
                if ($model->status == 1) {
                    $model->status = 0;
                } else {
                    $model->status = 1;
                }
                if ($model->save()) {
                    echo 1;
                }
            } catch (\yii\db\Exception $exception) {
                echo 0;
            }
        }

    }

    public function actionDeleteMultiple()
    {
        try {
            $action = Yii::$app->request->post('action');
            $selectCheckbox = Yii::$app->request->post('selection');
            if ($action === 'c') {
                if ($selectCheckbox) {
                    foreach ($selectCheckbox as $id) {
                        $this->findModel($id)->delete();
                    }
                    \Yii::$app->session->setFlash('indexFlash', 'Bạn đã xóa thành công.');
                }
            }
        } catch (\yii\db\Exception $e) {
            if ($e->errorInfo[1] == 1451) {
                throw new \yii\web\HttpException(400, 'Failed to delete the object.');
            } else {
                throw $e;
            }
        }
        return $this->redirect(['index']);
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

            $countSR = SmartReportModel::find()->where(['BETWEEN', 'report_timestamp', $from, $to])->count();
            $listKhoanChi = KhoanChi::find()->all();
            $nhomChi = KhoanChi::find();

            if ($countSR > 0) {
                $nhomChi->joinWith(['smartReportHasMany'])->andWhere(['BETWEEN', SmartReportModel::tableName() . '.report_timestamp', $from, $to]);
            } else {
                $nhomChi->joinWith(['deXuatChiHasMany'])->andWhere(['BETWEEN', DeXuatChi::tableName() . '.thoi_han_thanh_toan', $from, $to]);
            }

            if ($getIdNhomChi != null) {
                $nhomChi->joinWith(['nhomChiHasOne'])->andWhere([NhomChi::tableName() . '.id' => $getIdNhomChi]);
            } elseif ($getIdDMChi != null) {
                $nhomChi->joinWith(['danhMucChiHasOne'])->andWhere([DanhMucChi::tableName() . '.id' => $getIdDMChi]);
            }

            $nhomChi->indexBy('id');
            $nhomChi = $nhomChi->all();
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
                if ($countSR > 0) {
                    $da_chi = $itemKhoanChi != null && $itemKhoanChi->getTienDaChi($from, $to) != null ? $itemKhoanChi->getTienDaChi($from, $to) : 0;
                    $cho_duyet = $itemKhoanChi != null && $itemKhoanChi->getTienChoDuyet($from, $to) != null ? $itemKhoanChi->getTienChoDuyet($from, $to) : 0;
                } else {
                    $da_chi = $itemKhoanChi != null && $itemKhoanChi->deXuatChiDaChi != null ? $itemKhoanChi->deXuatChiDaChi : 0;
                    $cho_duyet = $itemKhoanChi != null && $itemKhoanChi->deXuatChiChoDuyet != null ? $itemKhoanChi->deXuatChiChoDuyet : 0;
                }
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
                'doanh_thu' => $doanh_thu,
                'note' => $countSR == 0 ? '**Lưu ý: Dữ liệu từ đề xuất chi. Vui lòng thêm mới dữ liệu' : '',
            ]);
        }

    }

    protected function findModel($id)
    {
        if (($model = SmartReportModel::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
    }
}
