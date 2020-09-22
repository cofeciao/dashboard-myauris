<?php

namespace backend\modules\labo\controllers;

use backend\components\GapiComponent;
use backend\components\MyComponent;
use backend\components\MyController;
use backend\models\CustomerModel;
use backend\modules\clinic\controllers\ChupBanhMoiController;
use backend\modules\clinic\controllers\ChupCuiController;
use backend\modules\clinic\controllers\ChupFinalController;
use backend\modules\clinic\controllers\ChupHinhController;
use backend\modules\clinic\controllers\DentalFormController;
use backend\modules\clinic\controllers\HinhFinalController;
use backend\modules\clinic\controllers\TkncController;
use backend\modules\clinic\controllers\UomRang1Controller;
use backend\modules\clinic\controllers\UomRang2Controller;
use backend\modules\clinic\models\Clinic;
use backend\modules\clinic\models\CustomerImages;
use backend\modules\clinic\models\form\FormAvatarCustomer;
use backend\modules\clinic\models\PhongKhamChupBanhMoi;
use backend\modules\clinic\models\PhongKhamChupCui;
use backend\modules\clinic\models\PhongKhamChupFinal;
use backend\modules\clinic\models\PhongKhamChupHinh;
use backend\modules\clinic\models\PhongKhamDentalForm;
use backend\modules\clinic\models\PhongKhamDonHang;
use backend\modules\clinic\models\PhongKhamHinhFinal;
use backend\modules\clinic\models\PhongKhamHinhTknc;
use backend\modules\clinic\models\PhongKhamUomRang1;
use backend\modules\clinic\models\PhongKhamUomRang2;
use backend\modules\customer\models\Dep365CustomerOnlineImport;
use backend\modules\general\models\Dep365Notification;
use backend\modules\labo\models\LaboDonHang;
use backend\modules\labo\models\LaboGiaiDoan;
use backend\modules\labo\models\LaboGiaiDoanImage;
use backend\modules\labo\models\search\SearchLaboDonHang;
use backend\modules\labo\models\search\SearchLaboGiaiDoan;
use backend\modules\labo\models\search\SearchLaboGiaiDoanImage;
use backend\modules\user\models\User;
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;


/**
 * LaboDonHangController implements the CRUD actions for LaboDonHang model.
 */
class LaboDonHangController extends MyController
{
    public function actionIndex()
    {
        $searchModel = new SearchLaboDonHang();
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
        if (($model = $this->findModel($id)) !== null) {
            $mDonHang = PhongKhamDonHang::findOne($model->phong_kham_don_hang_id);
            return $this->render('view/view', [
                'model' => $this->findModel($id),
                'mDonHang' => $mDonHang
            ]);
        }
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = LaboDonHang::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCreate()
    {
        $model = new LaboDonHang();

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

    public function actionCreateDon($don_id)
    {
        $PhongKhamDonHang = PhongKhamDonHang::findOne($don_id);
        if (!$PhongKhamDonHang) {
            Yii::$app->session->setFlash('alert-bao-hanh', [
                'body' => 'Không có đơn hàng',
                'class' => 'bg-danger',
            ]);
            return $this->redirect(Yii::$app->request->referrer);
        }
        $mCustomer = CustomerModel::findOne($PhongKhamDonHang->customer_id);
        $model = $this->findModelByDonHangID($don_id);
       
        if ($model === false) {
            $model = new LaboDonHang();
            $model->phong_kham_don_hang_id = $don_id;
            $model->status = 1;
            $model->trang_thai = LaboDonHang::TRANG_THAI_MOI;
            $model->so_luong = 0;
        }

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model->ngay_nhan = strtotime($model->ngay_nhan);
            $model->ngay_giao = strtotime($model->ngay_giao);
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            //  var_dump($model);
        // die;
        // Yii::warning($model);
            if ($model->validate()) {

                try {
                    $fileName = time() . '.' . $model->imageFile->extension;
                    if ($model->upload($fileName)) {
                        $model->image = $fileName;
                    }
                    $model->save();
                    // tao buoc tiep nhan

                    // if(LaboGiaiDoan::exitsDonHang($model->primaryKey) <= 0 ){
                    //     $this->createGiaiDoanTiepNhan($model->primaryKey);
                    // }
                    Yii::$app->session->setFlash('alert', [
                        'body' => Yii::$app->params['create-success'],
                        'class' => 'bg-success',
                    ]);
                    // tao thong bao
                    $this->createNotification($model);
                } catch (\yii\db\Exception $exception) {
                    Yii::$app->session->setFlash('alert', [
                        'body' => Yii::$app->params['create-danger'],
                        'class' => 'bg-danger',
                    ]);
                }
                return $this->refresh();
            }else{
                // echo "ahihi";
                // die;
            }
        }
        if (!empty($model->ngay_nhan)) {
            $model->ngay_nhan = date('d-m-Y', $model->ngay_nhan);
        }
        if (!empty($model->ngay_giao)) {
            $model->ngay_giao = date('d-m-Y', $model->ngay_giao);
        }

        return $this->render('create-don/create', [
            'model' => $model,
            'mCustomer' => $mCustomer,
        ]);
    }

    protected function findModelByDonHangID($don_id)
    {
        if (($model = LaboDonHang::find()->where(['phong_kham_don_hang_id' => $don_id])->one()) !== null) {
            return $model;
        }
        return false;
    }

    // tạo tự đọng 4 giai đoạn
    public function createGiaiDoanTiepNhan($labo_don_hang_id)
    {
        $countlaboGiaiDoanTiepNhan = LaboGiaiDoan::find()->where(['labo_don_hang_id' => $labo_don_hang_id, 'status' => LaboGiaiDoan::GIAI_DOAN_TIEP_NHAN])->count();
        if ($countlaboGiaiDoanTiepNhan == 0) {
            $mGiaiDoan = new LaboGiaiDoan();
            $mGiaiDoan->giai_doan = LaboGiaiDoan::GIAI_DOAN_TIEP_NHAN;
            $mGiaiDoan->labo_don_hang_id = $labo_don_hang_id;
            $mGiaiDoan->status = LaboGiaiDoan::STATUS_DISABLED;
            $mGiaiDoan->save();

            $mGiaiDoan = new LaboGiaiDoan();
            $mGiaiDoan->giai_doan = LaboGiaiDoan::GIAI_DOAN_DAU_RANG;
            $mGiaiDoan->labo_don_hang_id = $labo_don_hang_id;
            $mGiaiDoan->status = LaboGiaiDoan::STATUS_DISABLED;
            $mGiaiDoan->save();

            $mGiaiDoan = new LaboGiaiDoan();
            $mGiaiDoan->giai_doan = LaboGiaiDoan::GIAI_DOAN_FORM_RANG;
            $mGiaiDoan->labo_don_hang_id = $labo_don_hang_id;
            $mGiaiDoan->status = LaboGiaiDoan::STATUS_DISABLED;
            $mGiaiDoan->save();

            $mGiaiDoan = new LaboGiaiDoan();
            $mGiaiDoan->giai_doan = LaboGiaiDoan::GIAI_DOAN_NUONG_HOAN_THANH;
            $mGiaiDoan->labo_don_hang_id = $labo_don_hang_id;
            $mGiaiDoan->status = LaboGiaiDoan::STATUS_DISABLED;
            $mGiaiDoan->save();
        }
    }

    public function createNotification($model)
    {
        $notifId = Dep365Notification::quickCreate([
            'name' => 'Thông báo labo',
            'icon' => 'ft-alert-circle',
            'description' => "Tạo phiếu Labo thành công",
            'is_new' => 1,
            'is_bg' => 3,
            'status' => 1,
            'for_who' => 'user-' . $model->created_by
        ]);
        //        $notifId = Dep365Notification::quickCreate([
        //            'name' => 'Thông báo labo',
        //            'icon' => 'ft-alert-circle',
        //            'description' => "Bạn có đơn 1 phiếu labo mới",
        //            'is_new' => 1,
        //            'is_bg' => 3,
        //            'status' => 1,
        //            'for_who' => User::USER_KY_THUAT_LABO,
        //        ]);
        $notifId = Dep365Notification::quickCreate([
            'name' => 'Thông báo labo',
            'icon' => 'ft-alert-circle',
            'description' => "Bạn có đơn 1 phiếu labo mới",
            'is_new' => 1,
            'is_bg' => 3,
            'status' => 1,
            'for_who' => 'user-' . $model->user_labo,
        ]);
    }

    public function actionGiaiDoan($id)
    {

        $searchModel = new SearchLaboGiaiDoan();
        $dataProvider = $searchModel->searchByDonHang(Yii::$app->request->queryParams, $id);

        if (MyComponent::hasCookies('pageSize')) {
            $dataProvider->pagination->pageSize = MyComponent::getCookies('pageSize');
        } else {
            $dataProvider->pagination->pageSize = 10;
        }

        $pageSize = $dataProvider->pagination->pageSize;

        $totalCount = $dataProvider->totalCount;

        $totalPage = (($totalCount + $pageSize - 1) / $pageSize);

        return $this->renderPartial('create-don/_list_giai_doan', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalPage' => $totalPage,
            'labo_don_hang_id' => $id,
        ]);
    }

    public function actionCreateGiaiDoan($labo_don_hang_id)
    {
        $model = new LaboGiaiDoan();
        $model->labo_don_hang_id = $labo_don_hang_id;
        $model->status = LaboGiaiDoan::STATUS_DISABLED;
        $LaboDonHang = LaboDonHang::findOne($labo_don_hang_id);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            try {
                $model->save();
                Yii::$app->session->setFlash('alert', [
                    'body' => Yii::$app->params['create-success'],
                    'class' => 'bg-success',
                ]);

                return $this->redirect(Url::toRoute(['labo-don-hang/update-giai-doan', 'id' => $model->id]));
            } catch (\yii\db\Exception $exception) {
                Yii::$app->session->setFlash('alert', [
                    'body' => Yii::$app->params['create-danger'],
                    'class' => 'bg-danger',
                ]);
            }
            return $this->refresh();
        }

        return $this->render('create-don/_form_giai_doan', [
            'model' => $model,
            'phong_kham_don_hang_id' => $LaboDonHang->phong_kham_don_hang_id,
            'labo_don_hang_id' => $LaboDonHang->id,
        ]);
    }

    public function actionUpdateGiaiDoan($id)
    {
        $model = $this->findModelGiaiDoan($id);
        $LaboDonHang = LaboDonHang::findOne($model->labo_don_hang_id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            try {
                $model->save();
                Yii::$app->session->setFlash('alert', [
                    'body' => Yii::$app->params['update-success'],
                    'class' => 'bg-success',
                ]);
                //return $this->redirect(Url::toRoute(['labo-don-hang/create-don','don_id' => $LaboDonHang->phong_kham_don_hang_id]));
            } catch (\yii\db\Exception $exception) {
                Yii::$app->session->setFlash('alert', [
                    'body' => $exception->getMessage(),
                    'class' => 'bg-danger',
                ]);
            }
            return $this->refresh();
        }
        $modelGiaiDoanImage = new LaboGiaiDoanImage();


        $searchModel = new SearchLaboGiaiDoanImage();
        $dataProvider = $searchModel->searchByLaboGiaiDoan(Yii::$app->request->queryParams, $model->id);

        if (MyComponent::hasCookies('pageSize')) {
            $dataProvider->pagination->pageSize = MyComponent::getCookies('pageSize');
        } else {
            $dataProvider->pagination->pageSize = 10;
        }

        $pageSize = $dataProvider->pagination->pageSize;

        $totalCount = $dataProvider->totalCount;

        $totalPage = (($totalCount + $pageSize - 1) / $pageSize);

        return $this->render('create-don/_form_giai_doan', [
            'modelGiaiDoanImage' => $modelGiaiDoanImage,
            'model' => $model,
            'phong_kham_don_hang_id' => $LaboDonHang->phong_kham_don_hang_id,
            'dataProvider' => $dataProvider,
            'totalPage' => $totalPage,
            'labo_don_hang_id' => $LaboDonHang->id,
        ]);
    }

    protected function findModelGiaiDoan($id)
    {
        if (($model = LaboGiaiDoan::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionIndexImage()
    {
        $searchModel = new SearchLaboGiaiDoanImage();
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

    /**
     * Updates an existing LaboDonHang model.
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
                    $listGiaiDoan = LaboGiaiDoan::find()->where(['labo_don_hang_id' => $id]);
                    if ($listGiaiDoan->count() > 0) {
                        $alistGiaiDoan = $listGiaiDoan->all();
                        foreach ($alistGiaiDoan as $mGiaiDoan) {
                            $mGiaiDoan->deleteGiaiDoan();
                        }
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

    public function actionHinhAnhKhachHang($id)
    {

        $customer = Clinic::find()->where(['id' => $id])->one();
        if ($customer === null) {
            Yii::$app->session->setFlash('alert', [
                'class' => 'alert-warning',
                'body' => 'Không tìm thấy dữ liệu'
            ]);
            return $this->redirect(Url::toRoute('index'));
        }
        if ($customer->full_name == null) {
            Yii::$app->session->setFlash('alert', [
                'body' => 'Vui lòng cập nhật Họ và Tên đầy đủ của khách hàng!',
                'class' => 'alert-warning'
            ]);
            return $this->redirect(Url::toRoute('index'));
        }
        $formAvatar = new FormAvatarCustomer();
        $formAvatar->fileImage = $customer->avatar;
        $formAvatar->id = $customer->primaryKey;
        $formChupHinh = new PhongKhamChupHinh();
        $formChupBanhMoi = new PhongKhamChupBanhMoi();
        $formChupCui = new PhongKhamChupCui();
        $formChupFinal = new PhongKhamChupFinal();
        $formHinhTknc = new PhongKhamHinhTknc();
        $formUomRang1 = new PhongKhamUomRang1();
        $formUomRang2 = new PhongKhamUomRang2();
        $formHinhFinal = new PhongKhamHinhFinal();
        $formDentalForm = new PhongKhamDentalForm();
        $listChupHinh = $listChupBanhMoi = $listChupCui = $listChupFinal = $listHinhTknc = $listUomRang1 = $listUomRang2 = $listHinhFinal = $listDentalForm = [];
        if (!CONSOLE_HOST) {
            $service = '';
        } else {
            $service = GapiComponent::getService();
        }

        $time = strtotime(date('d-m-Y'));

        $customer = Dep365CustomerOnlineImport::find()->where(['id' => $id])->one();
        if ($customer == null) {
            Yii::$app->session->setFlash('alert', [
                'class' => 'alert-warning',
                'body' => 'Không tìm thấy dữ liệu'
            ]);
            return $this->redirect(['import-customer']);
        }
        /* CHUP HINH */
        $checkChupHinhData = CustomerImages::getListFilesByCustomer($id, Yii::$app->params['chup-hinh-catagory'][ChupHinhController::FOLDER]);
        foreach ($checkChupHinhData as $chuphinh) {
            if (file_exists(Url::to('@backend/web') . '/uploads/customer/' . $customer->slug . '-' . $customer->id . '/' . ChupHinhController::FOLDER . '/' . $chuphinh->image)) {
                $listChupHinh[] = [
                    'type' => 'local',
                    'id' => $chuphinh->id,
                    'name' => $chuphinh->image,
                    'webContentLink' => Url::to('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . ChupHinhController::FOLDER . '/' . $chuphinh->image,
                    'thumbnailLink' => Url::to('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . ChupHinhController::FOLDER . '/thumb/' . $chuphinh->image,
                ];
            }
        }
        /*$checkGDriveFolder = PhongKhamChupHinh::find()->where(['between', 'created_at', $time, $time + 86399])->andWhere(['customer_id' => $id])->one();
        if ($checkGDriveFolder == null) {
            $gDriveFolder = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($customer->full_name) . '-' . $id, date('d-m-Y'), self::FOLDER);
            $checkGDriveFolder = new PhongKhamChupHinh();
            $checkGDriveFolder->customer_id = $id;
            $checkGDriveFolder->folder_id = $gDriveFolder;
            $checkGDriveFolder->save();
        } else {
            $getFolder = GapiComponent::getFile($service, $checkGDriveFolder->folder_id);
            if ($getFolder != null) {
                $gDriveFolder = $checkGDriveFolder->folder_id;
            } else {
                $gDriveFolder = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($customer->full_name) . '-' . $id, date('d-m-Y'), self::FOLDER);
                $checkGDriveFolder->folder_id = $gDriveFolder;
                $checkGDriveFolder->save();
            }
        }*/
        if (count($listChupHinh) <= 0) {
            $rowChupHinh = PhongKhamChupHinh::find()->where(['customer_id' => $id])->all();
            if ($rowChupHinh != null) {
                foreach ($rowChupHinh as $chupHinh) {
                    $list = GapiComponent::getListFile($service, $chupHinh->folder_id);
                    if ($list != null && count($list) > 0) {
                        $listChupHinh = array_merge($listChupHinh, $list);
                    }
                }
            }
        }
        /* END CHUP HINH */
        /* CHUP BANH MOI */
        $checkChupBanhMoiData = CustomerImages::getListFilesByCustomer($id, Yii::$app->params['chup-hinh-catagory'][ChupBanhMoiController::FOLDER]);
        foreach ($checkChupBanhMoiData as $chuphinh) {
            if (file_exists(Url::to('@backend/web') . '/uploads/customer/' . $customer->slug . '-' . $customer->id . '/' . ChupBanhMoiController::FOLDER . '/' . $chuphinh->image)) {
                $listChupBanhMoi[] = [
                    'type' => 'local',
                    'id' => $chuphinh->id,
                    'name' => $chuphinh->image,
                    'webContentLink' => Url::to('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . ChupBanhMoiController::FOLDER . '/' . $chuphinh->image,
                    'thumbnailLink' => Url::to('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . ChupBanhMoiController::FOLDER . '/thumb/' . $chuphinh->image,
                ];
            }
        }
        /*$checkGDriveFolder = PhongKhamChupBanhMoi::find()->where(['between', 'created_at', $time, $time + 86399])->andWhere(['customer_id' => $id])->one();
        if ($checkGDriveFolder == null) {
            $gDriveFolder = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($customer->full_name) . '-' . $id, date('d-m-Y'), ChupBanhMoiController::FOLDER);
            $checkGDriveFolder = new PhongKhamChupBanhMoi();
            $checkGDriveFolder->customer_id = $id;
            $checkGDriveFolder->folder_id = $gDriveFolder;
            $checkGDriveFolder->save();
        } else {
            $getFolder = GapiComponent::getFile($service, $checkGDriveFolder->folder_id);
            if ($getFolder != null) {
                $gDriveFolder = $checkGDriveFolder->folder_id;
            } else {
                $gDriveFolder = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($customer->full_name) . '-' . $id, date('d-m-Y'), ChupBanhMoiController::FOLDER);
                $checkGDriveFolder->folder_id = $gDriveFolder;
                $checkGDriveFolder->save();
            }
        }*/
        if (count($listChupBanhMoi) <= 0) {
            $rowChupBanhMoi = PhongKhamChupBanhMoi::find()->where(['customer_id' => $id])->all();
            if ($rowChupBanhMoi != null) {
                foreach ($rowChupBanhMoi as $chupBanhMoi) {
                    $list = GapiComponent::getListFile($service, $chupBanhMoi->folder_id);
                    if ($list != null && count($list) > 0) {
                        $listChupBanhMoi = array_merge($listChupBanhMoi, $list);
                    }
                }
            }
        }
        /* END CHUP BANH MOI */
        /* CHUP CUI */
        $checkChupCuiData = CustomerImages::getListFilesByCustomer($id, Yii::$app->params['chup-hinh-catagory'][ChupCuiController::FOLDER]);
        foreach ($checkChupCuiData as $chuphinh) {
            if (file_exists(Url::to('@backend/web') . '/uploads/customer/' . $customer->slug . '-' . $customer->id . '/' . ChupCuiController::FOLDER . '/' . $chuphinh->image)) {
                $listChupCui[] = [
                    'type' => 'local',
                    'id' => $chuphinh->id,
                    'name' => $chuphinh->image,
                    'webContentLink' => Url::to('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . ChupCuiController::FOLDER . '/' . $chuphinh->image,
                    'thumbnailLink' => Url::to('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . ChupCuiController::FOLDER . '/thumb/' . $chuphinh->image,
                ];
            }
        }
        /*$checkGDriveFolder = PhongKhamChupCui::find()->where(['between', 'created_at', $time, $time + 86399])->andWhere(['customer_id' => $id])->one();
        if ($checkGDriveFolder == null) {
            $gDriveFolder = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($customer->full_name) . '-' . $id, date('d-m-Y'), ChupCuiController::FOLDER);
            $checkGDriveFolder = new PhongKhamChupCui();
            $checkGDriveFolder->customer_id = $id;
            $checkGDriveFolder->folder_id = $gDriveFolder;
            $checkGDriveFolder->save();
        } else {
            $getFolder = GapiComponent::getFile($service, $checkGDriveFolder->folder_id);
            if ($getFolder != null) {
                $gDriveFolder = $checkGDriveFolder->folder_id;
            } else {
                $gDriveFolder = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($customer->full_name) . '-' . $id, date('d-m-Y'), ChupCuiController::FOLDER);
                $checkGDriveFolder->folder_id = $gDriveFolder;
                $checkGDriveFolder->save();
            }
        }*/
        if (count($listChupCui) <= 0) {
            $rowChupCui = PhongKhamChupCui::find()->where(['customer_id' => $id])->all();
            if ($rowChupCui != null) {
                foreach ($rowChupCui as $chupCui) {
                    $list = GapiComponent::getListFile($service, $chupCui->folder_id);
                    if ($list != null && count($list) > 0) {
                        $listChupCui = array_merge($listChupCui, $list);
                    }
                }
            }
        }
        /* END CHUP CUI */
        /* CHUP KET THUC */
        $checkChupFinalData = CustomerImages::getListFilesByCustomer($id, Yii::$app->params['chup-hinh-catagory'][ChupFinalController::FOLDER]);
        foreach ($checkChupFinalData as $chuphinh) {
            if (file_exists(Url::to('@backend/web') . '/uploads/customer/' . $customer->slug . '-' . $customer->id . '/' . ChupFinalController::FOLDER . '/' . $chuphinh->image)) {
                $listChupFinal[] = [
                    'type' => 'local',
                    'id' => $chuphinh->id,
                    'name' => $chuphinh->image,
                    'webContentLink' => Url::to('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . ChupFinalController::FOLDER . '/' . $chuphinh->image,
                    'thumbnailLink' => Url::to('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . ChupFinalController::FOLDER . '/thumb/' . $chuphinh->image,
                ];
            }
        }
        /*$checkGDriveFolder = PhongKhamChupFinal::find()->where(['between', 'created_at', $time, $time + 86399])->andWhere(['customer_id' => $id])->one();
        if ($checkGDriveFolder == null) {
            $gDriveFolder = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($customer->full_name) . '-' . $id, date('d-m-Y'), ChupFinalController::FOLDER);
            $checkGDriveFolder = new PhongKhamChupFinal();
            $checkGDriveFolder->customer_id = $id;
            $checkGDriveFolder->folder_id = $gDriveFolder;
            $checkGDriveFolder->save();
        } else {
            $getFolder = GapiComponent::getFile($service, $checkGDriveFolder->folder_id);
            if ($getFolder != null) {
                $gDriveFolder = $checkGDriveFolder->folder_id;
            } else {
                $gDriveFolder = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($customer->full_name) . '-' . $id, date('d-m-Y'), ChupFinalController::FOLDER);
                $checkGDriveFolder->folder_id = $gDriveFolder;
                $checkGDriveFolder->save();
            }
        }*/
        if (count($listChupFinal) <= 0) {
            $rowChupFinal = PhongKhamChupFinal::find()->where(['customer_id' => $id])->all();
            if ($rowChupFinal != null) {
                foreach ($rowChupFinal as $chupFinal) {
                    $list = GapiComponent::getListFile($service, $chupFinal->folder_id);
                    if ($list != null && count($list) > 0) {
                        $listChupFinal = array_merge($listChupFinal, $list);
                    }
                }
            }
        }
        /* END CHUP KET THUC */
        /* HINH TKNC */
        $checkHinhTkncData = CustomerImages::getListFilesByCustomer($id, Yii::$app->params['chup-hinh-catagory'][TkncController::FOLDER]);
        foreach ($checkHinhTkncData as $chuphinh) {
            if (file_exists(Url::to('@backend/web') . '/uploads/customer/' . $customer->slug . '-' . $customer->id . '/' . TkncController::FOLDER . '/' . $chuphinh->image)) {
                $listHinhTknc[] = [
                    'type' => 'local',
                    'id' => $chuphinh->id,
                    'name' => $chuphinh->image,
                    'webContentLink' => Url::to('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . TkncController::FOLDER . '/' . $chuphinh->image,
                    'thumbnailLink' => Url::to('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . TkncController::FOLDER . '/thumb/' . $chuphinh->image,
                    'imageType' => $chuphinh->type
                ];
            }
        }
        /*$checkGDriveFolder = PhongKhamHinhTknc::find()->where(['between', 'created_at', $time, $time + 86399])->andWhere(['customer_id' => $id])->one();
        if ($checkGDriveFolder == null) {
            $gDriveFolder = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($customer->full_name) . '-' . $id, date('d-m-Y'), TkncController::FOLDER);
            $checkGDriveFolder = new PhongKhamHinhTknc();
            $checkGDriveFolder->customer_id = $id;
            $checkGDriveFolder->folder_id = $gDriveFolder;
            $checkGDriveFolder->save();
        } else {
            $getFolder = GapiComponent::getFile($service, $checkGDriveFolder->folder_id);
            if ($getFolder != null) {
                $gDriveFolder = $checkGDriveFolder->folder_id;
            } else {
                $gDriveFolder = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($customer->full_name) . '-' . $id, date('d-m-Y'), TkncController::FOLDER);
                $checkGDriveFolder->folder_id = $gDriveFolder;
                $checkGDriveFolder->save();
            }
        }*/
        if (count($listHinhTknc) <= 0) {
            $rowTknc = PhongKhamHinhTknc::find()->where(['customer_id' => $id])->all();
            if ($rowTknc != null) {
                foreach ($rowTknc as $tknc) {
                    $list = GapiComponent::getListFile($service, $tknc->folder_id);
                    if ($list != null && count($list) > 0) {
                        $listHinhTknc = array_merge($listHinhTknc, $list);
                    }
                }
            }
        }
        /* END HINH TKNC */
        /* UOM RANG 1 */
        $checkUomRang1Data = CustomerImages::getListFilesByCustomer($id, Yii::$app->params['chup-hinh-catagory'][UomRang1Controller::FOLDER]);
        foreach ($checkUomRang1Data as $chuphinh) {
            if (file_exists(Url::to('@backend/web') . '/uploads/customer/' . $customer->slug . '-' . $customer->id . '/' . UomRang1Controller::FOLDER . '/' . $chuphinh->image)) {
                $listUomRang1[] = [
                    'type' => 'local',
                    'id' => $chuphinh->id,
                    'name' => $chuphinh->image,
                    'webContentLink' => Url::to('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . UomRang1Controller::FOLDER . '/' . $chuphinh->image,
                    'thumbnailLink' => Url::to('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . UomRang1Controller::FOLDER . '/thumb/' . $chuphinh->image,
                ];
            }
        }
        /*$checkGDriveFolder = PhongKhamUomRang1::find()->where(['between', 'created_at', $time, $time + 86399])->andWhere(['customer_id' => $id])->one();
        if ($checkGDriveFolder == null) {
            $gDriveFolder = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($customer->full_name) . '-' . $id, date('d-m-Y'), UomRang1Controller::FOLDER);
            $checkGDriveFolder = new PhongKhamUomRang1();
            $checkGDriveFolder->customer_id = $id;
            $checkGDriveFolder->folder_id = $gDriveFolder;
            $checkGDriveFolder->save();
        } else {
            $getFolder = GapiComponent::getFile($service, $checkGDriveFolder->folder_id);
            if ($getFolder != null) {
                $gDriveFolder = $checkGDriveFolder->folder_id;
            } else {
                $gDriveFolder = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($customer->full_name) . '-' . $id, date('d-m-Y'), UomRang1Controller::FOLDER);
                $checkGDriveFolder->folder_id = $gDriveFolder;
                $checkGDriveFolder->save();
            }
        }*/
        if (count($listUomRang1) <= 0) {
            $rowUomRang1 = PhongKhamUomRang1::find()->where(['customer_id' => $id])->all();
            if ($rowUomRang1 != null) {
                foreach ($rowUomRang1 as $uomRang1) {
                    $list = GapiComponent::getListFile($service, $uomRang1->folder_id);
                    if ($list != null && count($list) > 0) {
                        $listUomRang1 = array_merge($listUomRang1, $list);
                    }
                }
            }
        }
        /* END UOM RANG 1 */
        /* UOM RANG 2 */
        $checkUomRang2Data = CustomerImages::getListFilesByCustomer($id, Yii::$app->params['chup-hinh-catagory'][UomRang2Controller::FOLDER]);
        foreach ($checkUomRang2Data as $chuphinh) {
            if (file_exists(Url::to('@backend/web') . '/uploads/customer/' . $customer->slug . '-' . $customer->id . '/' . UomRang2Controller::FOLDER . '/' . $chuphinh->image)) {
                $listUomRang2[] = [
                    'type' => 'local',
                    'id' => $chuphinh->id,
                    'name' => $chuphinh->image,
                    'webContentLink' => Url::to('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . UomRang2Controller::FOLDER . '/' . $chuphinh->image,
                    'thumbnailLink' => Url::to('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . UomRang2Controller::FOLDER . '/thumb/' . $chuphinh->image,
                ];
            }
        }
        /*$checkGDriveFolder = PhongKhamUomRang2::find()->where(['between', 'created_at', $time, $time + 86399])->andWhere(['customer_id' => $id])->one();
        if ($checkGDriveFolder == null) {
            $gDriveFolder = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($customer->full_name) . '-' . $id, date('d-m-Y'), UomRang2Controller::FOLDER);
            $checkGDriveFolder = new PhongKhamUomRang2();
            $checkGDriveFolder->customer_id = $id;
            $checkGDriveFolder->folder_id = $gDriveFolder;
            $checkGDriveFolder->save();
        } else {
            $getFolder = GapiComponent::getFile($service, $checkGDriveFolder->folder_id);
            if ($getFolder != null) {
                $gDriveFolder = $checkGDriveFolder->folder_id;
            } else {
                $gDriveFolder = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($customer->full_name) . '-' . $id, date('d-m-Y'), UomRang2Controller::FOLDER);
                $checkGDriveFolder->folder_id = $gDriveFolder;
                $checkGDriveFolder->save();
            }
        }*/
        if (count($listUomRang2) <= 0) {
            $rowUomRang2 = PhongKhamUomRang2::find()->where(['customer_id' => $id])->all();
            if ($rowUomRang2 != null) {
                foreach ($rowUomRang2 as $uomRang2) {
                    $list = GapiComponent::getListFile($service, $uomRang2->folder_id);
                    if ($list != null && count($list) > 0) {
                        $listUomRang2 = array_merge($listUomRang2, $list);
                    }
                }
            }
        }
        /* END UOM RANG 2 */
        /* HINH FINAL */
        $checkHinhFinalData = CustomerImages::getListFilesByCustomer($id, Yii::$app->params['chup-hinh-catagory'][HinhFinalController::FOLDER]);
        foreach ($checkHinhFinalData as $chuphinh) {
            if (file_exists(Url::to('@backend/web') . '/uploads/customer/' . $customer->slug . '-' . $customer->id . '/' . HinhFinalController::FOLDER . '/' . $chuphinh->image)) {
                $listHinhFinal[] = [
                    'type' => 'local',
                    'id' => $chuphinh->id,
                    'name' => $chuphinh->image,
                    'webContentLink' => Url::to('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . HinhFinalController::FOLDER . '/' . $chuphinh->image,
                    'thumbnailLink' => Url::to('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . HinhFinalController::FOLDER . '/thumb/' . $chuphinh->image,
                ];
            }
        }
        /*$checkGDriveFolder = PhongKhamHinhFinal::find()->where(['between', 'created_at', $time, $time + 86399])->andWhere(['customer_id' => $id])->one();
        if ($checkGDriveFolder == null) {
            $gDriveFolder = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($customer->full_name) . '-' . $id, date('d-m-Y'), HinhFinalController::FOLDER);
            $checkGDriveFolder = new PhongKhamHinhFinal();
            $checkGDriveFolder->customer_id = $id;
            $checkGDriveFolder->folder_id = $gDriveFolder;
            $checkGDriveFolder->save();
        } else {
            $getFolder = GapiComponent::getFile($service, $checkGDriveFolder->folder_id);
            if ($getFolder != null) {
                $gDriveFolder = $checkGDriveFolder->folder_id;
            } else {
                $gDriveFolder = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($customer->full_name) . '-' . $id, date('d-m-Y'), HinhFinalController::FOLDER);
                $checkGDriveFolder->folder_id = $gDriveFolder;
                $checkGDriveFolder->save();
            }
        }*/
        if (count($listHinhFinal) <= 0) {
            $rowHinhFinal = PhongKhamHinhFinal::find()->where(['customer_id' => $id])->all();
            if ($rowHinhFinal != null) {
                foreach ($rowHinhFinal as $hinhFinal) {
                    $list = GapiComponent::getListFile($service, $hinhFinal->folder_id);
                    if ($list != null && count($list) > 0) {
                        $listHinhFinal = array_merge($listHinhFinal, $list);
                    }
                }
            }
        }
        /* END HINH FINAL */
        /* DENTAL FORM */
        $checkDentalForm = CustomerImages::getListFilesByCustomer($id, Yii::$app->params['chup-hinh-catagory'][DentalFormController::FOLDER]);
        foreach ($checkDentalForm as $dentalForm) {
            if (file_exists(Url::to('@backend/web') . '/uploads/customer/' . $customer->slug . '-' . $customer->id . '/' . DentalFormController::FOLDER . '/' . $dentalForm->image)) {
                $listDentalForm[] = [
                    'type' => 'local',
                    'id' => $dentalForm->id,
                    'name' => $dentalForm->image,
                    'webContentLink' => Url::to('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . DentalFormController::FOLDER . '/' . $dentalForm->image,
                    'thumbnailLink' => Url::to('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . DentalFormController::FOLDER . '/thumb/' . $dentalForm->image,
                    'imageType' => $dentalForm->type
                ];
            }
        }
        /*$checkGDriveFolder = PhongKhamDentalForm::find()->where(['between', 'created_at', $time, $time + 86399])->andWhere(['customer_id' => $id])->one();
        if ($checkGDriveFolder == null) {
            $gDriveFolder = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($customer->full_name) . '-' . $id, date('d-m-Y'), DentalFormController::FOLDER);
            $checkGDriveFolder = new PhongKhamDentalForm();
            $checkGDriveFolder->customer_id = $id;
            $checkGDriveFolder->folder_id = $gDriveFolder;
            $checkGDriveFolder->save();
        } else {
            $getFolder = GapiComponent::getFile($service, $checkGDriveFolder->folder_id);
            if ($getFolder != null) {
                $gDriveFolder = $checkGDriveFolder->folder_id;
            } else {
                $gDriveFolder = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($customer->full_name) . '-' . $id, date('d-m-Y'), DentalFormController::FOLDER);
                $checkGDriveFolder->folder_id = $gDriveFolder;
                $checkGDriveFolder->save();
            }
        }*/
        if (count($listDentalForm) <= 0) {
            $rowDentalForm = PhongKhamDentalForm::find()->where(['customer_id' => $id])->all();
            if ($rowDentalForm != null) {
                foreach ($rowDentalForm as $dentalForm) {
                    $list = GapiComponent::getListFile($service, $dentalForm->folder_id);
                    if ($list != null && count($list) > 0) {
                        $listDentalForm = array_merge($listDentalForm, $list);
                    }
                }
            }
        }
        /* END DENTAL FORM */
        return $this->render('view/_hinh_anh_khach_hang', [
            'id' => $id,
            'customer' => $customer,
            'formAvatar' => $formAvatar,
            'formChupHinh' => $formChupHinh,
            'formChupBanhMoi' => $formChupBanhMoi,
            'formChupCui' => $formChupCui,
            'formChupFinal' => $formChupFinal,
            'formHinhTknc' => $formHinhTknc,
            'formUomRang1' => $formUomRang1,
            'formUomRang2' => $formUomRang2,
            'formHinhFinal' => $formHinhFinal,
            'formDentalForm' => $formDentalForm,
            'listChupHinh' => $listChupHinh,
            'listChupBanhMoi' => $listChupBanhMoi,
            'listChupCui' => $listChupCui,
            'listChupFinal' => $listChupFinal,
            'listHinhTknc' => $listHinhTknc,
            'listUomRang1' => $listUomRang1,
            'listUomRang2' => $listUomRang2,
            'listHinhFinal' => $listHinhFinal,
            'listDentalForm' => $listDentalForm,
        ]);
    }

    public function actionQuanLyCongDoan($id)
    {

        $searchModel = new SearchLaboGiaiDoan();
        $dataProvider = $searchModel->searchByDonHang(Yii::$app->request->queryParams, $id);

        if (MyComponent::hasCookies('pageSize')) {
            $dataProvider->pagination->pageSize = MyComponent::getCookies('pageSize');
        } else {
            $dataProvider->pagination->pageSize = 10;
        }

        $pageSize = $dataProvider->pagination->pageSize;

        $totalCount = $dataProvider->totalCount;

        $totalPage = (($totalCount + $pageSize - 1) / $pageSize);

        return $this->render('view/_quan_ly_cong_doan', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalPage' => $totalPage,
            'labo_don_hang_id' => $id,
        ]);

        //        return $this->render('view/_quan_ly_cong_doan');
    }
}
