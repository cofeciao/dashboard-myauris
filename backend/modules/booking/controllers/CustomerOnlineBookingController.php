<?php

namespace backend\modules\booking\controllers;

use backend\modules\api\modules\v1\controllers\BookingController;
use backend\modules\booking\models\form\ChooseOptionsRenderForm;
use backend\modules\booking\models\TimeWork;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\setting\models\Dep365CoSo;
use common\components\RenderVirtualBookingComponent;
use Yii;
use backend\modules\booking\models\CustomerOnlineBooking;
use backend\modules\booking\models\search\CustomerOnlineBookingSearch;
use backend\components\MyController;
use yii\db\Exception;
use yii\db\Transaction;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\MyComponent;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * CustomerOnlineBookingController implements the CRUD actions for CustomerOnlineBooking model.
 */
class CustomerOnlineBookingController extends MyController
{
    public function actionIndex()
    {
        $searchModel = new CustomerOnlineBookingSearch();
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
        $model = new CustomerOnlineBooking();

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
    }

    /**
     * Updates an existing CustomerOnlineBooking model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                try {
                    $model->save();
                    return [
                        'status' => 200,
                        'mess' => Yii::$app->params['create-success'],
                    ];
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
            ]);
        }
    }

    public function actionDelete()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
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

    public function actionChangeStatus($id = null)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $status = Yii::$app->request->post('status');
            $model = CustomerOnlineBooking::find()->where(['id' => $id])->one();
            if ($model == null) {
                return [
                'code' => 404,
                'msg' => 'Không tìm thấy dữ liệu'
            ];
            }
            try {
                $model->updateAttributes([
                    'status' => $status == "true" ? 1 : 0
                ]);
                return [
                    'code' => 200,
                    'msg' => 'Cập nhật thành công'
                ];
            } catch (Exception $ex) {
                return [
                    'code' => 400,
                    'msg' => 'Cập nhật thất bại',
                    'error' => $ex
                ];
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
        if (($model = CustomerOnlineBooking::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionRenderVirtualBooking()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $today = date('Y-m-d');
            $dateNow = strtotime($today);
            $listCoSo = Dep365CoSo::find()->where(['render_virtual_booking' => Dep365CoSo::STATUS_PUBLISHED])->published()->all();
            if ($listCoSo != null) {
                $checkBookingIsInit = CustomerOnlineBooking::find()->where(['customer_type' => CustomerOnlineBooking::CUSTOMER_VITUAL])->andWhere('booking_date > ' . $dateNow)->count();
                if ($checkBookingIsInit <= 0) {
                    /* CHƯA RENDER LỊCH ẢO => RENDER LỊCH ẢO CHO 60 NGÀY TIẾP THEO */
                    $renderVirtualBooking = new RenderVirtualBookingComponent();
                    return $renderVirtualBooking->renderVirtualBooking();
                } else {
                    /* ĐÃ RENDER LỊCH ẢO => MỞ OPTIONS CHỌN PHƯƠNG THỨC RENDER */
                    return [
                        'code' => 200,
                        'msg' => 'Chọn phương thức tạo lịch ảo',
                        'return' => 'choose-options'
                    ];
                }
            }
        }

        return [
            'code' => 403,
            'msg' => 'Bạn không có quyền truy cập chức năng này'
        ];
    }

    public function actionChooseOptionsRenderBooking()
    {
        if (Yii::$app->request->isAjax) {
            $model = new ChooseOptionsRenderForm();
            return $this->renderAjax('choose-options-render', [
                'model' => $model
            ]);
        }
    }
    public function actionValidationFormChooseOptions()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = new ChooseOptionsRenderForm();
            if ($model->load(Yii::$app->request->post())) {
                return ActiveForm::validate($model);
            }
            return [];
        }
    }
    public function actionSubmitFormChooseOptions()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = new ChooseOptionsRenderForm();
            if (!$model->load(Yii::$app->request->post()) || !$model->validate()) {
                return [
                'code' => 400,
                'msg' => 'Lỗi dữ liệu'
            ];
            }
            $type_render = $model->options == ChooseOptionsRenderForm::OPTION_RENDER_NEW ? true : false;
            $renderVirtualBooking = new RenderVirtualBookingComponent();
            return $renderVirtualBooking->renderVirtualBooking($type_render);
        }
    }

    public function actionConvertData()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $code = 200;
            $msg = '';
            $listCustomerOnline = Dep365CustomerOnline::find()->where('time_lichhen>' . strtotime(date('d-m-Y h:i:s')))->andWhere('co_so IS NOT null')->all();
            if ($listCustomerOnline != null) {
                foreach ($listCustomerOnline as $customerOnline) {
                    $check = CustomerOnlineBooking::find()->where(['user_register_id' => $customerOnline->id, 'customer_type' => CustomerOnlineBooking::CUSTOMER_FROM_ONLINE])->one();
                    if ($check != null) {
                        continue;
                    }
                    $h = date('H', $customerOnline->time_lichhen);
                    $i = date('i', $customerOnline->time_lichhen);
                    if ($i >= 30) {
                        $i = 30;
                    } else {
                        $i = '00';
                    }
                    $time = TimeWork::find()->where(['time' => $h . ':' . $i])->one();
                    if ($time == null) {
                        $msg .= 'Khách hàng ' . $customerOnline->name . ' (' . $customerOnline->id . ') lưu thất bại, lý do: thời gian đặt hẹn ' . $h . ':' . $i . ' không tồn tại! <br/>';
                    } else {
                        $booking = new CustomerOnlineBooking();
                        $booking->user_register_id = $customerOnline->id;
                        $booking->customer_type = CustomerOnlineBooking::CUSTOMER_FROM_ONLINE;
                        $booking->time_id = $time->id;
                        $booking->coso_id = $customerOnline->co_so;
                        $booking->status = 1;
                        $booking->booking_date = strtotime(date('d-m-Y', $customerOnline->time_lichhen));
                        if (!$booking->save()) {
                            $msg .= 'Lưu dữ liệu khách hàng ' . $customerOnline->name . ' (' . $customerOnline->id . ') thất bại!<br/>';
                        }
                    }
                }
            }
            return [
                'code' => $code,
                'msg' => $msg
            ];
        }
    }
}
