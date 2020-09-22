<?php

namespace backend\modules\clinic\controllers;

use backend\components\MyComponent;
use backend\modules\clinic\models\Clinic;
use backend\modules\clinic\models\PhongKhamDonHangTree;
use backend\modules\clinic\models\PhongKhamDonHangWOrder;
use backend\modules\clinic\models\PhongKhamDonHangWThanhToan;
use backend\modules\clinic\models\PhongKhamSanPham;
use Yii;
use backend\modules\clinic\models\PhongKhamDonHang;
use backend\modules\clinic\models\search\PhongKhamDonHangSearch;
use backend\components\MyController;
use yii\db\Transaction;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use backend\modules\user\models\User;

/**
 * ClinicOrderController implements the CRUD actions for PhongKhamDonHang model.
 */
class ClinicOrderController extends MyController
{
    public function actionIndex()
    {
        $searchModel = new PhongKhamDonHangSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (MyComponent::hasCookies('pageSize')) {
            $dataProvider->pagination->pageSize = MyComponent::getCookies('pageSize');
        } else {
            $dataProvider->pagination->pageSize = 10;
        }
        $pageSize = $dataProvider->pagination->pageSize;
        $totalCount = $dataProvider->totalCount;
        $totalPage = (($totalCount + $pageSize - 1) / $pageSize);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalPage' => $totalPage,
        ]);
    }

    public function actionPerpage($perpage)
    {
        MyComponent::setCookies('pageSize', $perpage);
    }

    public function actionView($id)
    {
        if (Yii::$app->request->isAjax && $this->findModel($id)) {
            return $this->renderAjax('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    public function actionCreate()
    {
//        $model = new PhongKhamDonHang();
//
//        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
//            try {
//                $model->save();
//                Yii::$app->session->setFlash('alert', [
//                    'body' => Yii::$app->params['create-success'],
//                    'class' => 'bg-success',
//                ]);
//            } catch (\yii\db\Exception $exception) {
        Yii::$app->session->setFlash('alert', [
            'body' => Yii::$app->params['create-danger'],
            'class' => 'bg-danger',
        ]);
//            }
        return $this->redirect(['index']);
//        }

//        return $this->render('create', [
//            'model' => $model,
//        ]);
    }

    /**
     * Updates an existing PhongKhamDonHang model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = $this->findModel($id);
            if ($model == false) {
                return [
                    'status' => 404,
                    'mess' => 'Không tìm thấy dữ liệu khách hàng.',
                ];
            };
            $customer = $this->findClinic($id);
            $orderData = PhongKhamDonHangWOrder::find()->where(['phong_kham_don_hang_id' => $model->id])->all();
            $thanhtoanData = PhongKhamDonHangWThanhToan::find()->where(['phong_kham_don_hang_id' => $model->id])->all();

            if ($model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                $dataOder = $model->customer_order;
                $thanhtoanData = $model->thanh_toan;
                $model->customer_order = json_encode($model->customer_order);
                $model->thanh_toan = json_encode($model->thanh_toan);
                try {
                    $transaction = Yii::$app->db->beginTransaction(
                        Transaction::SERIALIZABLE
                    );
                    if ($model->save()) {
                        $donhangTree = new PhongKhamDonHangTree();
                        $arr = $model->getAttributes();
                        $arr['id_order'] = $model->getAttribute('id');
                        unset($arr['id']);
                        unset($arr['customer_order']);
                        unset($arr['thanh_toan']);

                        foreach ($arr as $key => $item) {
                            $donhangTree->$key = $item;
                        }

                        if ($donhangTree->save()) {
                            //Thêm order
                            $arrID = [];
                            foreach ($dataOder as $value) {
                                $order = PhongKhamDonHangWOrder::find()->where(['id' => $value['id']])->one();
                                if ($order === null) {
                                    $order = new PhongKhamDonHangWOrder();
                                }
                                $order->customer_id = $model->customer_id;

                                foreach ($value as $keys => $item) {
                                    if ($keys == 'id') {
                                        continue;
                                    }
                                    $order->phong_kham_don_hang_id = $model->getPrimaryKey();
                                    $order->$keys = $item;
                                }
                                if (!$order->save()) {
                                    $transaction->rollBack();
                                } else {
                                    $arrID[] = $order->getPrimaryKey();
                                }
                            }

                            $arrIdNotIn = PhongKhamDonHangWOrder::find()->where(['not in', 'id', $arrID])->andWhere(['in', 'customer_id', $model->customer_id])->all();
                            foreach ($arrIdNotIn as $key => $val) {
                                $orderDel = PhongKhamDonHangWOrder::findOne($val->id);
                                if (!$orderDel->delete()) {
                                    $transaction->rollBack();
                                }
                            }

                            //Thêm thanh toán
                            $arrIDTT = [];
                            foreach ($thanhtoanData as $value) {
                                $thanhtoan = PhongKhamDonHangWThanhToan::find()->where(['id' => $value['id']])->one();
                                if ($thanhtoan === null) {
                                    $thanhtoan = new PhongKhamDonHangWThanhToan();
                                }
                                $thanhtoan->customer_id = $model->customer_id;

                                foreach ($value as $keys => $item) {
                                    if ($keys == 'id') {
                                        continue;
                                    }
                                    $thanhtoan->phong_kham_don_hang_id = $model->getPrimaryKey();
                                    $thanhtoan->$keys = $item;
                                }
                                if (!$thanhtoan->save()) {
                                    $transaction->rollBack();
                                } else {
                                    $arrIDTT[] = $thanhtoan->getPrimaryKey();
                                }
                            }

                            $arrIdNotInTT = PhongKhamDonHangWThanhToan::find()->where(['not in', 'id', $arrIDTT])->andWhere(['in', 'customer_id', $model->customer_id])->all();
                            foreach ($arrIdNotInTT as $key => $val) {
                                $thanhtoanDel = PhongKhamDonHangWThanhToan::findOne($val->id);
                                if (!$thanhtoanDel->delete()) {
                                    $transaction->rollBack();
                                }
                            }
                            //End

                            $transaction->commit();
                            return [
                                'status' => 200,
                                'mess' => Yii::$app->params['update-success'],
                            ];
                        } else {
                            $transaction->rollBack();
                            return [
                                'status' => 403,
                                'mess' => Yii::$app->params['update-danger'],
                            ];
                        }
                    } else {
                        $transaction->rollBack();
                        return [
                            'status' => 400,
                            'mess' => Yii::$app->params['update-danger'],
                        ];
                    }
                } catch (\yii\db\Exception $exception) {
                    return [
                        'status' => 400,
                        'mess' => $exception->getMessage(),
                        'error' => $exception,
                    ];
                }
            }

            return $this->renderAjax('update', [
                'model' => $model,
                'customer' => $customer,
                'orderData' => $orderData,
                'thanhtoanData' => $thanhtoanData
            ]);
        }
    }

    public function actionValidateOrder()
    {
        $idGet = Yii::$app->request->get('id');
        $model = $this->findModel($idGet);
        if ($model === false) {
            $model = new PhongKhamDonHang();
        }

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            $check = $model->customer_order;
            $checkMoney = $model->thanh_toan;
            $model->customer_order = json_encode($model->customer_order);
            $model->thanh_toan = json_encode($model->thanh_toan);
            $model->thanh_tien = str_replace('.', '', $model->thanh_tien);

            foreach ($check as $key => $item) {
                if ($item['dich_vu'] == 0) {
                    $model->dich_vu = 0;
                    $model->scenario = 'checkOrder';
                } else {
                    $model->dich_vu = $item['dich_vu'];
                }
                if ($item['san_pham'] == 0) {
                    $model->s_p = 0;
                    $model->scenario = 'checkOrder';
                } else {
                    $model->s_p = $item['san_pham'];
                }
            }

            foreach ($checkMoney as $key => $item) {
                if ($item['tien_thanh_toan'] == 0) {
                    $model->t_T_t = 0;
                    $model->scenario = 'checkOrder';
                } else {
                    $model->t_T_t = $item['tien_thanh_toan'];
                }
            }

            return Json::encode(\yii\widgets\ActiveForm::validate($model));
        }
    }

    public function actionGetPriceSanPham()
    {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');
            $sl = Yii::$app->request->post('sl');
            $sanpham = new PhongKhamSanPham();
            $data = $sanpham->getSanPhamOne($id);
            $result = $data->don_gia;
            $data = $sl * $result;
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'status' => true,
                'result' => number_format($data, 0, ',', '.'),
            ];
        }
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

    protected function findModel($id)
    {
        $user = new User();
        $roleUser = $user->getRoleName(\Yii::$app->user->id);
        $model = PhongKhamDonHang::find()->where(['customer_id' => $id]);

        if ($roleUser == User::USER_DIRECT_SALE) {
            $model->joinWith(['clinicHasOne']);
            $model->andWhere(['dep365_customer_online.directsale' => \Yii::$app->user->id]);
        }

        $order = $model->one();
        if ($order !== null) {
            return $order;
        }

        return false;
    }

    protected function findClinic($id)
    {
        $model = Clinic::findOne($id);
        if (($model !== null)) {
            return $model;
        }

        return false;
    }
}
