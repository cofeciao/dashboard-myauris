<?php

namespace backend\modules\clinic\controllers;

use backend\components\MyComponent;
use backend\controllers\CustomerController;
use backend\models\CustomerModel;
use backend\models\Dep365CustomerOnlineRemindCall;
use backend\modules\clinic\models\Clinic;
use backend\modules\clinic\models\DatHen;
use backend\modules\clinic\models\PhongKhamDonHang;
use backend\modules\clinic\models\PhongKhamDonHangTree;
use backend\modules\clinic\models\PhongKhamDonHangWOrder;
use backend\modules\clinic\models\PhongKhamDonHangWThanhToan;
use backend\modules\clinic\models\PhongKhamLichDieuTri;
use backend\modules\clinic\models\PhongKhamLichDieuTriTree;
use backend\modules\clinic\models\PhongKhamSanPham;
use backend\modules\clinic\models\search\ClinicSearch;
use backend\modules\customer\models\CustomerOnlineRemindCall;
use backend\modules\customer\models\Dep365CustomerOnline;
use common\helpers\MyHelper;
use Yii;
use yii\db\Exception;
use yii\db\Transaction;
use yii\helpers\Json;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii2mod\editable\EditableAction;

/**
 * Default controller for the `clinic` module
 */
class ClinicController extends CustomerController
{
    public function init()
    {
        parent::init();
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ClinicSearch();
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

    public function actionCheckLetan()
    {
        if (Yii::$app->request->isAjax) {
            $dataOption = Yii::$app->request->post('dataOption');
            $id = Yii::$app->request->post('id');
            $option = Yii::$app->request->post('option');

            $clinic = DatHen::find()->where(['id' => $id])->one();
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($clinic !== null) {
                switch ($option) {
                    case 'dathen':
                        $clinic->dat_hen = $dataOption;
                        if ($clinic->customer_come == null) {
                            $clinic->customer_come = time();
                            $clinic->customer_come_date = strtotime(date('d-m-Y', $clinic->customer_come));
                        }
                        break;
                    case 'direct':
                        $clinic->directsale = $dataOption;
                        break;
                }
                if ($clinic->save()) {
                    $status = '200';
                } else {
                    $status = '403';
                }
                return ['status' => $status];
            }
        }
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
                                'status' => 1,
                                'result' => $idGet != null ? 'Bạn đã cập nhật thành công.' : 'Bạn đã tạo mới thành công.',
                            ];
                        } else {
                            $transaction->rollBack();
                            return [
                                'status' => 0,
                                'result' => 'Thất bại, vui lòng liên hệ bộ phận kỹ thuật gấp.',//$model->getErrors(),
                            ];
                        }
                    } else {
                        $transaction->commit();
                        return [
                            'status' => 1,
                            'result' => $idGet != null ? 'Bạn đã cập nhật thành công.' : 'Bạn đã tạo mới thành công.',
                        ];
                    }
                } else {
                    $transaction->rollBack();
                    return [
                        'status' => 0,
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
//        $idGet = Yii::$app->request->get('id');
//        $model = $this->findDieuTri($idGet);
//        if ($model === false)
        $model = new PhongKhamLichDieuTri();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            $model->time_dieu_tri = strtotime($model->time_dieu_tri);
            $model->time_start = strtotime($model->time_start);
            $model->time_end = strtotime($model->time_end);
            $model->scenario = PhongKhamLichDieuTri::SCENARIO_TIMEEND;
            return Json::encode(\yii\widgets\ActiveForm::validate($model));
        }
    }

    public function actionOrderCustomer()
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
                $model->thanh_tien = str_replace('.', '', $model->thanh_tien);
                $model->chiet_khau = str_replace('.', '', $model->chiet_khau);
                $model->direct_sale_id = $customer->directsale;

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
                                if ($keys == 'thanh_tien') {
                                    $order->thanh_tien = str_replace('.', '', $item);
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
                                if ($keys == 'tien_thanh_toan') {
                                    $thanhtoan->tien_thanh_toan = str_replace('.', '', $item);
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
                            'status' => true,
                            'result' => $idGet != null ? 'Bạn đã cập nhật thành công.' : 'Bạn đã tạo mới thành công.',
                        ];
                    } else {
                        $transaction->rollBack();
                        return [
                            'status' => true,
                            'result' => 'Thất bại, vui lòng liên hệ bộ phận kỹ thuật gấp.'
                        ];
                    }
                } else {
                    $transaction->rollBack();
                    return [
                        'status' => true,
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

    public function actionRenderAndUpdate($id)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_HTML;
            $idAjax = Yii::$app->request->post('id');
            $ids = $idAjax == null ? $id : $idAjax;
            $model = $this->findModel($ids);
            $model->scenario = Clinic::SCENARIO_UPDATE;
            $modelRemind = CustomerOnlineRemindCall::find()
                ->where(['type' => CustomerOnlineRemindCall::TYPE_CUSTOMER_ONLINE, 'customer_id' => $model->primaryKey, 'status' => $model->status, 'status_fail' => $model->status_fail, 'dat_hen' => $model->dat_hen])
                ->published()
                ->orderBy(['id' => SORT_DESC])
                ->one();
            if ($modelRemind == null) {
                $modelRemind = new CustomerOnlineRemindCall();
            }

            if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                if ($model->validate()) {
                    $transaction = Yii::$app->db->beginTransaction(
                        Transaction::SERIALIZABLE
                    );

                    if ($model->dat_hen == 2) {
                        $model->customer_come = null;
                        $model->customer_come_date = null;
                        $model->customer_come_time_to = null;
                    }
                    if ($model->dat_hen == 1) {
                        $model->customer_come = strtotime($model->customer_come);
                        $model->customer_come_date = strtotime(date('d-m-Y', $model->customer_come));
                    }
                    try {
                        if (!$model->save()) {
                            $transaction->rollBack();
                            return [
                                'status' => $model->getErrors(),
                                'result' => Yii::$app->params['update-danger'],
                            ];
                        }
                        if ($model->dat_hen == Dep365CustomerOnline::DAT_HEN_DEN) {
                            if ($modelRemind->primaryKey != null) {
                                $modelRemind->remind_call_status = Dep365CustomerOnlineRemindCall::STATUS_DISABLED;
                                if (!$modelRemind->save()) {
                                    $transaction->rollBack();
                                    return [
                                        'status' => $modelRemind->getErrors(),
                                        'result' => Yii::$app->params['update-danger']
                                    ];
                                }
                            }
                        } elseif ($model->dat_hen == Dep365CustomerOnline::DAT_HEN_KHONG_DEN) {
                            if ($modelRemind->primaryKey == null) {
                                $modelRemind->customer_id = $model->primaryKey;
                                $modelRemind->status = $model->status;
                                $modelRemind->status_fail = $model->status_fail;
                                $modelRemind->dat_hen = $model->dat_hen;
                                $modelRemind->type = CustomerOnlineRemindCall::TYPE_CUSTOMER_ONLINE;
                            }
                            $modelRemind->remind_call_time = strtotime(date('d-m-Y'));
                            if (!$modelRemind->save()) {
                                $transaction->rollBack();
                                return [
                                    'status' => $modelRemind->getErrors(),
                                    'result' => Yii::$app->params['update-danger']
                                ];
                            }
                        }
                        $transaction->commit();
                        return [
                            'status' => 1,
                            'result' => Yii::$app->params['update-success'],
                        ];
                    } catch (Exception $ex) {
                        return [
                            'status' => $ex->getMessage(),
                            'result' => Yii::$app->params['update-danger'],
                        ];
                    }
                } else {
                    return [
                        'status' => 0,
                        'result' => 'Lỗi dữ liệu'
                    ];
                }
            }
            return $this->renderAjax('create-ajax', [
                'model' => $model,
            ]);
        }
    }

    public function actionValidateRenderAndUpdate($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = $this->findModel($id);
            $model->scenario = Clinic::SCENARIO_UPDATE;
            if ($model->load(Yii::$app->request->post())) {
                return Json::encode(\yii\widgets\ActiveForm::validate($model));
            }
        }
    }

    public function actionCreate()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_HTML;

            $model = new Clinic();
            $model->dat_hen = 1;
            if ($model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $model->customer_come = strtotime($model->customer_come);
                $model->customer_come_date = strtotime(date('d-m-Y', $model->customer_come));
                if ($model->validate()) {
                    try {
                        if (!$model->save()) {
                            return [
                                'status' => 0,
                                'result' => Yii::$app->params['create-danger'],
                                'error' => $model->getErrors()
                            ];
                        }
                        $id = $model->primaryKey;
                        if (strlen(Yii::$app->user->identity->permission_coso) == 1) {
                            $coso = '0' . Yii::$app->user->identity->permission_coso;
                        } else {
                            $coso = Yii::$app->user->identity->permission_coso;
                        }

                        $model->updateAttributes(['customer_code' => 'AUR' . $coso . '-' . $id]);
                        return [
                            'status' => 1,
                            'result' => Yii::$app->params['create-success'],
                        ];
                    } catch (Exception $ex) {
                        return [
                            'status' => 0,
                            'result' => Yii::$app->params['create-danger'],
                            'error' => $ex->getMessage()
                        ];
                    }
                } else {
                    return [
                        'status' => 0,
                        'result' => 'Lỗi dữ liệu'
                    ];
                }
            }
            return $this->renderAjax('create-ajax', [
                'model' => $model,
            ]);
        }
    }

    public function actionValidateCreate()
    {
        if (Yii::$app->request->isAjax) {
            $model = new Clinic();
            if ($model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
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
