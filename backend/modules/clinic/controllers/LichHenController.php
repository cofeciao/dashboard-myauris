<?php

namespace backend\modules\clinic\controllers;

use backend\components\MyComponent;
use backend\components\WarningComponent;
use backend\controllers\CustomerController;
use backend\models\CanhBao;
use backend\models\CustomerModel;
use backend\models\Dep365CustomerOnlineRemindCall;
use backend\modules\clinic\models\Clinic;
use backend\modules\clinic\models\CustomerDanhGia;
use backend\modules\clinic\models\DatHen;
use backend\modules\clinic\models\PhongKhamDichVu;
use backend\modules\clinic\models\PhongKhamDonHang;
use backend\modules\clinic\models\PhongKhamDonHangTree;
use backend\modules\clinic\models\PhongKhamDonHangWOrder;
use backend\modules\clinic\models\PhongKhamKhuyenMai;
use backend\modules\clinic\models\PhongKhamLichDieuTri;
use backend\modules\clinic\models\PhongKhamSanPham;
use backend\modules\clinic\models\search\LichHenSearch;
use backend\modules\customer\models\CustomerOnlineRemindCall;
use backend\modules\customer\models\CustomerToken;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\customer\models\Dep365CustomerOnlineCome;
use backend\modules\directsale\models\DirectSaleRemindCall;
use backend\modules\setting\models\Setting;
use backend\modules\user\models\User;
use backend\modules\user\models\UserTimelineModel;
use common\models\UserProfile;
use GuzzleHttp\Client;
use Yii;
use yii\db\Exception;
use yii\db\Transaction;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Response;
use yii\widgets\ActiveForm;

class LichHenController extends CustomerController
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
        $searchModel = new LichHenSearch();
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
            $dat_hen_old = $clinic->getOldAttribute('dat_hen');
            $directsale_old = $clinic->getOldAttribute('directsale');

            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($clinic !== null) {
                $transaction = Yii::$app->db->beginTransaction(
                    Transaction::SERIALIZABLE
                );

                $listAccept = ArrayHelper::map(Dep365CustomerOnlineCome::find()->published()->andWhere(['accept' => Dep365CustomerOnlineCome::STATUS_ACCEPT])->all(), 'id', 'id');

                $modelRemindCustomer = CustomerOnlineRemindCall::find()
                    ->where(['type' => CustomerOnlineRemindCall::TYPE_CUSTOMER_ONLINE, 'customer_id' => $clinic->primaryKey])
                    ->published()
                    ->orderBy(['id' => SORT_DESC])
                    ->one();
                if ($modelRemindCustomer == null) {
                    $modelRemindCustomer = new CustomerOnlineRemindCall();
                }

                $modelRemindDirectSale = DirectSaleRemindCall::find()
                    ->where(['type' => DirectSaleRemindCall::TYPE_DIRECT_SALE, 'customer_id' => $clinic->primaryKey])
                    ->published()
                    ->orderBy(['id' => SORT_DESC])
                    ->one();
                if ($modelRemindDirectSale == null) {
                    $modelRemindDirectSale = new DirectSaleRemindCall();
                }

                $modelRemind = CustomerOnlineRemindCall::find()
                    ->where(['type' => CustomerOnlineRemindCall::TYPE_CUSTOMER_ONLINE, 'customer_id' => $clinic->primaryKey, 'status' => $clinic->status, 'status_fail' => $clinic->status_fail, 'dat_hen' => $clinic->dat_hen])
                    ->published()
                    ->orderBy(['id' => SORT_DESC])
                    ->one();
                if ($modelRemind == null) {
                    $modelRemind = new CustomerOnlineRemindCall();
                }

                switch ($option) {
                    case 'dathen':
                        $clinic->dat_hen = $dataOption;
                        if ($dataOption == Clinic::DA_DEN && $clinic->customer_come == null) {
                            $clinic->customer_come = time();
                            $clinic->customer_come_date = strtotime(date('d-m-Y', $clinic->customer_come));
                        }
                        if ($modelRemind->primaryKey == null) {
                            $modelRemind->customer_id = $clinic->primaryKey;
                            $modelRemind->status = $clinic->status;
                            $modelRemind->status_fail = $clinic->status_fail;
                            $modelRemind->dat_hen = $clinic->dat_hen;
                            $modelRemind->type = CustomerOnlineRemindCall::TYPE_CUSTOMER_ONLINE;
                        }
                        $modelRemind->permission_user = $clinic->permission_user;
                        $modelRemind->created_by = $clinic->permission_user;
                        $modelRemind->remind_call_time = strtotime(date('d-m-Y'));
                        if (!$modelRemind->save()) {
                            $transaction->rollBack();
                            return [
                                'status' => $modelRemind->getErrors(),
                                'result' => Yii::$app->params['update-danger']
                            ];
                        }
                        break;
                    case 'direct':
                        $clinic->directsale = $dataOption;
                        break;
                    case 'dichvu':
                        $clinic->id_dich_vu = $dataOption;
                        break;
                    case 'co_so':
                        $user = new User();
                        $roleName = $user->getRoleName(Yii::$app->user->id);
                        if (!in_array($roleName, [User::USER_DEVELOP, User::USER_ADMINISTRATOR, User::USER_QUANLY_PHONGKHAM])) {
                            return [
                                'status' => 403
                            ];
                        }
                        $clinic->co_so = $dataOption;
                        break;
                }
                if (!$clinic->save()) {
                    $transaction->rollBack();
                    return ['status' => 403];
                }
                if ($clinic->dat_hen == Dep365CustomerOnline::DAT_HEN_DEN) {
                    /* Đặt hẹn đến */
                    if ($modelRemindCustomer->primaryKey != null) {
                        $modelRemindCustomer->remind_call_status = Dep365CustomerOnlineRemindCall::STATUS_DISABLED;
                        if (!$modelRemindCustomer->save()) {
                            $transaction->rollBack();
                            return [
                                'status' => $modelRemindCustomer->getErrors(),
                                'result' => Yii::$app->params['update-danger']
                            ];
                        }
                    }
                    if (array_key_exists($clinic->customer_come_time_to, $listAccept)) {
                        /* Khách đồng ý làm dịch vụ */
                        if ($modelRemindDirectSale->primaryKey != null) {
                            $modelRemindDirectSale->remind_call_status = Dep365CustomerOnlineRemindCall::STATUS_DISABLED;
                            if (!$modelRemindDirectSale->save()) {
                                $transaction->rollBack();
                                return [
                                    'status' => $modelRemindDirectSale->getErrors(),
                                    'result' => Yii::$app->params['update-danger']
                                ];
                            }
                        }
                    } else {
                        /* Khách không đồng ý làm dịch vụ */
                        if ($modelRemindDirectSale->primaryKey == null) {
                            $modelRemindDirectSale->customer_id = $clinic->primaryKey;
                            $modelRemindDirectSale->status = CustomerModel::STATUS_DH;
                            $modelRemindDirectSale->dat_hen = Dep365CustomerOnline::DAT_HEN_DEN;
                        }
                        $modelRemindDirectSale->remind_call_time = strtotime(date('d-m-Y'));
                        if (!$modelRemindDirectSale->save()) {
                            $transaction->rollBack();
                            return [
                                'status' => $modelRemindDirectSale->getErrors(),
                                'result' => Yii::$app->params['update-danger']
                            ];
                        }
                    }
                } elseif ($clinic->dat_hen == Dep365CustomerOnline::DAT_HEN_KHONG_DEN) {
                    if ($modelRemind->primaryKey == null) {
                        $modelRemind->customer_id = $clinic->primaryKey;
                        $modelRemind->status = $clinic->status;
                        $modelRemind->status_fail = $clinic->status_fail;
                        $modelRemind->dat_hen = $clinic->dat_hen;
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

                $status = '200';

                $dat_hen_new = $clinic->getAttribute('dat_hen');
                if ($dat_hen_new == Dep365CustomerOnline::DAT_HEN_DEN && $dat_hen_old != Dep365CustomerOnline::DAT_HEN_DEN) {
                    $customerToken = CustomerToken::find()->where([
                        'customer_id' => $clinic->primaryKey,
                        'type' => CustomerToken::TYPE_CUSTOMER_FEEDBACK,
                        'status' => CustomerToken::STATUS_DISABLED
                    ])->one();
                    /* CÓ TOKEN RỒI THÌ KHÔNG TẠO NỮA */
                    if ($customerToken == null) {
                        if (!CustomerToken::quickCreate($clinic->primaryKey, null, null, CustomerToken::TYPE_CUSTOMER_FEEDBACK)) {
                            $transaction->rollBack();
                            return [
                                'status' => 400,
                                'result' => Yii::$app->params['update-danger']
                            ];
                        } else {
                            $cache = Yii::$app->cache;
                            $key = 'redis-screen-online';
                            $cache->set($key, [
                                'customer_id' => $clinic->primaryKey,
                                'status' => UserTimelineModel::ACTION_THAM_KHAM
                            ]);
                        }
                    }
                } else {
                    $cache = Yii::$app->cache;
                    $key = 'redis-screen-online';
                    $cache->set($key, [
                        'srcOnlTimeline' => UserTimelineModel::ACTION_CAP_NHAT,
                    ]);
                }

                $directsale_new = $clinic->getAttribute('directsale');
                if ($directsale_old != null && $directsale_new != null && $directsale_new != $directsale_old) {
                    /* THÔNG BÁO TỚI APP DICRECT SALE ĐƯỢC PHÂN CÔNG CHĂM SÓC KHÁCH HÀNG */
                    $setting = Setting::find()->where(['key_value' => 'khach_phan_cong_cho_directsale'])->one();
                    if ($setting != null) {
                        if (CONSOLE_HOST == false/*\Yii::$app->request->getUserIP() == '127.0.0.1'*/) {
                            $client = new Client([
                                'verify' => Url::to('@backend/modules/clinic/token/cacert.pem')
                            ]);
                        } else {
                            $client = new Client();
                        }
                        if ($clinic->full_name != null) {
                            $customer = $clinic->full_name;
                        } elseif ($clinic->forename != null) {
                            $customer = $clinic->forename;
                        } else {
                            $customer = $clinic->name;
                        }
                        $content = str_replace('{$customer}', $customer, $setting->value);
                        $client->request('POST', 'https://api.myauris.vn/api/CreateNoti', [
                            'verify' => false,
                            'form_params' => [
                                'name' => $setting->param,
                                'content' => $content,
                                'description' => $content,
                                'user_id' => $directsale_new,
                                'customer_id' => $clinic->primaryKey,
                                'type' => 3
                            ]
                        ]);
                    }
                }

                $transaction->commit();
                return ['status' => $status, 'data' => $clinic->getAttributes(), 'remind' => $modelRemind->getAttributes()];
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

    public function actionDanhGia()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $id = Yii::$app->request->post('id');
            $customer = $this->findModel($id);
            if ($customer == false || $customer == null) {
                $status = 201;
                $msg = 'Khách hàng không tồn tại';
                return ['status' => $status, 'msg' => $msg];
            }

            $dataIsset = CustomerDanhGia::find()->where(['customer_id' => $id, 'ngay_tao' => strtotime(date('d-m-Y'))])->one();

            $danhGia = new CustomerDanhGia();
            if ($dataIsset !== null) {
                $danhGia = CustomerDanhGia::findOne($dataIsset->id);
            }
            $danhGia->customer_id = $id;
            $danhGia->danh_gia = 1;
            $coso = Yii::$app->user->identity->permission_coso;
            $danhGia->co_so = $coso == null ? 1 : $coso;

            if ($danhGia->save()) {
                $status = 200;
                $msg = 'Hãy nói khách hàng đánh giá trên màn.';
            } else {
                var_dump($danhGia->getErrors());
                die;
                $status = 202;
                $msg = 'Lỗi kỹ thuật, hãy liên hệ bộ phận kỹ thuật.';
            }

            return ['status' => $status, 'msg' => $msg];
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
            $listAccept = ArrayHelper::map(Dep365CustomerOnlineCome::find()->where(['accept' => Dep365CustomerOnlineCome::STATUS_ACCEPT])->all(), 'id', 'name');
            if (!array_key_exists($customer->customer_come_time_to, $listAccept)) {
                $error = 'Khách không đồng ý làm, không thể tạo đơn hàng!';
                return $this->renderAjax('_error', [
                    'error' => $error
                ]);
            }

            $model = new PhongKhamDonHang();

            $orderData = PhongKhamDonHangWOrder::find()->where(['phong_kham_don_hang_id' => $model->id])->all();
            $user_timeline = new UserTimelineModel();
            $user_timeline->action = [UserTimelineModel::ACTION_TAO, UserTimelineModel::ACTION_DON_HANG];

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
                            $user_timeline->customer_id = $model->customer_id;
                            if (!$user_timeline->save()) {
                                $transaction->rollBack();
                            }
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
                            'status' => true,
                            'order_id' => $model->id,
                            'result' => Yii::$app->params['create-success'],
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
            $idGet = Yii::$app->request->get('id');
            /*$model = $this->findOrder($idGet);
            if ($model === false) return [
                'abc' => $idGet
            ];*/
            $model = new PhongKhamDonHang();

            if ($model->load(Yii::$app->request->post())) {
//            $sanpham = PhongKhamSanPham::find()->joinWith(['dichVuHasOne'])->where([PhongKhamSanPham::tableName() . '.id' => $model->san_pham])->published()->one();
//            $model->dich_vu = $sanpham == null || $sanpham->dichVuHasOne == null ? null : $sanpham->dichVuHasOne->id;
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
        }
    }

    public function actionRenderAndUpdate($id)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_HTML;
            $idAjax = Yii::$app->request->post('id');
            $ids = $idAjax == null ? $id : $idAjax;
            $model = $this->findModel($ids);
            $model->check_dich_vu = '1';
            $user = new User();
            $roleName = $user->getRoleName(Yii::$app->user->id);
            if (in_array($roleName, [User::USER_DEVELOP, User::USER_ADMINISTRATOR])) {
                $model->scenario = Clinic::SCENARIO_ADMIN;
            } else {
                $model->scenario = Clinic::SCENARIO_UPDATE;
            }
            if ($model->ngay_dong_y_lam != null) {
                $model->ngay_dong_y_lam = date('d-m-Y', $model->ngay_dong_y_lam);
            }
            $user_timeline = new UserTimelineModel();
            $user_timeline->action = UserTimelineModel::ACTION_CAP_NHAT;
            $user_timeline->customer_id = $model->primaryKey;

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

            $modelRemind = CustomerOnlineRemindCall::find()
                ->where(['type' => CustomerOnlineRemindCall::TYPE_CUSTOMER_ONLINE, 'customer_id' => $model->primaryKey, 'status' => $model->status, 'status_fail' => $model->status_fail, 'dat_hen' => $model->dat_hen])
                ->published()
                ->orderBy(['id' => SORT_DESC])
                ->one();
            if ($modelRemind == null) {
                $modelRemind = new CustomerOnlineRemindCall();
            }

            $listAccept = ArrayHelper::map(Dep365CustomerOnlineCome::find()->published()->andWhere(['accept' => Dep365CustomerOnlineCome::STATUS_ACCEPT])->all(), 'id', 'name');

            if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                if ($model->validate()) {
                    $transaction = Yii::$app->db->beginTransaction(
                        Transaction::SERIALIZABLE
                    );

                    if ($model->dat_hen == Dep365CustomerOnline::DAT_HEN_KHONG_DEN) {
                        $model->customer_come = null;
                        $model->customer_come_date = null;
                        $model->customer_come_time_to = null;
                        $user_timeline->action = [UserTimelineModel::ACTION_CAP_NHAT, UserTimelineModel::ACTION_TRANG_THAI, UserTimelineModel::ACTION_DAT_HEN];
                    }
                    if ($model->dat_hen == Dep365CustomerOnline::DAT_HEN_DEN) {
                        $model->customer_come = strtotime($model->customer_come);
                        $model->customer_come_date = strtotime(date('d-m-Y', $model->customer_come));
                        $user_timeline->action = [UserTimelineModel::ACTION_CAP_NHAT, UserTimelineModel::ACTION_TRANG_THAI, UserTimelineModel::ACTION_THAM_KHAM];
                    }
                    try {
                        $dat_hen_old = $model->getOldAttribute('dat_hen');
                        $directsale_old = $model->getOldAttribute('directsale');
                        if (!$model->save()) {
                            $transaction->rollBack();
                            return [
                                'status' => $model->getErrors(),
                                'result' => Yii::$app->params['update-danger'],
                            ];
                        }

                        if ($model->dat_hen == Dep365CustomerOnline::DAT_HEN_DEN) {
                            /* Đặt hẹn đến */
                            if ($modelRemindCustomer->primaryKey != null) {
                                $modelRemindCustomer->remind_call_status = Dep365CustomerOnlineRemindCall::STATUS_DISABLED;
                                if (!$modelRemindCustomer->save()) {
                                    $transaction->rollBack();
                                    return [
                                        'status' => $modelRemindCustomer->getErrors(),
                                        'result' => Yii::$app->params['update-danger']
                                    ];
                                }
                            }
                            if (array_key_exists($model->customer_come_time_to, array_keys($listAccept))) {
                                /* Khách đồng ý làm dịch vụ */
                                if ($modelRemindDirectSale->primaryKey != null) {
                                    $modelRemindDirectSale->remind_call_status = Dep365CustomerOnlineRemindCall::STATUS_DISABLED;
                                    if (!$modelRemindDirectSale->save()) {
                                        $transaction->rollBack();
                                        return [
                                            'status' => $modelRemindDirectSale->getErrors(),
                                            'result' => Yii::$app->params['update-danger']
                                        ];
                                    }
                                }
                                //Tạo cảnh báo thành công
                                /* if ($model->directsale != null) {
                                     $directsale = UserProfile::find()->where(['user_id' => $model->directsale])->one();
                                     if ($directsale !== null)
                                         $name = $directsale->fullname == null ? null : $directsale->fullname;
                                 }

                                 //Tinh so lan thanh cong
                                 $khachOkOfDirect = CustomerModel::find()
                                     ->where([CustomerModel::tableName() . '.directsale' => $model->directsale])
                                     ->andWhere(['in', 'customer_come_time_to', $listAccept])
                                     ->orderBy(['id' => SORT_DESC])
                                     ->one();

                                 $failOfDirect = CustomerModel::find()->where(['between', CustomerModel::tableName() . '.customer_come', $khachOkOfDirect->customer_come, time()])
                                     ->andWhere(['dat_hen' => CustomerModel::DA_DEN])
                                     ->andWhere(['in', 'customer_come_time_to', $listAccept])
                                     ->andWhere(['directsale' => $model->directsale])
 //                                echo $failOfDirect->createCommand()->getRawSql();die;
                                     ->count();
                                 if ($failOfDirect >= 3) {
                                     $canhBao = new CanhBao();
                                     $canhBao->type = CanhBao::DIRECT_SALE_CHOT_THANH_CONG;
                                     $canhBao->name = 'Direct sale chốt thành công liên tục';
                                     $canhBao->description = 'Direct sale ' . $name . ' vừa chốt thành công liên tục ' . $failOfDirect . ' khách';
                                     if (!$canhBao->save()) {
                                         var_dump($canhBao->getErrors());
                                         die;
                                     }
                                 }*/
                            } else {
                                /* Khách không đồng ý làm dịch vụ */
                                if ($modelRemindDirectSale->primaryKey == null) {
                                    $modelRemindDirectSale->customer_id = $model->primaryKey;
                                    $modelRemindDirectSale->status = CustomerModel::STATUS_DH;
                                    $modelRemindDirectSale->dat_hen = Dep365CustomerOnline::DAT_HEN_DEN;
                                }
                                $modelRemindDirectSale->remind_call_time = strtotime(date('d-m-Y'));
                                if (!$modelRemindDirectSale->save()) {
                                    $transaction->rollBack();
                                    return [
                                        'status' => $modelRemindDirectSale->getErrors(),
                                        'result' => Yii::$app->params['update-danger']
                                    ];
                                }
                                //Tạo cảnh báo fail liên tục
                                /*if ($model->directsale != null) {
                                    $directsale = UserProfile::find()->where(['user_id' => $model->directsale])->one();
                                    if ($directsale !== null)
                                        $name = $directsale->fullname == null ? null : $directsale->fullname;
                                }

                                //Tinh so lan fail
                                $khachOkOfDirect = CustomerModel::find()
                                    ->where([CustomerModel::tableName() . '.directsale' => $model->directsale])
                                    ->andWhere(['in', 'customer_come_time_to', $listAccept])
                                    ->orderBy(['id' => SORT_DESC])
                                    ->one();

                                $failOfDirect = CustomerModel::find()->where(['between', CustomerModel::tableName() . '.customer_come', $khachOkOfDirect->customer_come, time()])
                                    ->andWhere(['dat_hen' => CustomerModel::DA_DEN])
                                    ->andWhere(['directsale' => $model->directsale])
                                    ->andWhere(['not in', 'customer_come_time_to', $listAccept])
                                    ->count();
                                if ($failOfDirect >= 2) {
                                    $canhBao = new CanhBao();
                                    $canhBao->type = CanhBao::DIRECT_SALE_CHOT_FAIL;
                                    $canhBao->name = 'Direct sale chốt fail liên tục';
                                    $canhBao->description = 'Direct sale ' . $name . ' vừa chốt fail liên tục ' . $failOfDirect . ' khách';
                                    if (!$canhBao->save()) {
                                        var_dump($canhBao->getErrors());
                                        die;
                                    }
                                }*/
                            }
//                            if ($modelRemind->primaryKey != null) {
//                                $modelRemind->remind_call_status = Dep365CustomerOnlineRemindCall::STATUS_DISABLED;
//                                if (!$modelRemind->save()) {
//                                    $transaction->rollBack();
//                                    return [
//                                        'status' => $modelRemind->getErrors(),
//                                        'result' => Yii::$app->params['update-danger']
//                                    ];
//                                }
//                            }
                        } elseif ($model->dat_hen == Dep365CustomerOnline::DAT_HEN_KHONG_DEN) {
                            if ($modelRemind->primaryKey == null) {
                                $modelRemind->customer_id = $model->primaryKey;
                                $modelRemind->status = $model->status;
                                $modelRemind->status_fail = $model->status_fail;
                                $modelRemind->dat_hen = $model->dat_hen;
                                $modelRemind->type = CustomerOnlineRemindCall::TYPE_CUSTOMER_ONLINE;
                            }
                            $modelRemind->permission_user = $model->permission_user;
                            $modelRemind->created_by = $model->permission_user;
                            $modelRemind->remind_call_time = strtotime(date('d-m-Y'));
                            if (!$modelRemind->save()) {
                                $transaction->rollBack();
                                return [
                                    'status' => $modelRemind->getErrors(),
                                    'result' => Yii::$app->params['update-danger']
                                ];
                            }
                        }

                        if (!$user_timeline->save()) {
                            $transaction->rollBack();
                        }

                        $dat_hen_new = $model->getAttribute('dat_hen');
                        if ($dat_hen_new == Dep365CustomerOnline::DAT_HEN_DEN && $dat_hen_old != Dep365CustomerOnline::DAT_HEN_DEN) {
                            if (!CustomerToken::quickCreate($model->primaryKey, null, null, CustomerToken::TYPE_CUSTOMER_FEEDBACK)) {
                                $transaction->rollBack();
                                return [
                                    'status' => 400,
                                    'result' => Yii::$app->params['update-danger']
                                ];
                            } else {
                                $cache = Yii::$app->cache;
                                $key = 'redis-screen-online';
                                $cache->set($key, [
                                    'customer_id' => $model->primaryKey,
                                    'status' => UserTimelineModel::ACTION_THAM_KHAM
                                ]);
                            }
                        }
                        $directsale_new = $model->getAttribute('directsale');
                        if ($directsale_old != null && $model->directsale != null && $directsale_new != $directsale_old) {
                            /* THÔNG BÁO TỚI APP DICRECT SALE ĐƯỢC PHÂN CÔNG CHĂM SÓC KHÁCH HÀNG */
                            $setting = Setting::find()->where(['key_value' => 'khach_phan_cong_cho_directsale'])->one();
                            if ($setting != null) {
                                if (CONSOLE_HOST == false/*\Yii::$app->request->getUserIP() == '127.0.0.1'*/) {
                                    $client = new Client([
                                        'verify' => Url::to('@backend/modules/clinic/token/cacert.pem')
                                    ]);
                                } else {
                                    $client = new Client();
                                }
                                if ($model->full_name != null) {
                                    $customer = $model->full_name;
                                } elseif ($model->forename != null) {
                                    $customer = $model->forename;
                                } else {
                                    $customer = $model->name;
                                }
                                $content = str_replace('{$customer}', $customer, $setting->value);
                                $client->request('POST', 'https://api.myauris.vn/api/CreateNoti', [
                                    'verify' => false,
                                    'form_params' => [
                                        'name' => $setting->param,
                                        'content' => $content,
                                        'description' => $content,
                                        'user_id' => $directsale_new,
                                        'customer_id' => $model->primaryKey,
                                        'type' => 3
                                    ]
                                ]);
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
                        'result' => 'Lỗi dữ liệu',
                        'error' => $model->getErrors()
                    ];
                }
            }

            return $this->renderAjax('create-ajax', [
                'model' => $model,
                'listAccept' => $listAccept
            ]);
        }
    }

    public function actionValidateRenderAndUpdate($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = $this->findModel($id);
            $model->check_dich_vu = '1';
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
            $user_timeline = new UserTimelineModel();
            $user_timeline->action = [UserTimelineModel::ACTION_THEM, UserTimelineModel::ACTION_DAT_HEN];
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
                        $user_timeline->customer_id = $model->primaryKey;
                        if (!$user_timeline->save()) {
                            $transaction->rollBack();
                        }
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

    public function actionCheckDichVu()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $sanpham = Yii::$app->request->post('sanpham');
            $rowSanPham = PhongKhamSanPham::find()->joinWith(['dichVuHasOne'])->where([PhongKhamSanPham::tableName() . '.id' => $sanpham])->published()->one();
            return [
                'code' => 200,
                'data' => $rowSanPham == null || $rowSanPham->dichVuHasOne == null ? '' : $rowSanPham->dichVuHasOne->name,
                'don_gia' => $rowSanPham == null ? '' : number_format($rowSanPham->don_gia, 0, '', '.'),
            ];
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
