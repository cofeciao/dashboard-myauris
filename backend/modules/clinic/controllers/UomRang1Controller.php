<?php

namespace backend\modules\clinic\controllers;

use backend\modules\clinic\components\HinhCustomer;
use backend\modules\clinic\models\Clinic;
use backend\modules\clinic\models\CustomerImages;
use backend\modules\clinic\models\form\FormUomRang1;
use backend\modules\clinic\models\PhongKhamUomRang1;
use common\helpers\MyHelper;
use Yii;
use backend\components\GapiComponent;
use yii\db\Transaction;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\UploadedFile;

class UomRang1Controller extends HinhCustomer
{
    const FOLDER = 'uom_rang_1';

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        ini_set('memory_limit', '-1');
        set_time_limit(600);
    }

    public function actionUpload($id)
    {
        $customer = Clinic::find()->where(['id' => $id])->one();
        if ($customer === null) {
            return $this->redirect(Url::toRoute('index'));
        }
        if ($customer->full_name == null) {
            Yii::$app->session->setFlash('alert', [
                'body' => 'Vui lòng cập nhật Họ và Tên đầy đủ của khách hàng!',
                'class' => 'alert-warning'
            ]);
            return $this->redirect(Url::toRoute('index'));
        }

        $listFile = [];

        $model = new FormUomRang1();
        $model->id = $id;

        $checkLocalData = CustomerImages::getListFilesByCustomer($id, Yii::$app->params['chup-hinh-catagory'][self::FOLDER]);
        foreach ($checkLocalData as $chuphinh) {
            if (file_exists(Url::to('@backend/web') . '/uploads/customer/' . $customer->slug . '-' . $customer->id . '/' . self::FOLDER . '/' . $chuphinh->image)) {
                $listFile[] = [
                    'type' => 'local',
                    'id' => $chuphinh->id,
                    'name' => $chuphinh->image,
                    'webContentLink' => Url::to('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . self::FOLDER . '/' . $chuphinh->image,
                    'thumbnailLink' => Url::to('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . self::FOLDER . '/thumb/' . $chuphinh->image,
                ];
            }
        }
        if (count($listFile) <= 0) {
            $service = GapiComponent::getService();

            $time = strtotime(date('d-m-Y'));
            $checkGDriveFolder = PhongKhamUomRang1::find()->where(['between', 'created_at', $time, $time + 86399])->andWhere(['customer_id' => $id])->one();
            if ($checkGDriveFolder == null) {
                $gDriveFolder = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($customer->full_name) . '-' . $id, date('d-m-Y'), self::FOLDER);
                $checkGDriveFolder = new PhongKhamUomRang1();
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
            }

            $rowChupHinh = PhongKhamUomRang1::find()->where(['customer_id' => $id])->all();
            if ($rowChupHinh != null) {
                foreach ($rowChupHinh as $chuphinh) {
                    $list = GapiComponent::getListFile($service, $chuphinh->folder_id);
                    if ($list != null && count($list) > 0) {
                        $listFile = array_merge($listFile, $list);
                    }
                }
            }
        }

        return $this->render('upload', [
            'customer' => $customer,
            'model' => $model,
            'listFile' => $listFile,
        ]);
    }

    public function actionUploadImage($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $customer = Clinic::find()->where(['id' => $id])->one();
        if ($customer == null) {
            return [
                'code' => 400,
                'msg' => 'Không tìm thấy khách hàng!'
            ];
        }
        $service = GapiComponent::getService();
        $time = strtotime(date('d-m-Y'));
//        $checkGDriveFolder = PhongKhamUomRang1::find()->where(['between', 'created_at', $time, $time + 86399])->andWhere(['customer_id' => $id])->one();
//        if ($checkGDriveFolder == null) {
//            return [
//                'code' => 403,
//                'data' => [
//                    'msg' => 'Khởi tạo Google Drive thất bại!',
//                ]
//            ];
//        }
        $code = 200;
        $msg = '';
        $dataImage = [];
        if (Yii::$app->request->isAjax) {
            $model = new FormUomRang1();
            $transaction = Yii::$app->db->beginTransaction(
                Transaction::SERIALIZABLE
            );
            if ($model->load(Yii::$app->request->post())) {
                $file = UploadedFile::getInstances($model, 'fileImage');
                $model->fileImage = $file;
                if ($model->validate()) {
//                    $gDriveFolder = $checkGDriveFolder->folder_id;
                    $data = [];

                    $fileName = $file[0]->baseName . '.' . $file[0]->extension;
                    if ($file[0]->saveAs(Yii::getAlias('@backend/web') . '/uploads/tmp/' . $fileName)) {
                        $msg = 'Upload thành công!';
                        $urlFile = Yii::$app->basePath . '/web/uploads/tmp/' . $fileName;
//                        /* 21-02-2020: Đóng code lưu hình trên server vì server đầy */
                        $image = $this->createImage('@backend/web', $urlFile, 220, 220, '/uploads/customer/' . $customer->slug . '-' . $customer->id . '/' . self::FOLDER . '/thumb/');
                        $this->createImage('@backend/web', $urlFile, null, null, '/uploads/customer/' . $customer->slug . '-' . $customer->id . '/' . self::FOLDER . '/', $image);

//                        $idImage = GapiComponent::uploadImageToDrive($service, $fileName, '@backend/web/uploads/tmp', $gDriveFolder);
//                        if ($idImage == null) {
//                            $code = 403;
//                            $msg = "Lưu hình lên drive thất bại!";
//                        } else {
                            $this->deleteImage('@backend/web', '/uploads/tmp/', $fileName);

                            $customerImage = new CustomerImages();
                            $customerImage->customer_id = $customer->id;
                            $customerImage->catagory_id = Yii::$app->params['chup-hinh-catagory'][self::FOLDER];
//                            $customerImage->image = $fileName;
//                            /* 21-02-2020: lưu image là url file tmp */
                            $customerImage->image = $image;
//                            $customerImage->google_id = $idImage;
                            if (!$customerImage->save()) {
                                $code = 400;
                                $msg = "Lưu hình thất bại!";
                                $data = $customerImage->getErrors();
                                $transaction->rollBack();
                            } else {
                                $transaction->commit();
//                                $file = GapiComponent::getFile($service, $idImage);
                                $data = [
                                    'type' => 'local',
                                    'id' => $customerImage->getPrimaryKey(),
                                    'title' => $fileName,
                                    'image' => Yii::getAlias('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . self::FOLDER . '/' . $image,
                                    'thumb' => Yii::getAlias('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . self::FOLDER . '/thumb/' . $image,
//                                    'image' => $file['webContentLink'],
//                                    'thumb' => $file['thumbnailLink'],
                                ];
                            }
//                        }
                    }
                    $dataImage = $data;
                } else {
                    $code = 400;
                    $msg = $model->getErrors('fileImage');
                }
            } else {
                $code = 400;
                $msg = 'Lỗi xử lý dữ liệu!';
            }
        } else {
            $code = 400;
            $msg = 'Yêu cầu không hợp lệ!';
        }
        return [
            'code' => $code,
            'data' => [
                'msg' => $msg,
                'dataImage' => $dataImage,
            ],
        ];
    }

    public function actionReload()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $code = 200;
        $msg = '';
        $data = [];
        $id = Yii::$app->request->post('id');
        $service = GapiComponent::getService();
        $file = GapiComponent::getFile($service, $id);
        if ($file == null) {
            $code = 403;
            $msg = "Có lỗi khi làm mới!";
        } else {
            $data = [
                'id' => $file['id'],
                'title' => $file['name'],
                'image' => $file['webContentLink'],
                'thumb' => $file['thumbnailLink'],
            ];
        }
        return [
            'code' => $code,
            'data' => [
                'msg' => $msg,
                'dataImage' => $data
            ]
        ];
    }
}
