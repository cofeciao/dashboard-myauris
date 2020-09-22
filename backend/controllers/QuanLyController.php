<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 08-Apr-19
 * Time: 11:50 AM
 */

namespace backend\controllers;

use backend\components\GapiComponent;
use backend\components\MyComponent;
use backend\components\MyController;
use backend\models\CustomerModel;
use backend\models\doanhthu\DonHangModel;
use backend\models\quanly\CustomerInfo;
use backend\models\search\CustomerModelSearch;
use backend\modules\clinic\models\CustomerImages;
use backend\modules\clinic\models\PhongKhamDonHang;
use backend\modules\clinic\models\PhongKhamDonHangWOrder;
use backend\modules\clinic\models\PhongKhamDonHangWThanhToan;
use backend\modules\clinic\models\PhongKhamKhuyenMai;
use backend\modules\clinic\models\PhongKhamLichDieuTri;
use backend\modules\clinic\models\UploadAudio;
use backend\modules\customer\models\Dep365CustomerOnlineDathenTime;
use backend\modules\log\components\VhtCallLogComponent;
use backend\modules\setting\models\Setting;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Response;

class QuanLyController extends MyController
{
    public function actionIndex()
    {
        $searchModel = new CustomerModelSearch();
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

    public function actionCustomerView($id)
    {
        $customer = CustomerModel::find()->where(['dep365_customer_online.id' => $id])->joinWith(['districtHasOne', 'provinceHasOne', 'directSaleHasOne', 'statusDatHenHasOne', 'dentalTagHasOne'])->one();
        if ($customer == null) {
            Yii::$app->session->setFlash('alert', [
                'class' => 'alert-warning',
                'body' => 'Không tìm thấy khách hàng'
            ]);
            return $this->redirect(['index']);
        }
        $orderData = DonHangModel::find()->where(['customer_id' => $id])->all();

        return $this->render('customer-view', [
            'customer' => $customer,
            'orderData' => $orderData,
        ]);
    }

    public function actionCustomerTimeline($id)
    {
        $customer = CustomerModel::find()->where(['dep365_customer_online.id' => $id])->joinWith(['districtHasOne', 'provinceHasOne', 'directSaleHasOne', 'statusDatHenHasOne', 'dentalTagHasOne'])->one();
        if ($customer == null) {
            Yii::$app->session->setFlash('alert', [
                'class' => 'alert-warning',
                'body' => 'Không tìm thấy khách hàng'
            ]);
            return $this->redirect(['index']);
        }
        $userCreated = CustomerModel::getUserCreatedBy($customer->created_by);

        /*$timelines = [
            '2019-03-15 11:21' => [
                'icon-class' => 'warning',
                'icon' => '<i class="fa fa-user-md fa-2x"></i>',
                'title' => 'Lịch khám',
                'content' => 'Khách hàng đến phòng khám',
            ],
            '2019-03-14 8:32' => [
                'icon-class' => 'danger',
                'icon' => '<i class="fa fa-medkit fa-2x"></i>',
                'title' => 'Đặt lịch',
                'content' => 'Khách hàng đặt hẹn lúc 11:21 15-03-2019',
            ],
            '2019-03-13 14:22' => [
                'center' => true,
                'icon-class' => 'default',
                'icon' => '<i class="fa fa-plane fa-2x"></i>',
                'title' => 'Tên sự kiện',
                'content' => 'Nội dung sự kiện',
            ],
            '2018-03-13 10:03' => [
                'icon-class' => 'primary',
                'icon' => '<i class="fa fa-check-square-o fa-2x"></i>',
                'title' => 'Xét duyệt',
                'content' => 'Được xác nhận bởi <span class="badge badge-warning">Quản trị</span>',
            ],
            '2018-03-12 13:21' => [
                'center' => true,
                'icon-class' => 'success',
                'icon' => '<i class="ft-users fa-2x"></i>',
                'title' => 'Thêm mới',
                'content' => 'Được thêm bởi <span class="badge badge-warning">Nhân viên</span>',
            ],
        ];*/

        $timelines = [
            $customer->ngay_tao => [
                'center' => true,
                'icon-class' => 'danger',
                'icon' => '<i class="fa fa-user"></i>',
                'title' => 'Thêm mới',
                'content' => 'Được thêm vào hệ thống ' . ($userCreated != null ? ' bởi <span class="badge badge-primary">' . $userCreated->fullname . '</span> ' : ''),
            ]
        ];

        $listDatHenTime = Dep365CustomerOnlineDathenTime::find()->where(['customer_online_id' => $customer->id])->joinWith(['userHasOne'])->all();
        if ($listDatHenTime != null) {
            foreach ($listDatHenTime as $k => $datHenTime) {
                $timelines[$datHenTime->time_change] = [
                    'icon-class' => 'success',
                    'icon' => '<i class="fa fa-phone"></i>',
                    'title' => ($datHenTime->time_lichhen == null ? 'Đặt hẹn' : 'Đổi lịch hẹn'),
                    'content' =>
                        ($datHenTime->userHasOne != null ? '<span class="badge badge-primary">' . $datHenTime->userHasOne->fullname . '</span>' : '') .
                        (
                        $datHenTime->time_lichhen == null ?
                            ' tạo lịch hẹn mới ngày <span class="badge badge-success">' . date('d-m-Y h:i', $datHenTime->time_lichhen_new) . '</span>' :
                            ' đổi lịch hẹn từ ngày <span class="badge badge-warning">' . date('d-m-Y h:i', $datHenTime->time_lichhen) . '</span> sang ngày <span class="badge badge-success">' . date('d-m-Y h:i', $datHenTime->time_lichhen_new) . '</span>'
                        )
                ];
            }
        }

        $listDonHangWOrder = PhongKhamDonHang::find()->where(['customer_id' => $customer->id])->joinWith(['userCreatedByHasOne'])->all();
        if ($listDonHangWOrder != null) {
            foreach ($listDonHangWOrder as $k => $donHangWOrder) {
                $timelines[$donHangWOrder->created_at] = [
                    'icon-class' => 'info',
                    'icon' => '<i class="fa fa-shopping-cart"></i>',
                    'title' => 'Tạo đơn hàng',
                    'content' => ($donHangWOrder->userCreatedByHasOne != null ? '<span class="badge badge-primary">' . $donHangWOrder->userCreatedByHasOne->fullname . '</span>' : '') . ' tạo hoá đơn thanh toán - ' . Html::a('Chi tiết', ['clinic/clinic-order/view', 'id' => $customer->id], ['class' => 'badge badge-warning', 'target' => '_blank'])
                ];
            }
        }

        $listLichDieuTri = PhongKhamLichDieuTri::find()->where(['customer_id' => $customer->id])->joinWith(['userCreatedByHasOne'])->all();
        if ($listLichDieuTri != null) {
            foreach ($listLichDieuTri as $k => $lichDieuTri) {
                $timelines[$lichDieuTri->created_at] = [
                    'icon-class' => 'success',
                    'icon' => '<i class="fa fa-medkit"></i>',
                    'title' => 'Tạo lịch điều trị',
                    'content' => ($lichDieuTri->userCreatedByHasOne != null ? '<span class="badge badge-primary">' . $lichDieuTri->userCreatedByHasOne->fullname . '</span>' : '') . ' tạo lịch điều trị - ' . Html::a('Chi tiết', '#', [
                            'class' => 'badge badge-warning',
                            'data-pjax' => 0,
                            'data-toggle' => 'modal',
                            'data-backdrop' => 'static',
                            'data-keyboard' => false,
                            'data-target' => '#custom-modal',
                            'onclick' => 'showModal($(this), "' . \yii\helpers\Url::toRoute(['/clinic/clinic-dieu-tri/view', 'id' => $lichDieuTri->id]) . '");return false;',
                        ])
                ];
            }
        }

        if ($customer->customer_come != null && $customer->customer_come < time()) {
            $timelines[$customer->customer_come] = [
                'center' => true,
                'icon-class' => 'success',
                'icon' => '<i class="icon-user-following"></i>',
                'title' => 'Đến phòng khám',
                'content' => 'Khách đến thăm khám vào lúc ' . date('H:i d-m-Y', $customer->customer_come)
            ];
        }


        // Pham Thanh Nghia - 1/2/2020
        // Cap nhat KH goi den,  Goi cho KH

        $setting = Setting::find()
            ->where(['key_value' => 'phone_sale_myauris'])
            ->one();
        $phone_sale = "";
        if ($setting !== null) {
            $phone_sale = $setting->value;
        }

        // KH gọi đến
        $params['to_number'] = $phone_sale;
        $params['from_number'] = $customer->phone;
        $VhtCallLog = new VhtCallLogComponent([], $params);
        $model = $VhtCallLog->ConnectVht();
        if ($model !== null) {
            if (is_array($model->items) && count($model->items) > 0) {
                foreach ($model->items as $item) {
                    if ($item->cause == 200) {
                        $icon_class = $item->recording_url != '' ? 'success' : 'warning';
                        $timelines[$item->time_started] = [
                            'center' => false,
                            'icon-class' => $icon_class,
                            'icon' => '<i class="ft-phone-incoming"></i>',
                            'title' => 'Khách hàng gọi đến',
                            'content' => 'Khách hàng gọi đến lúc <span class="badge badge-success">' . date('H:i d-m-Y', $item->time_started) . '</span>' . ($item->recording_url ? ' ' . Html::button('<i class="fa fa-play-circle"></i>', [
                                        'class' => 'btn btn-sm btn-primary',
                                        'data-toggle' => 'popover',
                                        'data-content' => '<iframe src =\'' . $item->recording_url . '\'></iframe>',
                                        'data-html' => 'true',
                                        'data-placement' => 'bottom'
                                    ]) : ''),
                        ];
                    }
                }
            }
        }


        // Gọi cho KH
        $params['to_number'] = $customer->phone;
        $params['from_number'] = $phone_sale;
        $VhtCallLog = new VhtCallLogComponent([], $params);
        $model = $VhtCallLog->ConnectVht();
        if ($model !== null) {
            if (is_array($model->items) && count($model->items) > 0) {
                foreach ($model->items as $item) {
                    if ($item->cause == 200) {
                        $icon_class = $item->recording_url != '' ? 'success' : 'warning';
                        $timelines[$item->time_started] = [
                            'center' => false,
                            'icon-class' => $icon_class,
                            'icon' => '<i class="ft-phone-outgoing"></i>',
                            'title' => 'Gọi cho khách hàng',
                            'content' => 'Gọi cho khách hàng lúc <span class="badge badge-success">' . date('H:i d-m-Y', $item->time_started) . '</span>' . ($item->recording_url != '' ? ' ' . Html::button('<i class="fa fa-play-circle"></i>', [
                                        'class' => 'btn btn-sm btn-primary',
                                        'data-toggle' => 'popover',
                                        'data-content' => '<iframe src =\'' . $item->recording_url . '\'></iframe>',
                                        'data-html' => 'true',
                                        'data-placement' => 'bottom'
                                    ]) : ''),
                        ];
                    }
                }
            }
        }

        // Ghi am cuoc tu van
        $listChupHinh = CustomerImages::find()->where(['customer_id' => $customer->id, 'catagory_id' => Yii::$app->params['chup-hinh-catagory'][UploadAudio::FOLDER]])->all();

        foreach ($listChupHinh as $item) {
            if (file_exists(Yii::$app->basePath . '/web/uploads/audio/' . $item->image)) {
                $avatar = '/uploads/audio/' . $item->image;
                $timelines[$item->created_at] = [
                    'center' => false,
                    'icon-class' => 'info',
                    'icon' => '<i class="icon-user-following"></i>',
                    'title' => 'Ghi âm chăm sóc khách hàng của Direct Sale',
                    'content' => Html::button('<i class="fa fa-play-circle"></i>', [
                            'class' => 'btn btn-sm btn-primary',
                            'data-toggle' => 'popover',
                            'data-content' => "<audio controls=\"controls\" style=\"width: 267px; margin: 8px;\">
                                                    <source src=" . $avatar . " type=\"audio/mpeg\" />
                                                </audio>",
                            'data-html' => 'true',
                            'data-placement' => 'bottom'
                        ]) . ' bắt đầu lúc <span class="badge badge-success">' . date('d-m-Y h:i', $item->created_at) . '</span>',
                ];
            }
        }

        krsort($timelines);

        return $this->renderAjax('_customer_timeline', [
//            'customer' => $customer,
            'timelines' => $timelines
        ]);
    }

    public function actionCustomerInfo($id)
    {
        if (Yii::$app->request->isAjax) {
            $customer = CustomerModel::find()->where(['dep365_customer_online.id' => $id])->joinWith(['districtHasOne', 'provinceHasOne', 'directSaleHasOne', 'statusDatHenHasOne', 'dentalTagHasOne'])->one();
            if ($customer == null) {
                return 'Không tìm thấy khách hàng';
            }
            return $this->renderPartial('_customer_info', [
                'customer' => $customer
            ]);
        }
    }

    public function actionLichDieuTriInfo($id)
    {
        $customer = CustomerModel::find()->where(['dep365_customer_online.id' => $id])->joinWith(['districtHasOne', 'provinceHasOne', 'directSaleHasOne', 'statusDatHenHasOne', 'dentalTagHasOne'])->one();
        if ($customer == null) {
            return 'Không tìm thấy khách hàng';
        }

        $dataLichDieuTriProvider = new ActiveDataProvider([
            'query' => PhongKhamLichDieuTri::find()->where(['customer_id' => $id])->joinWith(['ekipInfoHasOne']),
            'pagination' => [
                'defaultPageSize' => 10
            ],
        ]);

        return $this->renderPartial('_lichdieutri_info', [
            'customer' => $customer,
            'dataLichDieuTriProvider' => $dataLichDieuTriProvider
        ]);
    }

    public function actionCustomerInfoEdit($id)
    {
        if (Yii::$app->request->isAjax) {
            $customer = CustomerInfo::find()->where(['dep365_customer_online.id' => $id])->joinWith(['districtHasOne', 'provinceHasOne', 'directSaleHasOne', 'statusDatHenHasOne', 'dentalTagHasOne'])->one();
            if ($customer == null) {
                return 'Không tìm thấy khách hàng';
            }
            if ($customer->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $code = 200;
                $data = [
                    'post' => Yii::$app->request->post()
                ];
                if ($customer->validate()) {
                    $customer->time_lichhen = strtotime($customer->time_lichhen);
                    if ($customer->save()) {
                        $msg = 'Cập nhật thành công!';
                    } else {
                        $code = 403;
                        $msg = 'Lỗi cập nhật dữ liệu!';
                        $data['error'] = $customer->getErrors();
                    }
                } else {
                    $code = 400;
                    $msg = 'Lỗi kiểm tra dữ liệu!';
                    $data['error'] = $customer->getErrors();
                }
                return [
                    'code' => $code,
                    'msg' => $msg,
                    'data' => $data
                ];
            }
            $customer->time_lichhen = date('d-m-Y', $customer->time_lichhen);
            return $this->renderPartial('_customer_info_edit', [
                'customer' => $customer
            ]);
        }
    }

    // Pham Thanh Nghia

    public function actionShowImageGoogleDrive($customer_id, $slug, $folder)
    {
        $service = GapiComponent::getService();
        $cutomerImages = CustomerImages::getListFilesByCustomer($customer_id, Yii::$app->params['chup-hinh-catagory'][$folder]);
        $aImage = [];
        foreach ($cutomerImages as $image) {

            if (file_exists(Url::to('@backend/web') . '/uploads/customer/' . $slug . '-' . $customer_id . '/' . $folder . '/' . $image->image)) {
                $aImage[] = [
                    'type' => 'local',
                    'id' => $image->id,
                    'name' => $image->image,
                    'webContentLink' => Url::to('@web/uploads') . '/customer/' . $slug . '-' . $customer_id . '/' . $folder . '/' . $image->image,
                    'thumbnailLink' => Url::to('@web/uploads') . '/customer/' . $slug . '-' . $customer_id . '/' . $folder . '/thumb/' . $image->image,
                    'imageType' => $image->type
                ];
            } else {
                $getFile = GapiComponent::getFile($service, $image->google_id);
                if (isset($getFile['webContentLink'])) {
                    // cat lay link hinh anh
                    $webContentLink = chop($getFile['webContentLink'], "export=download");
                    $aImage[] = [
                        'type' => 'local',
                        'id' => $image->id,
                        'name' => $image->image,
                        'webContentLink' => $webContentLink,
                        'thumbnailLink' => $webContentLink,
                        'imageType' => $image->type
                    ];
                }
            }
        }

        return $this->renderPartial('_image_drive', [
            'aImage' => $aImage,
            'folder' => $folder
        ]);
    }

    public function actionDonHangInfo($id, $order_id)
    {
        $customer = CustomerModel::find()->where(['dep365_customer_online.id' => $id])->joinWith(['districtHasOne', 'provinceHasOne', 'directSaleHasOne', 'statusDatHenHasOne', 'dentalTagHasOne'])->one();
        if ($customer == null) {
            return 'Không tìm thấy khách hàng';
        }

        $listKhuyenMai = PhongKhamKhuyenMai::getListKhuyenMai();

        $order = DonHangModel::findOne($order_id);
        if ($order) {
            $dataOrderProviderOrder = new ActiveDataProvider([
                'query' => PhongKhamDonHangWOrder::find()->where(['customer_id' => $id, 'phong_kham_don_hang_id' => $order->id])->joinWith(['dichVuHasOne', 'sanPhamHasOne']),
                'sort' => false,
            ]);

            $dataOrderProviderThanhToan = new ActiveDataProvider([
                'query' => PhongKhamDonHangWThanhToan::find()->where(['phong_kham_don_hang_id' => $order->id]),
                'sort' => false,
            ]);
//            $this->renderDonHang($order,$dataOrderProviderOrder, $dataOrderProviderThanhToan, $listKhuyenMai);
            return $this->renderPartial('_donhang_info', [
                'order' => $order,
                'dataOrderProviderOrder' => $dataOrderProviderOrder,
                'dataOrderProviderThanhToan' => $dataOrderProviderThanhToan,
                'listKhuyenMai' => $listKhuyenMai,
            ]);
        }


    }

    public function renderDonHang($order, $dataOrderProviderOrder, $dataOrderProviderThanhToan, $listKhuyenMai)
    {
        return $this->renderPartial('_donhang_info', [
            'order' => $order,
            'dataOrderProviderOrder' => $dataOrderProviderOrder,
            'dataOrderProviderThanhToan' => $dataOrderProviderThanhToan,
            'listKhuyenMai' => $listKhuyenMai,
        ]);
    }

    // Giai doan thanh toan
    public function actionThanhToan($customer_id)
    {
        $customer = CustomerModel::find()->where(['dep365_customer_online.id' => $customer_id])->joinWith(['districtHasOne', 'provinceHasOne', 'directSaleHasOne', 'statusDatHenHasOne', 'dentalTagHasOne'])->one();
        if ($customer == null) {
            return 'Không tìm thấy khách hàng';
        }

        $dataOrderProvider = new ActiveDataProvider([
            'query' => PhongKhamDonHangWThanhToan::find()->where(['customer_id' => $customer_id])->joinWith(['dichVuHasOne', 'sanPhamHasOne']),
            'sort' => false,
            'pagination' => [
                'defaultPageSize' => 10
            ],
        ]);

        return $this->renderPartial('_thanh_toan_info', [
            'customer' => $customer,
            'dataOrderProvider' => $dataOrderProvider
        ]);
    }
}
