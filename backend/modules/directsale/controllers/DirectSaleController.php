<?php

namespace backend\modules\directsale\controllers;

use backend\components\WarningComponent;
use backend\models\CustomerModel;
use backend\models\Dep365CustomerOnlineRemindCall;
use backend\modules\clinic\models\Clinic;
use backend\modules\clinic\models\CustomerImages;
use backend\modules\clinic\models\PhongKhamDonHang;
use backend\modules\clinic\models\PhongKhamDonHangTree;
use backend\modules\clinic\models\PhongKhamDonHangWOrder;
use backend\modules\clinic\models\PhongKhamDonHangWThanhToan;
use backend\modules\clinic\models\PhongKhamKhuyenMai;
use backend\modules\clinic\models\PhongKhamLichDieuTri;
use backend\modules\clinic\models\PhongKhamLichDieuTriTree;
use backend\modules\clinic\models\PhongKhamSanPham;
use backend\modules\customer\models\CustomerOnlineRemindCall;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\customer\models\Dep365CustomerOnlineCome;
use backend\modules\directsale\models\DirectSaleRemindCall;
use Yii;
use backend\modules\directsale\models\DirectSaleModel;
use backend\modules\directsale\models\search\DirectSaleSearch;
use backend\components\MyController;
use yii\db\Exception;
use yii\db\Transaction;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\MyComponent;
use yii\web\Response;

/**
 * DirectSaleController implements the CRUD actions for DirectSaleModel model.
 */
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
            $listAccept = ArrayHelper::map(Dep365CustomerOnlineCome::find()->where(['accept' => Dep365CustomerOnlineCome::STATUS_ACCEPT])->all(), 'id', 'name');
            if (!array_key_exists($customer->customer_come_time_to, $listAccept)) {
                $error = 'Khách không đồng ý làm, không thể tạo đơn hàng!';
                return $this->renderAjax('_error', [
                    'error' => $error
                ]);
            }

            $model = new PhongKhamDonHang();

            $orderData = PhongKhamDonHangWOrder::find()->where(['phong_kham_don_hang_id' => $model->id])->all();

            if ($model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $dataOder = $model->customer_order;
                $model->customer_order = json_encode($model->customer_order);
                $model->chiet_khau = str_replace('.', '', $model->chiet_khau);
                $model->direct_sale_id = $customer->directsale;

                $transaction = Yii::$app->db->beginTransaction(
                    Transaction::SERIALIZABLE
                );
                if ($model->save()) {
                    $coso = Yii::$app->user->identity->permission_coso;
                    if (strlen($coso) < 0) {
                        $coso = '0' . $coso;
                    }
                    $model->updateAttributes([
                        'order_code' => 'AUR' . $coso . '-HD' . $model->primaryKey
                    ]);
                    $donhangTree = new PhongKhamDonHangTree();
                    $arr = $model->getAttributes();
                    $arr['id_order'] = $model->getPrimaryKey();
                    unset($arr['id']);
                    unset($arr['customer_order']);

                    foreach ($arr as $key => $item) {
                        $donhangTree->$key = $item;
                    }

                    if ($donhangTree->save()) {
                        //Thêm order
                        $arrID = [];
                        $total = 0;
                        foreach ($dataOder as $value) {
                            $order = PhongKhamDonHangWOrder::find()->where(['id' => $value['id']])->one();
                            if ($order === null) {
                                $order = new PhongKhamDonHangWOrder();
                            }
                            $order->customer_id = $model->customer_id;
//                            var_dump($order);
                            foreach ($value as $keys => $item) {
                                if ($keys == 'id' || $keys == 'dich_vu') {
                                    continue;
                                }
                                if ($keys == 'san_pham') {
                                    $sanpham = PhongKhamSanPham::find()->joinWith(['dichVuHasOne'])->where([PhongKhamSanPham::tableName() . '.id' => $item])->published()->one();
                                    $order->dich_vu = $sanpham == null || $sanpham->dichVuHasOne == null ? null : (string)$sanpham->dichVuHasOne->id;
                                }
                                if ($keys == 'thanh_tien') {
                                    $order->thanh_tien = str_replace('.', '', $item);
                                    $total += $order->thanh_tien;
                                    continue;
                                }
                                if ($keys == 'chiet_khau_order') {
                                    $order->chiet_khau_order = str_replace('.', '', $item);
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
                        $model->updateAttributes([
                            'thanh_tien' => $total
                        ]);

                        $arrIdNotIn = PhongKhamDonHangWOrder::find()->where(['not in', 'id', $arrID])->andWhere(['in', 'phong_kham_don_hang_id', $model->primaryKey])->all();
                        foreach ($arrIdNotIn as $key => $val) {
                            $orderDel = PhongKhamDonHangWOrder::findOne($val->id);
                            if (!$orderDel->delete()) {
                                $transaction->rollBack();
                            }
                        }

                        $transaction->commit();
                        return [
                            'status' => '200',
                            'order_id' => $model->id,
                            'result' => Yii::$app->params['create-success'],
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

            $listKhuyenMai = PhongKhamKhuyenMai::getListKhuyenMai();

            return $this->renderAjax('_order', [
                'model' => $model,
                'customer' => $customer,
                'orderData' => $orderData,
                'listKhuyenMai' => $listKhuyenMai
            ]);
        }
    }

    public function actionValidateOrder()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = new PhongKhamDonHang();

            if ($model->load(Yii::$app->request->post())) {
                $check = $model->customer_order;
                $model->customer_order = json_encode($model->customer_order);
                $model->thanh_tien = str_replace('.', '', $model->thanh_tien);

                foreach ($check as $key => $item) {
                    /*if ($item['dich_vu'] == 0) {
                        $model->dich_vu = 0;
                        $model->scenario = 'checkOrder';
                    } else {
                        $model->dich_vu = $item['dich_vu'];
                    }*/
                    if ($item['san_pham'] == 0) {
                        $model->s_p = 0;
                        $model->dich_vu = null;
                        $model->scenario = 'checkOrder';
                    } else {
                        $sanpham = PhongKhamSanPham::find()->joinWith(['dichVuHasOne'])->where([PhongKhamSanPham::tableName() . '.id' => $item['san_pham']])->published()->one();
                        $model->dich_vu = $sanpham == null || $sanpham->dichVuHasOne == null ? null : $sanpham->dichVuHasOne->id;
                        $model->s_p = $item['san_pham'];
                    }
                }

                return \yii\widgets\ActiveForm::validate($model);
            }
            return [];
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

    public function actionUpdate($id)
    {
        if (Yii::$app->request->isAjax) {
//            $id = Yii::$app->request->post('id');
            $customer = DirectSaleModel::find()->where(['id' => $id])->one();
            if ($customer === null) {
                return $this->redirect(['index']);
            }
            $remindCall = DirectSaleRemindCall::find()->where(['type' => DirectSaleRemindCall::TYPE_DIRECT_SALE, 'customer_id' => $customer->primaryKey, 'status' => CustomerModel::STATUS_DH, 'dat_hen' => Dep365CustomerOnline::DAT_HEN_DEN, 'customer_come_time_to' => $customer->customer_come_time_to])->published()->one();
            if ($remindCall != null) {
                $customer->remind_call_time = $remindCall->remind_call_time != null ? date('d-m-Y H:i', $remindCall->remind_call_time) : null;
                $customer->remind_call_note = $remindCall->note;
            }
            if ($customer->customer_come != null) {
                $customer->customer_come = date('d-m-Y H:i', $customer->customer_come);
            }
            $statusAccept = ArrayHelper::map(Dep365CustomerOnlineCome::find()->where(['accept' => Dep365CustomerOnlineCome::STATUS_ACCEPT])->all(), 'id', 'name');

//            Yii::$app->response->format = Response::FORMAT_HTML;
            return $this->renderAjax('update', [
                'model' => $customer,
                'statusAccept' => $statusAccept
            ]);
        }
    }

    public function actionValidateUpdate()
    {
        $idGet = Yii::$app->request->get('id');
        $model = DirectSaleModel::find()->where(['id' => $idGet])->one();

        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }
    }

    public function actionUpdateSubmit()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $idGet = Yii::$app->request->get('id');
            $model = DirectSaleModel::find()->where(['id' => $idGet])->one();
            if ($model == null) {
                return ['status' => 404, 'result' => 'Không tìm thấy dữ liệu'];
            }

            $modelRemindCustomer = CustomerOnlineRemindCall::find()
                ->where(['type' => CustomerOnlineRemindCall::TYPE_CUSTOMER_ONLINE, 'customer_id' => $model->primaryKey])
                ->published()
                ->orderBy(['id' => SORT_DESC])
                ->one();
            if ($modelRemindCustomer == null) {
                $modelRemindCustomer = new CustomerOnlineRemindCall();
            }
            $modelRemindDirectSale = DirectSaleRemindCall::find()
                ->where(['type' => DirectSaleRemindCall::TYPE_DIRECT_SALE, 'customer_id' => $model->primaryKey])
                ->published()
                ->orderBy(['id' => SORT_DESC])
                ->one();
            if ($modelRemindDirectSale == null) {
                $modelRemindDirectSale = new DirectSaleRemindCall();
            }

            if ($model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                if ($model->validate()) {
                    $transaction = Yii::$app->db->beginTransaction(
                        Transaction::SERIALIZABLE
                    );
                    try {
                        if ($model->customer_come != null) {
                            $model->customer_come = strtotime($model->customer_come);
                        }
                        if (!$model->save()) {
                            $transaction->rollBack();
                            return ['status' => 400, 'result' => Yii::$app->params['update-danger'], 'error' => $model->getErrors()];
                        }
                        $listAccept = ArrayHelper::map(Dep365CustomerOnlineCome::find()->where(['accept' => Dep365CustomerOnlineCome::STATUS_ACCEPT])->all(), 'id', 'name');

                        /*
                         * trạng thái đặt hẹn = ĐẶT HẸN KHÔNG ĐẾN
                         * => lưu remind call cho CUSTOMER ONLINE
                         * => cập nhật remind_call_status = DISABLED cho DIRECT SALE (nếu tồn tại remind call)
                         *
                         * trạng thái đặt hẹn = ĐẶT HẸN ĐẾN:
                         * => cập nhật remind_call_status = DISABLED cho CUSTOMER ONLINE (nếu tồn tại remind call)
                         * + khách làm dịch vụ => cập nhật remind_call_status = DISABLED cho DIRECT SALE (nếu tồn tại remind call)
                         * + khách không làm dịch vụ => lưu lại remind call cho DIRECT SALE
                         */
                        if ($model->dat_hen == Dep365CustomerOnline::DAT_HEN_KHONG_DEN) {
                            /* Đặt hẹn không đến */
                            if ($modelRemindCustomer->primaryKey == null) {
                                $modelRemindCustomer->customer_id = $model->primaryKey;
                                $modelRemindCustomer->type = CustomerOnlineRemindCall::TYPE_CUSTOMER_ONLINE;
                                $modelRemindCustomer->remind_call_time = strtotime(date('d-m-Y', strtotime('+1day')));
                            }
                            $modelRemindCustomer->status = CustomerModel::STATUS_DH;
                            $modelRemindCustomer->dat_hen = Dep365CustomerOnline::DAT_HEN_KHONG_DEN;
                            if (!$modelRemindCustomer->save()) {
                                $transaction->rollBack();
                                return [
                                    'status' => 400,
                                    'result' => Yii::$app->params['update-danger'],
                                    'error' => $modelRemindCustomer->getErrors()
                                ];
                            }
                            if ($modelRemindDirectSale->primaryKey != null) {
                                $modelRemindDirectSale->remind_call_status = Dep365CustomerOnlineRemindCall::STATUS_DISABLED;
                                if (!$modelRemindDirectSale->save()) {
                                    $transaction->rollBack();
                                    return [
                                        'status' => 400,
                                        'result' => Yii::$app->params['update-danger'],
                                        'error' => $modelRemindDirectSale->getErrors()
                                    ];
                                }
                            }
                        } else {
                            /* Đặt hẹn đến */
                            if ($modelRemindCustomer->primaryKey != null) {
                                $modelRemindCustomer->remind_call_status = Dep365CustomerOnlineRemindCall::STATUS_DISABLED;
                                if (!$modelRemindCustomer->save()) {
                                    $transaction->rollBack();
                                    return [
                                        'status' => 400,
                                        'result' => Yii::$app->params['update-danger'],
                                        'error' => $modelRemindCustomer->getErrors()
                                    ];
                                }
                            }
                            if (array_key_exists($model->customer_come_time_to, $listAccept)) {
                                /* Khách đồng ý làm dịch vụ */
                                if ($modelRemindDirectSale->primaryKey != null) {
                                    $modelRemindDirectSale->customer_come_time_to = $model->customer_come_time_to;
                                    $modelRemindDirectSale->remind_call_status = Dep365CustomerOnlineRemindCall::STATUS_DISABLED;
                                    if (!$modelRemindDirectSale->save()) {
                                        $transaction->rollBack();
                                        return [
                                            'status' => 400,
                                            'result' => Yii::$app->params['update-danger'],
                                            'error' => $modelRemindDirectSale->getErrors()
                                        ];
                                    }
                                }
                            } else {
                                /* Khách không đồng ý làm dịch vụ */
                                if ($modelRemindDirectSale->primaryKey == null) {
                                    $modelRemindDirectSale->customer_id = $model->primaryKey;
                                    $modelRemindDirectSale->status = CustomerModel::STATUS_DH;
                                    $modelRemindDirectSale->dat_hen = Dep365CustomerOnline::DAT_HEN_DEN;
                                }
                                $modelRemindDirectSale->customer_come_time_to = $model->customer_come_time_to;
                                $modelRemindDirectSale->remind_call_time = $model->remind_call_time != null ? strtotime($model->remind_call_time) : strtotime(date('d-m-Y'));
                                if (!$modelRemindDirectSale->save()) {
                                    $transaction->rollBack();
                                    return [
                                        'status' => 400,
                                        'result' => Yii::$app->params['update-danger'],
                                        'error' => $modelRemindDirectSale->getErrors()
                                    ];
                                }
                            }
                        }

                        $transaction->commit();
                        return ['status' => 200, 'result' => Yii::$app->params['update-success']];
                    } catch (Exception $ex) {
                        $transaction->rollBack();
                        return ['status' => 400, 'result' => Yii::$app->params['update-danger'], 'error' => $ex->getMessage()];
                    }
                } else {
                    return ['status' => 400, 'result' => 'Lỗi dữ liệu', 'error' => $model->getErrors()];
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

    public function actionShowBeaf($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = CustomerImages::getImageBeforeAfter($id);

            if ($model['before'] == null || $model['after'] == null) {
                return $this->renderAjax('_error', [
                    'error' => 'Hãy cập nhật hình before - after'
                ]);
            }

            return $this->renderAjax('view-beaf', [
                'model' => $model
            ]);
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
