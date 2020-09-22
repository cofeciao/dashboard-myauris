<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 23-Mar-19
 * Time: 3:34 PM
 */

namespace backend\controllers;

use backend\components\MyComponent;
use backend\components\MyController;
use backend\models\CustomerModel;
use backend\models\phongkham\DirectSaleModel;
use backend\models\search\DirectSaleSearch;
use backend\modules\clinic\models\Clinic;
use backend\modules\clinic\models\PhongKhamDonHang;
use backend\modules\clinic\models\PhongKhamDonHangTree;
use backend\modules\clinic\models\PhongKhamDonHangWOrder;
use backend\modules\clinic\models\PhongKhamDonHangWThanhToan;
use backend\modules\clinic\models\PhongKhamLichDieuTri;
use backend\modules\clinic\models\PhongKhamLichDieuTriTree;
use backend\modules\clinic\models\PhongKhamSanPham;
use Yii;
use yii\db\Transaction;
use yii\helpers\Json;
use yii\web\Response;

class DirectSaleController extends MyController
{
    public function actionIndex()
    {
        $searchModel = new DirectSaleSearch();
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

    public function actionUpdateField()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $id = Yii::$app->request->post('id');
            $field = Yii::$app->request->post('field');
            $data = Yii::$app->request->post('data');

            $customer = DirectSaleModel::find()->where(['id' => $id])->one();
            if ($customer === null) {
                $status = '403';
                return ['status' => $status];
            }
            switch ($field) {
                case 'mongmuon':
                    $customer->customer_mongmuon = $data;
                    break;
                case 'fullname':
                    $customer->full_name = $data;
                    break;
                case 'notedirect':
                    $customer->note_direct = $data;
                    break;
                default:
                    break;
            }
            if ($customer->save()) {
                $status = '200';
            } else {
                $status = '403';
            }

            return ['status' => $status];
        }
    }

    public function actionDieuTri()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_HTML;
            $id = Yii::$app->request->post('id');
            $idGet = Yii::$app->request->get('id');
            $ids = $id != null ? $id : $idGet;
            $customer = $this->findModel($ids);
            if ($customer == false) {
                $error = 'Khách hàng không tồn tại. Xin cảm ơn!';
                return $this->renderAjax('_error', [
                    'error' => $error,
                ]);
            }

            $order = $this->findOrder($ids);
            if ($order === false) {
                $error = 'Xin hãy tạo đơn hàng trước khi thực hiện hành động này. Xin cảm ơn!';
                return $this->renderAjax('_error', [
                    'error' => $error,
                ]);
            }
//            $model = $this->findDieuTri($ids);
//            if ($model === false)
            $model = new PhongKhamLichDieuTri();

            $modelOld = $model->getOldAttributes();
            if ($model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $model->time_dieu_tri = strtotime($model->time_dieu_tri);
                $model->time_start = strtotime($model->time_start) != 0 ? strtotime($model->time_start) : null;
                $model->time_end = strtotime($model->time_end) != 0 ? strtotime($model->time_end) : null;
                $model->danh_gia = 0;
                if ($model->time_end != null) {
                    $model->danh_gia = 2;
                }

                $transaction = Yii::$app->db->beginTransaction(
                    Transaction::SERIALIZABLE
                );

                if ($model->validate() && $model->save()) {
                    $modelNews = $model->getAttributes();
                    unset($modelOld['updated_at']);
                    unset($modelNews['updated_at']);
                    if ($modelNews != $modelOld) {
                        $dieuTriTree = new PhongKhamLichDieuTriTree();
                        $arr = $model->getAttributes();
                        $arr['dieu_tri_id'] = $model->getPrimaryKey();
                        unset($arr['id']);

                        foreach ($arr as $key => $item) {
                            $dieuTriTree->$key = $item;
                        }

                        if ($dieuTriTree->save()) {
                            $transaction->commit();
                            return [
                                'status' => '200',
                                'result' => $idGet != null ? 'Bạn đã cập nhật thành công.' : 'Bạn đã tạo mới thành công.',
                            ];
                        } else {
                            $transaction->rollBack();
                            return [
                                'status' => '403',
                                'result' => 'Thất bại, vui lòng liên hệ bộ phận kỹ thuật gấp.',//$model->getErrors(),
                            ];
                        }
                    } else {
                        $transaction->commit();
                        return [
                            'status' => '200',
                            'result' => $idGet != null ? 'Bạn đã cập nhật thành công.' : 'Bạn đã tạo mới thành công.',
                        ];
                    }
                } else {
                    $transaction->rollBack();
                    return [
                        'status' => false,
                        'result' => 'Thất bại, vui lòng liên hệ bộ phận kỹ thuật gấp.',//$model->getErrors(),
                    ];
                }
            }


            return $this->renderAjax('_dieutri', [
                'customer' => $customer,
                'order' => $order,
                'model' => $model,
            ]);
        }
    }

    public function actionValidateDieuTri()
    {
        $model = new PhongKhamLichDieuTri();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            $model->time_dieu_tri = strtotime($model->time_dieu_tri);
            $model->time_start = strtotime($model->time_start);
            $model->time_end = strtotime($model->time_end);
            $model->scenario = PhongKhamLichDieuTri::SCENARIO_TIMEEND;
            return Json::encode(\yii\widgets\ActiveForm::validate($model));
        }
    }

    public function actionOrder()
    {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');
            $idGet = Yii::$app->request->get('id');
            Yii::$app->response->format = Response::FORMAT_HTML;

            $ids = $id != null ? $id : $idGet;
            $customer = $this->findModel($ids);
            if ($customer && $customer->customer_code == null) {
                $error = 'Vui lòng cập nhật thông tin khách hàng trước. Xin cảm ơn!';
                return $this->renderAjax('_error', [
                    'error' => $error,
                ]);
            }

            $model = $this->findOrder($ids);
            if ($model === false) {
                $model = new PhongKhamDonHang();
            }

            $orderData = PhongKhamDonHangWOrder::find()->where(['phong_kham_don_hang_id' => $model->id])->all();
            $thanhtoanData = PhongKhamDonHangWThanhToan::find()->where(['phong_kham_don_hang_id' => $model->id])->all();

            if ($model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $dataOder = $model->customer_order;
                $thanhtoanData = $model->thanh_toan;
                $model->customer_order = json_encode($model->customer_order);
                $model->thanh_toan = json_encode($model->thanh_toan);

                $transaction = Yii::$app->db->beginTransaction(
                    Transaction::SERIALIZABLE
                );
                if ($model->save()) {
                    $donhangTree = new PhongKhamDonHangTree();
                    $arr = $model->getAttributes();
                    $arr['id_order'] = $model->getPrimaryKey();
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
//                            var_dump($order);
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
                            'status' => '200',
                            'result' => $idGet != null ? 'Bạn đã cập nhật thành công.' : 'Bạn đã tạo mới thành công.',
                        ];
                    } else {
                        $transaction->rollBack();
                        return [
                            'status' => '403',
                            'result' => 'Thất bại, vui lòng liên hệ bộ phận kỹ thuật gấp.'
                        ];
                    }
                } else {
                    $transaction->rollBack();
                    return [
                        'status' => '403',
                        'result' => 'Thất bại, vui lòng liên hệ bộ phận kỹ thuật gấp.'
                    ];
                }
            }

            return $this->renderAjax('_order', [
                'model' => $model,
                'customer' => $customer,
                'orderData' => $orderData,
                'thanhtoanData' => $thanhtoanData,
            ]);
        }
    }

    public function actionValidateOrder()
    {
        $idGet = Yii::$app->request->get('id');
        $model = $this->findOrder($idGet);
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

    public function actionUpdate()
    {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');
            $customer = DirectSaleModel::find()->where(['id' => $id])->one();
            $customer->customer_come = date('d-m-Y H:i', $customer->customer_come);
            if ($customer === null) {
                return $this->redirect(['index']);
            }

            Yii::$app->response->format = Response::FORMAT_HTML;
            return $this->renderAjax('update', [
                'model' => $customer
            ]);
        }
    }

    public function actionValidateUpdate()
    {
        $idGet = Yii::$app->request->get('id');
        $model = DirectSaleModel::find()->where(['id' => $idGet])->one();

        if ($model->load(Yii::$app->request->post())) {
            return Json::encode(\yii\widgets\ActiveForm::validate($model));
        }
    }

    public function actionUpdateSubmit()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $idGet = Yii::$app->request->get('id');
            $model = DirectSaleModel::find()->where(['id' => $idGet])->one();

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->customer_come = strtotime($model->customer_come);
                if ($model->save()) {
                    return ['status' => 200, 'result' => 'Cập nhật thành công'];
                } else {
                    return ['status' => 403, 'result' => 'Cập nhật không thành công', 'error' => $model->getErrors()];
                }
            } else {
                return ['status' => 400, 'result' => 'Lỗi dữ liệu', 'error' => $model->getErrors()];
            }
        }
    }

    public function actionCheckThamMy()
    {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');

            $model = CustomerModel::find()->where(['id' => $id])->one();

            try {
                if ($model->customer_direct_sale_checkthammy == 1) {
                    $model->customer_direct_sale_checkthammy = 0;
                } else {
                    $model->customer_direct_sale_checkthammy = 1;
                }
                if ($model->save()) {
                    echo 1;
                }
            } catch (\yii\db\Exception $exception) {
                echo 0;
            }
        }
        return false;
    }

    public function actionViewBeforeAfter($id)
    {
        if (Yii::$app->request->isAjax) {
        }
    }

    protected function findDieuTri($id)
    {
        $dieuTri = PhongKhamLichDieuTri::find()->where(['customer_id' => $id])->one();
        if ($dieuTri !== null) {
            return $dieuTri;
        }

        return false;
    }

    protected function findOrder($id)
    {
        $order = PhongKhamDonHang::find()->where(['customer_id' => $id])->one();
        if ($order !== null) {
            return $order;
        }

        return false;
    }

    protected function findModel($id)
    {
        $model = Clinic::findOne($id);
        if (($model !== null)) {
            return $model;
        }

        return false;
    }
}
