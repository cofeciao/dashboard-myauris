<?php

namespace backend\modules\clinic\controllers;

use backend\models\doanhthu\DonHangModel;
use backend\models\doanhthu\ThanhToanModel;
use backend\modules\clinic\models\PhongKhamDonHang;
use backend\modules\user\models\User;
use backend\modules\user\models\UserTimelineModel;
use Yii;
use backend\modules\clinic\models\PhongKhamDonHangWThanhToan;
use backend\modules\clinic\models\PhongKhamDonHangWOrder;
use backend\modules\clinic\models\search\PhongKhamDonHangWThanhToanSearch;
use backend\components\MyController;
use yii\bootstrap\ActiveForm;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\MyComponent;
use yii\web\Response;

/**
 * ClinicPaymentController implements the CRUD actions for PhongKhamDonHangWThanhToan model.
 */
class ClinicPaymentController extends MyController
{
    public function actionIndex($order_id = null)
    {
        $searchModel = new PhongKhamDonHangWThanhToanSearch();
        $order = null;
        if ($order_id != null) {
            $order = PhongKhamDonHang::find()->where(['id' => $order_id])->one();
            if ($order == null) {
                return $this->redirect(['/clinic/clinic-payment']);
            }
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $order);

        if (MyComponent::hasCookies('pageSize')) {
            $dataProvider->pagination->pageSize = MyComponent::getCookies('pageSize');
        } else {
            $dataProvider->pagination->pageSize = 10;
        }


        $so_tien_thanh_toan = $searchModel->search(Yii::$app->request->queryParams, null, true, 'tien_thanh_toan', false, $hoan_coc = false);
        $so_tien_hoancoc = $searchModel->search(Yii::$app->request->queryParams, null, true, 'tien_thanh_toan', false, $hoan_coc = true);

        $result = $so_tien_thanh_toan - $so_tien_hoancoc;

        $pageSize = $dataProvider->pagination->pageSize;

        $totalCount = $dataProvider->totalCount;

        $totalPage = (($totalCount + $pageSize - 1) / $pageSize);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalPage' => $totalPage,
            'so_tien_thanh_toan' => $result
        ]);
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
        } else {
            return $this->redirect(['index']);
        }
    }

    /*public function actionCreate()
    {
        $model = new PhongKhamDonHangWThanhToan();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            try {
                $model->save();
                Yii::$app->session->setFlash('alert', [
                    'body' => Yii::$app->params['create-success'],
                    'class' => 'bg-success',
                ]);
            } catch (\yii\db\Exception $exception) {
                Yii::$app->session->setFlash('alert', [
                    'body' => Yii::$app->params['create-danger'],
                    'class' => 'bg-danger',
                ]);
            }
            return $this->refresh();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }*/

    /**
     * Updates an existing PhongKhamDonHangWThanhToan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id = null)
    {
        if (Yii::$app->request->isAjax) {
            $model = PhongKhamDonHangWThanhToan::find()->where([PhongKhamDonHangWThanhToan::tableName() . '.id' => $id])->joinWith(['customerHasOne', 'donHangHasOne', 'loaiThanhToanHasOne'])->one();
            if ($model == null) {
                return $this->renderAjax('_error', [
                    'error' => 'Không tìm thấy thông tin thanh toán!'
                ]);
            }
            if ($model->donHangHasOne == null) {
                return $this->renderAjax('_error', [
                    'error' => 'Không tìm thấy đơn hàng!'
                ]);
            }
            $model->customer_name = $model->customerHasOne != null ? ($model->customerHasOne->full_name != null ? $model->customerHasOne->full_name : $model->customerHasOne->name) : null;
            $model->order_code = $model->donHangHasOne != null ? $model->donHangHasOne->order_code : null;
            $model->tam_ung_name = $model->tam_ung === null || !array_key_exists($model->tam_ung, ThanhToanModel::THANHTOAN_TYPE) ? null : ThanhToanModel::THANHTOAN_TYPE[$model->tam_ung];
            $listThanhToan = ThanhToanModel::THANHTOAN_TYPE;
            $hoanCoc = PhongKhamDonHangWThanhToan::find()->where(['phong_kham_don_hang_id' => $model->phong_kham_don_hang_id, 'tam_ung' => ThanhToanModel::HOAN_COC])->andWhere(['<>', 'id', $model->primaryKey])->one();
            $datCoc = PhongKhamDonHangWThanhToan::find()->where(['phong_kham_don_hang_id' => $model->phong_kham_don_hang_id, 'tam_ung' => ThanhToanModel::DAT_COC])->andWhere(['<>', 'id', $model->primaryKey])->one();
            if ($datCoc != null) {
                unset($listThanhToan[ThanhToanModel::DAT_COC]);
            }
            $readOnly = false;
            $readOnlyHoanCoc = false;
            if ($hoanCoc != null || ($model->tam_ung == ThanhToanModel::HOAN_COC && $model->accept_hoan_coc == PhongKhamDonHangWThanhToan::ACCEPT_HOAN_COC)) {
                $readOnly = true;
            }

            return $this->renderAjax('update', [
                'model' => $model,
                'listThanhToan' => $listThanhToan,
                'readOnly' => $readOnly
            ]);
        }
    }

    public function actionValidateClinicPayment($id = null)
    {
        if (Yii::$app->request->isAjax) {
            $model = PhongKhamDonHangWThanhToan::find()->where(['id' => $id])->one();
            if ($model == null) {
                $model = new PhongKhamDonHangWThanhToan();
            }
            $hoanCoc = PhongKhamDonHangWThanhToan::find()->where(['phong_kham_don_hang_id' => $model->phong_kham_don_hang_id, 'tam_ung' => ThanhToanModel::HOAN_COC])->andWhere(['<>', 'id', $model->primaryKey])->one();
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($hoanCoc != null) {
                $model->scenario = PhongKhamDonHangWThanhToan::SCENARIO_UPDATE_PAYMENT;
            }
            if ($model->load(Yii::$app->request->post())) {
                return ActiveForm::validate($model);
            }
        }
    }

    public function actionSubmitClinicPayment($id = null)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $code = 200;
            $msg = Yii::$app->params['update-success'];
            $data = null;
            $model = PhongKhamDonHangWThanhToan::find()->where(['id' => $id])->one();
            $hoanCoc = PhongKhamDonHangWThanhToan::find()->where(['phong_kham_don_hang_id' => $model->phong_kham_don_hang_id, 'tam_ung' => ThanhToanModel::HOAN_COC])->andWhere(['<>', 'id', $model->primaryKey])->one();
            if ($hoanCoc == null) {
                $model->scenario = PhongKhamDonHangWThanhToan::SCENARIO_UPDATE_PAYMENT;
                if ($model == null) {
                    $code = 404;
                    $msg = 'Không tìm thấy dữ liệu';
                } else {
                    if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                        $model->tien_thanh_toan = str_replace('.', '', $model->tien_thanh_toan);
                        $model->ngay_tao = strtotime($model->ngay_tao);
                        $user_timeline = new UserTimelineModel();
                        $user_timeline->action = [UserTimelineModel::ACTION_CAP_NHAT, UserTimelineModel::ACTION_THANH_TOAN];
                        $user_timeline->customer_id = $model->customer_id;
                        $user_timeline->save();
//                            $transaction->rollBack();
                        if (!$model->save()) {
                            $code = 400;
                            $msg = Yii::$app->params['update-danger'];
                        }
                    } else {
                        $code = 403;
                        $msg = 'Lỗi dữ liệu!';
                        $data = $model->getErrors();
                    }
                }
            }
            return [
                'code' => $code,
                'msg' => $msg,
                'data' => $data
            ];
        }
    }

    public function actionPrintPayment($id)
    {
        $payment = $this->findModel($id);
        $model = $this->findModelOrder($payment->phong_kham_don_hang_id);
        $order = PhongKhamDonHangWOrder::find()->where(['phong_kham_don_hang_id' => $model->id])->all();

        $this->layout = '@backend/views/layouts/print-template';
        return $this->render('_print_payment_temp', [
            'model' => $model,
            'payment' => $payment,
            'order' => $order
        ]);
    }


    public function actionDelete()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $id = Yii::$app->request->post('id');
            $customer = ThanhToanModel::findOne(['id' => $id]);
            try {
                $user = new User();
                $roleUser = $user->getRoleName(\Yii::$app->user->id);
                if (!in_array($roleUser, [User::USER_DEVELOP, User::USER_ADMINISTRATOR])) {
                    return [
                        'status' => 'failure'
                    ];
                }
                if ($this->findModel($id)->delete()) {
                    $user_timeline = new UserTimelineModel();
                    $user_timeline->action = [UserTimelineModel::ACTION_XOA, UserTimelineModel::ACTION_THANH_TOAN];
                    $user_timeline->customer_id = $customer->customer_id;
                    if (!$user_timeline->save()) {
                        $transaction->rollBack();
                    }
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

    protected function findModel($id)
    {
        if (($model = PhongKhamDonHangWThanhToan::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModelOrder($id)
    {
        return PhongKhamDonHang::find()
            ->select([
                "phong_kham_don_hang.*",
                "(SELECT SUM(" . PhongKhamDonHangWOrder::tableName() . ".thanh_tien) FROM " . PhongKhamDonHangWOrder::tableName() . " WHERE " . PhongKhamDonHangWOrder::tableName() . ".phong_kham_don_hang_id=" . PhongKhamDonHang::tableName() . ".id) AS dh_thanh_tien",
                "(SELECT SUM(" . PhongKhamDonHangWThanhToan::tableName() . ".tien_thanh_toan) FROM " . PhongKhamDonHangWThanhToan::tableName() . " WHERE " . PhongKhamDonHangWThanhToan::tableName() . ".phong_kham_don_hang_id=" . PhongKhamDonHang::tableName() . ".id AND " . PhongKhamDonHangWThanhToan::tableName() . ".tam_ung='" . ThanhToanModel::DAT_COC . "') AS dat_coc",
                "(SELECT SUM(" . PhongKhamDonHangWThanhToan::tableName() . ".tien_thanh_toan) FROM " . PhongKhamDonHangWThanhToan::tableName() . " WHERE " . PhongKhamDonHangWThanhToan::tableName() . ".phong_kham_don_hang_id=" . PhongKhamDonHang::tableName() . ".id AND " . PhongKhamDonHangWThanhToan::tableName() . ".tam_ung='" . ThanhToanModel::THANH_TOAN . "') AS thanh_toan"
            ])
            ->leftJoin(PhongKhamDonHangWOrder::tableName(), PhongKhamDonHangWOrder::tableName() . '.phong_kham_don_hang_id=' . PhongKhamDonHang::tableName() . '.id')
            ->where([PhongKhamDonHang::tableName() . '.id' => $id])
            ->groupBy(PhongKhamDonHang::tableName() . '.id')->one();
    }
}
