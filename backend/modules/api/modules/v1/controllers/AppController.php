<?php

namespace backend\modules\api\modules\v1\controllers;

use backend\components\GapiComponent;
use backend\modules\clinic\controllers\ChupBanhMoiController;
use backend\modules\clinic\controllers\ChupCuiController;
use backend\modules\clinic\controllers\ChupFinalController;
use backend\modules\clinic\controllers\ChupHinhController;
use backend\modules\clinic\controllers\TkncController;
use backend\modules\clinic\models\Clinic;
use backend\modules\clinic\models\CustomerImages;
use backend\modules\clinic\models\form\FormChupHinh;
use backend\modules\clinic\models\PhongKhamChupBanhMoi;
use backend\modules\clinic\models\PhongKhamChupCui;
use backend\modules\clinic\models\PhongKhamChupFinal;
use backend\modules\clinic\models\PhongKhamChupHinh;
use backend\modules\clinic\models\PhongKhamHinhTknc;
use common\commands\DeleteImageCommand;
use common\commands\ImageCommand;
use common\helpers\MyHelper;
use Yii;
use backend\modules\api\components\RestController;

use yii\db\Transaction;
use yii\helpers\Url;
use yii\web\UploadedFile;

class AppController extends RestController
{
    public $modelClass = 'backend\modules\api\modules\v1\models\UserApi';


    public function actionGet_list_reason_cancel()
    {
        return Yii::$app->params["ly-do-khong-lam"];
    }

    public function actionUpload_image()
    {
        $post = \Yii::$app->request->post();

        if (!isset($_FILES["fileImage"])) {
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

        if ($aCategoryGetValue[$catagory_id] == "chup-hinh") {
            $mUploadImage = new PhongKhamChupHinh();
            return $this->handleUploadImageAPI($service, $time, $id, $customer, $mUploadImage, ChupHinhController::FOLDER);
        }

        if ($aCategoryGetValue[$catagory_id] == "chup-banh-moi") {
            $mUploadImage = new PhongKhamChupBanhMoi();
            return $this->handleUploadImageAPI($service, $time, $id, $customer, $mUploadImage, ChupBanhMoiController::FOLDER);
        }

        if ($aCategoryGetValue[$catagory_id] == "chup-cui") {
            $mUploadImage = new PhongKhamChupCui();
            return $this->handleUploadImageAPI($service, $time, $id, $customer, $mUploadImage, ChupCuiController::FOLDER);
        }

        if ($aCategoryGetValue[$catagory_id] == "chup-ket-thuc") {
            $mUploadImage = new PhongKhamChupFinal();
            return $this->handleUploadImageAPI($service, $time, $id, $customer, $mUploadImage, ChupFinalController::FOLDER);
        }

        if ($aCategoryGetValue[$catagory_id] == "thiet-ke-nu-cuoi") {
            $mUploadImage = new PhongKhamHinhTknc();
            return $this->handleUploadImageAPI($service, $time, $id, $customer, $mUploadImage, TkncController::FOLDER);
        }
    }

    // $customer = customer model
    // $id = $customer_id
    // $mUploadImage  == PhongKhamChupHinh
    // $FolderUploadImage  ==   ChupHinhController::FOLDER
    protected function handleUploadImageAPI($service, $time, $id, $customer, $mUploadImage, $FolderUploadImage)
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
        $postImage = [
            "FormChupHinh" => [
                "id" => $id,
                "fileImage" => $_FILES
            ],
        ];
        $model->load($postImage);
        if ($model->load($postImage)) {
            $gDriveFolder = $checkGDriveFolder->folder_id;
            $file = UploadedFile::getInstancesByName("fileImage");
            $model->fileImage = $file;
            if ($model->validate()) {
                $fileName = $file[0]->baseName . '.' . $file[0]->extension;
                if ($file[0]->saveAs(Yii::getAlias('@backend/web') . '/uploads/tmp/' . $fileName)) {
                    $urlFile = Yii::$app->basePath . '/web/uploads/tmp/' . $fileName;
                    $image = $this->createImageApi('@backend/web', $urlFile, 220, 220, '/uploads/customer/' . $customer->slug . '-' . $customer->id . '/' . $FolderUploadImage . '/thumb/');
                    $this->createImageApi('@backend/web', $urlFile, null, null, '/uploads/customer/' . $customer->slug . '-' . $customer->id . '/' . $FolderUploadImage . '/', $image);
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
                        if (!$customerImage->save()) {
                            $data = $customerImage->getErrors();
                            $transaction->rollBack();
                            return [
                                'code' => 400,
                                'data' => $data,
                                'message' =>  "Save failed",
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
        } else {
            $transaction->rollBack();
            return [
                'code' => 400,
                'message' => 'Wrong load model',
            ];
        }
    }

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

        $this->deleteImageApi('@backend/web', '/uploads/customer/'. $customer->slug . '-' . $customer->id . '/' . $FolderUploadImage . '/thumb/', $fileName);
        $this->deleteImageApi('@backend/web', '/uploads/customer/'. $customer->slug . '-' . $customer->id . '/' . $FolderUploadImage . '/', $fileName);
        $service = GapiComponent::getService();
        GapiComponent::deleteImageToDrive($service, $google_id); // Delete Image Drive
        $mCustomerImage->delete();

        return [
            'code' => 200,
            'message' => "Done. Delete image success."
        ];
    }
    // only for test
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
}
