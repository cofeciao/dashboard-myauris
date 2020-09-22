<?php

namespace backend\modules\api\modules\v2\controllers;

use backend\components\GapiComponent;
use backend\components\MtSmsComponent;
use backend\modules\api\components\RestAccessTokenController;
use backend\modules\api\modules\v2\components\RestfullController;
use backend\modules\appmyauris\models\AppMyauris;
use backend\modules\appmyauris\models\AppMyaurisCustomerLog;
use backend\modules\appmyauris\models\AppMyaurisGroupSanPham;
use backend\modules\clinic\controllers\ChupBanhMoiController;
use backend\modules\clinic\controllers\ChupCuiController;
use backend\modules\clinic\controllers\ChupFinalController;
use backend\modules\clinic\controllers\ChupHinhController;
use backend\modules\clinic\controllers\HinhFinalController;
use backend\modules\clinic\controllers\TkncController;
use backend\modules\clinic\controllers\UomRang1Controller;
use backend\modules\clinic\controllers\UomRang2Controller;
use backend\modules\clinic\models\Clinic;
use backend\modules\clinic\models\CustomerImages;
use backend\modules\clinic\models\form\FormChupHinh;
use backend\modules\clinic\models\form\FromAudio;
use backend\modules\clinic\models\PhongKhamChupBanhMoi;
use backend\modules\clinic\models\PhongKhamChupCui;
use backend\modules\clinic\models\PhongKhamChupFinal;
use backend\modules\clinic\models\PhongKhamChupHinh;
use backend\modules\clinic\models\PhongKhamHinhFinal;
use backend\modules\clinic\models\PhongKhamHinhTknc;
use backend\modules\clinic\models\PhongKhamSanPham;
use backend\modules\clinic\models\PhongKhamUomRang1;
use backend\modules\clinic\models\PhongKhamUomRang2;
use backend\modules\clinic\models\UploadAudio;
use backend\modules\recommend\models\Recommend;
use backend\modules\setting\models\Setting;
use backend\modules\toothstatus\models\TinhTrangRang;
use common\commands\DeleteImageCommand;
use common\commands\ImageCommand;
use common\helpers\MyHelper;
use GuzzleHttp\Client;
use Yii;
use yii\db\Transaction;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\UploadedFile;
use backend\modules\appmyauris\models\TableTemp;
use backend\models\CustomerModel;
use backend\modules\clinic\controllers\DentalFormController;

class AppController extends RestfullController
{
    public $modelClass = 'backend\modules\api\modules\v1\models\UserApi';


    public function actionGet_list_reason_cancel()
    {
        return Yii::$app->params["ly-do-khong-lam"];
    }

    public function actionUpload_image()
    {
        $post = \Yii::$app->request->post();

        if (empty(UploadedFile::getInstancesByName("fileImage"))) {
            return [
                'code' => 400,
                'message' => "Not enough data. Unknown file for 'fileImage'",
            ];
        }

        if (!isset($post['customer_id'])) {
            return [
                'code' => 400,
                'message' => "Not enough data. Unknown file for 'customer_id'",
            ];
        }
        $customer = Clinic::find()->where(['id' => $post['customer_id']])->one();
        if ($customer === null) {
            return [
                'code' => 400,
                'message' => "Wrong data customer_id . Unknown customer by 'customer_id'",
            ];
        }
        if ($customer->full_name == null) {
            return [
                'code' => 400,
                'message' => "Wrong data customer_id. Please update Customer Full Name",
            ];
        }

        if (!isset($post['catagory_id'])) {
            return [
                'code' => 400,
                'message' => "Not enough data. Unknown value for 'catagory_id'",
            ];
        }
        $aCategory = Yii::$app->params["chup-hinh-catagory"];
        $catagory_id = $post['catagory_id'];

        if (!in_array($catagory_id, $aCategory)) {
            return [
                'code' => 400,
                'message' => "Wrong data catagory_id. Unknown name catagory by 'catagory_id'",
            ];
        }
        $aCategoryGetValue = array_flip($aCategory);
        $service = GapiComponent::getService();
        $time = strtotime(date('d-m-Y'));
        $id = $post['customer_id'];

        $id_loai_chup_hinh = null;
        if ((int)$catagory_id !== 6) {
            if (!isset($post['id_loai_chup_hinh'])) {
                return [
                    'code' => 400,
                    'message' => "Not enough data. Unknown value for 'id_loai_chup_hinh'",
                ];
            } else {
                $id_loai_chup_hinh = $post['id_loai_chup_hinh'];
            }
        }

        if ($aCategoryGetValue[$catagory_id] == "chup-hinh") {
            $mUploadImage = new PhongKhamChupHinh();
            return $this->handleUploadImageAPI($service, $time, $id, $customer, $mUploadImage, ChupHinhController::FOLDER, $id_loai_chup_hinh);
        }

        if ($aCategoryGetValue[$catagory_id] == "chup-banh-moi") {
            $mUploadImage = new PhongKhamChupBanhMoi();
            return $this->handleUploadImageAPI($service, $time, $id, $customer, $mUploadImage, ChupBanhMoiController::FOLDER, $id_loai_chup_hinh);
        }

        if ($aCategoryGetValue[$catagory_id] == "chup-cui") {
            $mUploadImage = new PhongKhamChupCui();
            return $this->handleUploadImageAPI($service, $time, $id, $customer, $mUploadImage, ChupCuiController::FOLDER, $id_loai_chup_hinh);
        }

        if ($aCategoryGetValue[$catagory_id] == "chup-ket-thuc") {
            $mUploadImage = new PhongKhamChupFinal();
            return $this->handleUploadImageAPI($service, $time, $id, $customer, $mUploadImage, ChupFinalController::FOLDER, $id_loai_chup_hinh);
        }

        if ($aCategoryGetValue[$catagory_id] == "thiet-ke-nu-cuoi") {
            $mUploadImage = new PhongKhamHinhTknc();
            $dataHandle = $this->handleUploadImageAPI($service, $time, $id, $customer, $mUploadImage, TkncController::FOLDER, $id_loai_chup_hinh);
            if ($dataHandle['code'] === 200) {
                if (CONSOLE_HOST == false/*\Yii::$app->request->getUserIP() == '127.0.0.1'*/) {
                    $client = new Client([
                        'verify' => Url::to('@backend/modules/clinic/token/cacert.pem')
                    ]);
                } else {
                    $client = new Client();
                }
                $client->request('GET', 'https://api.myauris.vn/api/PushNotification?user_id=' . $customer->directsale . '&customer_id=' . $customer->primaryKey);
            }
            return $dataHandle;
        }

        if ($aCategoryGetValue[$catagory_id] == "uom_rang_1") { // $catagory_id == 7

            $mUploadImage = new PhongKhamUomRang1();
            return $this->handleUploadImageAPI($service, $time, $id, $customer, $mUploadImage, UomRang1Controller::FOLDER, $id_loai_chup_hinh);
        }
        if ($aCategoryGetValue[$catagory_id] == "uom_rang_2") { // $catagory_id == 8

            $mUploadImage = new PhongKhamUomRang2();
            return $this->handleUploadImageAPI($service, $time, $id, $customer, $mUploadImage, UomRang2Controller::FOLDER, $id_loai_chup_hinh);
        }
        if ($aCategoryGetValue[$catagory_id] == "hinh_final") { // $catagory_id == 9

            $mUploadImage = new PhongKhamHinhFinal();
            return $this->handleUploadImageAPI($service, $time, $id, $customer, $mUploadImage, HinhFinalController::FOLDER, $id_loai_chup_hinh);
        }

        if ($aCategoryGetValue[$catagory_id] == "upload-audio") { // $catagory_id == 6
            $mUploadImage = new UploadAudio();
            return $this->handleUploadAudio($service, $time, $id, $customer, $mUploadImage, UploadAudio::FOLDER);
        }
    }

    // $customer = customer model
    // $id = $customer_id
    // $mUploadImage  == PhongKhamChupHinh
    // $FolderUploadImage  ==   ChupHinhController::FOLDER

    protected function handleUploadImageAPI($service, $time, $id, $customer, $mUploadImage, $FolderUploadImage, $id_loai_chup_hinh)
    {
        $checkGDriveFolder = $mUploadImage::find()->where(['between', 'created_at', $time, $time + 86399])->andWhere(['customer_id' => $id])->one();
        if ($checkGDriveFolder == null) {
            $gDriveFolder = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($customer->full_name) . '-' . $id, date('d-m-Y'), $FolderUploadImage);
            $checkGDriveFolder = $mUploadImage;
            $checkGDriveFolder->customer_id = $id;
            $checkGDriveFolder->folder_id = $gDriveFolder;
            $checkGDriveFolder->save();
        } else {
            $getFolder = GapiComponent::getFile($service, $checkGDriveFolder->folder_id);
            if ($getFolder != null) {
                $gDriveFolder = $checkGDriveFolder->folder_id;
            } else {
                $gDriveFolder = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($customer->full_name) . '-' . $id, date('d-m-Y'), $FolderUploadImage);
                $checkGDriveFolder->folder_id = $gDriveFolder;
                $checkGDriveFolder->save();
            }
        }
        $model = new FormChupHinh(); // only check valiation
        $transaction = Yii::$app->db->beginTransaction(
            Transaction::SERIALIZABLE
        );

        $gDriveFolder = $checkGDriveFolder->folder_id;
        $file = UploadedFile::getInstancesByName("fileImage");
        $model->fileImage = $file;
        if ($model->validate()) {
            $fileName = $file[0]->baseName . '.' . $file[0]->extension;
            if ($file[0]->saveAs(Yii::getAlias('@backend/web') . '/uploads/tmp/' . $fileName)) {
                $urlFile = Yii::$app->basePath . '/web/uploads/tmp/' . $fileName;
                $image = $this->createImageApi('@backend/web', $urlFile, 220, 220, '/uploads/customer/' . $customer->slug . '-' . $customer->id . '/' . $FolderUploadImage . '/thumb/', null, true, true);
                $this->createImageApi('@backend/web', $urlFile, null, null, '/uploads/customer/' . $customer->slug . '-' . $customer->id . '/' . $FolderUploadImage . '/', $image, true, true);
                $idImage = GapiComponent::uploadImageToDrive($service, $fileName, '@backend/web/uploads/tmp', $gDriveFolder);
                if ($idImage == null) {
                    return [
                        'code' => 403,
                        'message' => "Image upload to drive failed",
                    ];
                } else {
                    $this->deleteImageApi('@backend/web', '/uploads/tmp/', $fileName);
                    $customerImage = new CustomerImages();
                    $customerImage->customer_id = $customer->id;
                    $customerImage->catagory_id = Yii::$app->params['chup-hinh-catagory'][$FolderUploadImage];
                    $customerImage->image = $image;
                    $customerImage->google_id = $idImage;
                    $customerImage->id_loai_chup_hinh = $id_loai_chup_hinh;
                    if (!$customerImage->save()) {
                        $data = $customerImage->getErrors();
                        $transaction->rollBack();
                        return [
                            'code' => 400,
                            'data' => $data,
                            'message' => "Save failed",
                            'image' => $image,
                        ];
                    } else {
                        $transaction->commit();
                        return [
                            'code' => 200,
                            'message' => "Save successful",
                        ];
                    }
                }
            }
        } else {
            $transaction->rollBack();
            return [
                'code' => 400,
                'message' => 'Wrong validate',
            ];
        }
    }

    protected function createImageApi($path, $image, $width, $height, $alias, $fileName = null, $debug = false, $img_link = false)
    {
        return Yii::$app->commandBus->handle(new ImageCommand([
            'path' => $path,
            'image' => $image,
            'width' => $width,
            'height' => $height,
            'alias' => $alias,
            'fileName' => $fileName,
            'debug' => $debug,
            'img_link' => $img_link
        ]));
    }

    protected function deleteImageApi($getAlias, $alias, $image)
    {
        return Yii::$app->commandBus->handle(new DeleteImageCommand([
            'getAlias' => $getAlias,
            'alias' => $alias,
            'image' => $image,
        ]));
    }

    protected function handleUploadAudio($service, $time, $id, $customer, $mUploadImage, $FolderUploadImage)
    {
        $checkGDriveFolder = $mUploadImage::find()->where(['between', 'created_at', $time, $time + 86399])->andWhere(['customer_id' => $id])->one();
        if ($checkGDriveFolder == null) {
            $gDriveFolder = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($customer->full_name) . '-' . $id, date('d-m-Y'), $FolderUploadImage);
            $checkGDriveFolder = $mUploadImage;
            $checkGDriveFolder->customer_id = $id;
            $checkGDriveFolder->folder_id = $gDriveFolder;
            $checkGDriveFolder->save();
        } else {
            $getFolder = GapiComponent::getFile($service, $checkGDriveFolder->folder_id);
            if ($getFolder != null) {
                $gDriveFolder = $checkGDriveFolder->folder_id;
            } else {
                $gDriveFolder = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($customer->full_name) . '-' . $id, date('d-m-Y'), $FolderUploadImage);
                $checkGDriveFolder->folder_id = $gDriveFolder;
                $checkGDriveFolder->save();
            }
        }
        $model = new FromAudio(); // only check valiation
        $transaction = Yii::$app->db->beginTransaction(
            Transaction::SERIALIZABLE
        );

        $gDriveFolder = $checkGDriveFolder->folder_id;
        $file = UploadedFile::getInstancesByName("fileImage");
        $model->fileImage = $file;
        //                $fileName = $file[0]->baseName . '.' . $file[0]->extension; // Integrity constraint violation: 1062 Duplicate entry  image
        $fileName = time() . '.' . $file[0]->extension;
        if ($file[0]->saveAs(Yii::getAlias('@backend/web') . '/uploads/audio/' . $fileName)) {
            $urlFile = Yii::$app->basePath . '/web/uploads/audio/' . $fileName;
            $idImage = GapiComponent::uploadImageToDrive($service, $fileName, '@backend/web/uploads/audio', $gDriveFolder);
            if ($idImage == null) {
                return [
                    'code' => 403,
                    'message' => "Image upload to drive failed",
                ];
            } else {
                $customerImage = new CustomerImages();
                $customerImage->customer_id = $customer->id;
                $customerImage->catagory_id = Yii::$app->params['chup-hinh-catagory'][$FolderUploadImage];
                $customerImage->image = $fileName;
                $customerImage->google_id = $idImage;
                if (!$customerImage->save()) {
                    $data = $customerImage->getErrors();
                    $transaction->rollBack();
                    return [
                        'code' => 400,
                        'data' => $data,
                        'message' => "Save failed",
                    ];
                } else {
                    $transaction->commit();
                    return [
                        'code' => 200,
                        'message' => "Save successful",
                    ];
                }
            }
        }
    }

    // only for test

    public function actionGet_access_token_driver()
    {
        $client = GapiComponent::getClient();
        return $client->getAccessToken();
    }

    public function actionDelete_image()
    {
        $post = \Yii::$app->request->post();

        if (!isset($post['catagory_id'])) {
            return [
                'code' => 400,
                'message' => "Not enough data. Unknown value for 'catagory_id'",
            ];
        }
        if (!isset($post['google_id'])) {
            return [
                'code' => 400,
                'message' => "Not enough data. Unknown value for 'google_id'",
            ];
        }
        if (!isset($post['customer_id'])) {
            return [
                'code' => 400,
                'message' => "Not enough data. Unknown value for 'customer_id'",
            ];
        }
        $customer = Clinic::find()->where(['id' => $post['customer_id']])->one();
        if ($customer === null) {
            return [
                'code' => 400,
                'message' => "Wrong data customer_id. Unknown customer by 'customer_id' ",
            ];
        }
        $aCategory = Yii::$app->params["chup-hinh-catagory"];
        $aCategoryGetValue = array_flip($aCategory);
        $catagory_id = $post['catagory_id'];
        $google_id = $post['google_id'];
        $customer_id = $post['customer_id'];

        if (!in_array($catagory_id, $aCategory)) {
            return [
                'code' => 400,
                'message' => "Wrong data catagory_id. Unknown name catagory by 'catagory_id'",
            ];
        }
        $mCustomerImage = CustomerImages::getFilesByCustomerByDetail($customer_id, $catagory_id, $google_id);
        if (!$mCustomerImage) {
            return [
                'code' => 400,
                'message' => "Data not found. Unknown image by 'customer_id', 'catagory_id', 'google_id'",
            ];
        }
        // begin delete image on server
        $FolderUploadImage = $aCategoryGetValue[$catagory_id];
        $fileName = $mCustomerImage->image;

        $this->deleteImageApi('@backend/web', '/uploads/customer/' . $customer->slug . '-' . $customer->id . '/' . $FolderUploadImage . '/thumb/', $fileName);
        $this->deleteImageApi('@backend/web', '/uploads/customer/' . $customer->slug . '-' . $customer->id . '/' . $FolderUploadImage . '/', $fileName);
        $service = GapiComponent::getService();
        GapiComponent::deleteImageToDrive($service, $google_id); // Delete Image Drive
        $mCustomerImage->delete();

        return [
            'code' => 200,
            'message' => "Done. Delete image success."
        ];
    }

    public function actionTest_get_user()
    {
        $post = \Yii::$app->request->post();
        if (!isset($post['catagory_id']) || !isset($post['customer_id'])) {
            return [
                'code' => 400,
                'message' => "Not enough data. Unknown value for 'catagory_id' or 'customer_id'",
            ];
        }
        $customer = Clinic::find()->where(['id' => $post['customer_id']])->one();
        if ($customer === null) {
            return [
                'code' => 400,
                'message' => "Wrong data customer_id. Unknown customer by 'customer_id'",
            ];
        }
        $listCustomerImage = CustomerImages::getListFilesByCustomer($customer->id, $post['catagory_id']);
        $listFile = [];
        foreach ($listCustomerImage as $chuphinh) {
            $listFile[$chuphinh->id] = $chuphinh->google_id;
        }
        return $listFile;
    }

    public function actionGet_list_focus_face()
    {
        return Yii::$app->params["focus-face"];
    }

    public function actionTinhTrangRang()
    {
        $listTinhTrang = ArrayHelper::map(TinhTrangRang::getListTinhTrangRangAPI(), 'id', 'name');
        return $listTinhTrang;
    }

    public function actionTinhTrangBenhNhan()
    {
        return Yii::$app->params["tinh-trang-benh-nhan"];
    }

    public function actionInfoConfigChupHinh()
    {
        return Yii::$app->params["info-config-chup-hinh"];
    }

    // API APP MYAURIS V2
    public function actionNoi_dung_tu_van()
    {
        $model_setting_app_key_content = Setting::find()->where(['key_value' => AppMyauris::SETTING_APP_KEY_CONTENT])->one();
        if ($model_setting_app_key_content != null) {
            return $model_setting_app_key_content->value;
        }
        return "";
    }

    public function actionGiai_phap_gioi_tinh()
    {
        return Recommend::getListGioiTinh();
    }

    public function actionGiai_phap_bo_cuc()
    {
        return Recommend::getListBoCuc();
    }

    public function actionGiai_phap_nhom_tuoi()
    {
        return Recommend::getListNhomTuoi();
    }

    public function actionGiai_phap_tinh_trang_rang()
    {
        return Recommend::getListTinhTrangRang();
    }

    public function actionGiai_phap_mong_muon()
    {
        return Recommend::getListMongMuon();
    }

    public function actionGiai_phap_phong_cach()
    {
        return Recommend::getListPhongCach();
    }

    public function actionGiai_phap_benh_ly()
    {
        return Recommend::getListBenhLy();
    }

    public function actionGiai_phap_phan_loai()
    {
        return Recommend::getListPhanLoai();
    }

    // Phuong Phap la giai phap
    public function actionGiai_phap_phuong_phap()
    {
        return Recommend::getListGiaiPhap();
    }

    public function actionKhoi_tao_giai_phap()
    {
        $post = \Yii::$app->request->post();

        $recommend = new Recommend();
        $query = $recommend->createQueryRecommend($post);
        $list = $query->all();
        $aGiaiPhap = ArrayHelper::toArray($list);
        $result = [];
        if (count($aGiaiPhap) > 0) {
            foreach ($aGiaiPhap as $element) {
                $result[] = [
                    'id' => $element['id'],
                    'tieu_de' => $element['tieu_de'],
                    'mo_ta' => $element['mo_ta'],
                    'phan_loai' => $element['phan_loai'],
                    'group' => $element['san_pham'], // @NGHIA
                    'video' => $element['video'],
                    'vat_lieu' => $element['vat_lieu'],
                ];
            }
        }
        return $result;
    }

    public function actionDanh_sach_san_pham()
    {

        $cache = Yii::$app->cache;
        $key = 'redis-api-get-san-pham';
        $data = $cache->get($key);
        if ($data === false) {
            $aSanPham = ArrayHelper::toArray(PhongKhamSanPham::getSanPham());
            $result = [];
            foreach ($aSanPham as $element) {
                $result[$element['id']] = [
                    'name' => $element['name'],
                    'don_gia' => $element['don_gia'],
                ];
            }
            $cache->set($key, $result, 86400);
            return $result;
        }
        return $data;
    }

    /**
     * Luu thong tin tu van cho khach hang
     * id = customer_id
     * tu_van = json
     * don_hang = json
     * @return true/false
     */
    public function actionLuu_thong_tin_tu_van()
    {
        $post = \Yii::$app->request->post();

        if (!isset($post['customer_id'])) {
            return [
                'code' => 400,
                'message' => "Not enough data. Unknown value for 'customer_id'",
            ];
        }

        if (!isset($post['tu_van'])) {
            return [
                'code' => 400,
                'message' => "Not enough data. Unknown value for 'tu_van', json",
            ];
        }

        if (!isset($post['don_hang'])) {
            return [
                'code' => 400,
                'message' => "Not enough data. Unknown value for 'don_hang', json",
            ];
        }

        try {
            $appCustomerLog = new AppMyaurisCustomerLog();
            $appCustomerLog->customer_id = $post['customer_id'];
            $appCustomerLog->tu_van = $post['tu_van'];
            $appCustomerLog->don_hang = $post['don_hang'];
            $appCustomerLog->status = AppMyaurisCustomerLog::STATUS_PUBLISHED;
            $appCustomerLog->save();
            return [
                'code' => 200,
                'message' => "Lưu thông tin thành công."
            ];
        } catch (\yii\db\Exception $exception) {
            return [
                'code' => 500,
                'message' => "Lưu không thành công."
            ];
        }
    }

    // 
    public function actionHinhAnhNguoiNoiTieng()
    {
        $TableTemp = TableTemp::find()->select(['image_before', 'image_after', 'name'])->where(['nguoi_noi_tieng' => 1])->all();
        return ArrayHelper::toArray($TableTemp);
    }

    public function actionDanhSachGroupSanPham()
    {
        return AppMyaurisGroupSanPham::getListGroupSanPham();
    }

    /**
     * @APPKHACHHANG
     * Mang hinh hoa don bao hanh, thong tin don hang, dental form
     * POST: phone
     */
    public function actionTrackDonHang()
    {
        $post = \Yii::$app->request->post();

        if (!isset($post['phone'])) {
            return [
                'code' => 400,
                'message' => "Not enough data. Unknown file for 'phone'",
            ];
        }
        $result = [];
        $mCustomer = CustomerModel::find()->where(['phone' => $post['phone']]);
        if (!$mCustomer->exists()) {
            return null;
        }
        $customer = $mCustomer->joinWith(['orderHasOne'])->one();
        // return "fasle";
        $order = $customer->orderHasOne;
        if (!$order) {
            return null;
        }
        $result['order_code'] = $order['order_code'];
        $result['tong_tien'] = $order['thanh_tien'] - $order['chiet_khau'];
        $result['lich_dieu_tri'] = $this->getDetailLichDieuTri($order->phongKhamLichDieuTriHasMany);
        $wOrder = $order->phongKhamDonHangWOrderHasMany;
        foreach ($wOrder as $mOrder) {
            $mPhongKhamSanPham = PhongKhamSanPham::getOneSanPham($mOrder['san_pham']);
            $result['wOrder'][] = [
                'san_pham' => $mPhongKhamSanPham->name,
                'so_luong' => $mOrder['so_luong'],
            ];
        }
        $result['dental-form'] = $customer->showImageGoogleDrive($customer->id, $customer->slug, DentalFormController::FOLDER);
        return $result;
    }

    public function getDetailLichDieuTri($aModel)
    {
        $result = [];
        if (is_array($aModel)) {
            foreach ($aModel as $mLichdieutri) {
                $ekipInfoHasOneName = ($mLichdieutri->ekipInfoHasOne !== null) ? $mLichdieutri->ekipInfoHasOne->fullname : "";
                $aLich = ArrayHelper::toArray($mLichdieutri);
                $aLich['ekip'] = $ekipInfoHasOneName;
                $aLich['tro_thu'] = $mLichdieutri->getThongTinTroThu();
                $aLich['room_id'] = ($mLichdieutri->roomHasOne !== null) ? $mLichdieutri->roomHasOne->fullname : "";
                $aLich['time_dieu_tri'] = date('d-m-Y H:i', $aLich['time_dieu_tri']);
                $result[] = $aLich;
            }
        }
        return $result;
    }

    // @APPKHACHHANG @BUG
    public function actionKhachHangDanhGia()
    {
        $post = \Yii::$app->request->post();
        if (!isset($post['phone'])) {
            return [
                'code' => 400,
                'message' => "Not enough data. Unknown file for 'phone'",
            ];
        }
        if (!isset($post['point'])) {
            return [
                'code' => 400,
                'message' => "Not enough data. Unknown 'point'",
            ];
        }
        $phone = $post['phone'];
        $point = $post['point'];

        return true;
    }

    /**
     * API lay ma OTP
     */
    public function actionGetOtp()
    {
        $mtSms = new MtSmsComponent([
            'data' => [
                'phone' => '0979883765',
            ]
        ]);
        $mtSms->setMsgContent("EM YEU ANH");
        return $mtSms->sendSms();
    }
}
