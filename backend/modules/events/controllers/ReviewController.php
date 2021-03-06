<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 11-May-19
 * Time: 10:54 AM
 */

namespace backend\modules\events\controllers;

use backend\components\WarningComponent;
use backend\models\CanhBao;
use backend\models\CustomerModel;
use backend\modules\clinic\models\CustomerDanhGia;
use Yii;
use backend\components\MyController;
use backend\modules\clinic\models\PhongKhamLichDieuTri;
use backend\modules\customer\models\Dep365CustomerOnline;
use yii\web\Response;

class ReviewController extends MyController
{
    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
    }

    public function actionThankYou()
    {
        $this->layout = '@backend/views/layouts/public';
        return $this->render('thankYou');
    }

    public function actionVideo()
    {
        $this->layout = '@backend/views/layouts/public';
        return $this->render('video');
    }

    public function actionDanhGia()
    {
        $this->layout = '@backend/views/layouts/public';
        $datas = PhongKhamLichDieuTri::find()
            ->where(['danh_gia' => 1])
            ->published()
            ->coso()
            ->one();

        if ($datas !== null) {
            $customer = Dep365CustomerOnline::findOne($datas->customer_id);
            $check = 0;
            $nameDanhGia = '';
            if ($datas->last_dieu_tri == 1 && $datas->danh_gia == 1 && (
                $datas->chuyen_mon == 0 ||
                    $datas->thai_do == 0 ||
                    $datas->tham_my == 0
            )) {
                if ($datas->thai_do == 0) {
                    $check = 1;
                    $nameDanhGia = 'Đánh giá thái độ';
                }

                if ($datas->chuyen_mon == 0) {
                    $check = 2;
                    $nameDanhGia = 'Đánh giá chuyên môn';
                }

                if ($datas->tham_my == 0) {
                    $check = 3;
                    $nameDanhGia = 'Đánh giá thẩm mỹ';
                }
            }

            if ($datas->danh_gia == 1) {
                $nameDanhGia = 'Đánh giá thái độ';
            }

            $nameCustomer = $customer->full_name == null ? $customer->name : $customer->full_name;
            return $this->render('danhGia', ['id' => $datas->id, 'nameCustomer' => $nameCustomer, 'check' => $check, 'nameDanhGia' => $nameDanhGia]);
        }

        return $this->redirect(['video']);
    }

    //Danh gia khach hang den auris
    public function actionDanhGiaType()
    {
        $this->layout = '@backend/views/layouts/public';
        $datas = CustomerDanhGia::find()
            ->where(['danh_gia' => 1])
            ->one();

        if ($datas !== null) {
            $customer = Dep365CustomerOnline::findOne($datas->customer_id);
            $check = 0;
            $nameDanhGia = '';

            if ($datas->danh_gia == 1) {
                $nameDanhGia = 'Đánh giá buổi thăm khám';
            }

            $nameCustomer = $customer->full_name == null ? $customer->name : $customer->full_name;
            return $this->render('khachDanhGia', ['id' => $datas->id, 'nameCustomer' => $nameCustomer, 'check' => $check, 'nameDanhGia' => $nameDanhGia]);
        }

        return $this->redirect(['video']);
    }

    public function actionListener()
    {
        if (Yii::$app->request->isGet) {
            header('Content-Type: text/event-stream');
            header("Cache-Control: no-cache");
            $id = 'null';
            $type = 'null';

            $coso = Yii::$app->user->identity->permission_coso == null ? 1 : Yii::$app->user->identity->permission_coso;
            $datas = PhongKhamLichDieuTri::find()
                ->where(['danh_gia' => 1])
                ->andWhere(['co_so' => $coso])
                ->one();

            if ($datas !== null) {
                $id = $datas->id;
                $type = 1;
            }

            $danhGia = CustomerDanhGia::find()->where(['danh_gia' => 1])->andWhere(['co_so' => $coso])->one();

            if ($danhGia !== null) {
                $id = $danhGia->id;
                $type = 2;
            }

            echo "data: " . json_encode(['id' => $id, 'type' => $type]) . "\n\n";
            echo "retry: 1000\n";
            echo "\n\n";

            ob_flush();
            flush();
            die;
        }
    }

    public function actionDanhGiaResult()
    {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');
            $point = Yii::$app->request->post('point');
            $check = Yii::$app->request->post('check');

            Yii::$app->response->format = Response::FORMAT_JSON;

            $data = PhongKhamLichDieuTri::find()->where(['id' => $id])->one();

            if ($data !== null) {
                if ($data->danh_gia == 1 && $data->last_dieu_tri == 0) {
                    $data->thai_do = $point;
                    $data->danh_gia = 2;
                }

                if ($data->last_dieu_tri == 1) {
                    if ($check == 1 && $data->thai_do == 0) {
                        $data->thai_do = $point;
                    }
                    if ($check == 2 && $data->chuyen_mon == 0) {
                        $data->chuyen_mon = $point;
                    }
                    if ($check == 3 && $data->tham_my == 0) {
                        $data->tham_my = $point;
                    }

                    if ($data->thai_do != 0 && $data->chuyen_mon != 0 && $data->tham_my != 0) {
                        $data->danh_gia = 2;
                        $data->last_dieu_tri = 2;
                    }
                }
                if ($data->save()) {
                    WarningComponent::warningRate($data->getPrimaryKey());

                    return ['status' => '200'];
                }
            }

            return ['status' => '403'];
        }
    }

    public function actionKhachDanhGiaResult()
    {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');
            $point = Yii::$app->request->post('point');
            $check = Yii::$app->request->post('check');

            Yii::$app->response->format = Response::FORMAT_JSON;

            $data = CustomerDanhGia::find()->where(['id' => $id])->one();

            if ($data !== null) {
                if ($data->danh_gia == 1) {
                    $data->danh_gia_thai_do = $point;
                    $data->danh_gia = 2;
                }

                if ($data->save()) {
                    WarningComponent::warningRate($data->getPrimaryKey());
                    
                    return ['status' => '200'];
                }
            }

            return ['status' => '403'];
        }
    }
}
