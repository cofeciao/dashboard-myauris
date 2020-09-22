<?php

namespace backend\modules\chi\controllers;

use backend\components\MyController;
use backend\modules\chi\models\DeXuatChi;
use backend\modules\chi\models\form\FormHoSo;
use backend\modules\chi\models\HoSo;
use backend\modules\user\models\UserSubRole;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\Response;

class HoSoController extends MyController
{
    public function actionIndex()
    {
        return $this->render('index', []);
    }

    public function actionCreate()
    {
        $model = new FormHoSo();
        return $this->render('create', [
            'model' => $model
        ]);
    }

    public function actionUpdate($id)
    {
        $model = new HoSo();
        return $this->render('create', [
            'model' => $model
        ]);
    }

    public function actionValidateTest()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $model = new FormHoSo();
            if ($model->load(\Yii::$app->request->post())) {
                return ActiveForm::validate($model);
            }
        }
    }

    public function actionSubmitTest()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $model = new FormHoSo();
            if (!$model->load(\Yii::$app->request->post()) || !$model->validate()) {
                return [
                    'code' => 400,
                    'msg' => 'Có lỗi xảy ra',
                    'fileError' => $model->getFilesError()
                ];
            }
            $listFiles = $model->saveFiles();
            return [
                'code' => 200,
                'msg' => 'OK',
                'data' => $listFiles,
                'files' => $model->files
            ];
        }
    }

    public function actionDelete()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $id = \Yii::$app->request->get('id');
            $id_de_xuat = \Yii::$app->request->get('id_de_xuat');
            $model = HoSo::find()->where(['id' => $id])->one();
            $de_xuat = DeXuatChi::find()->where(['id' => $id_de_xuat])->one();
            if ($model == null || $de_xuat == null) {
                return [
                    'code' => 404,
                    'msg' => 'Không tìm thấy hồ sơ'
                ];
            }
            if ($de_xuat->status != DeXuatChi::STATUS_DANG_DOI_DUYET && UserSubRole::is_current_user_is_teamlead() ) {
                return [
                    'code' => 400,
                    'msg' => 'Không thể chỉnh sửa hồ sơ'
                ];
            }

            if (\Yii::$app->user->id != $model->created_by && !UserSubRole::is_current_user_is_ketoan()) {
                return [
                    'code' => 400,
                    'msg' => 'Không có quyền chỉnh sửa hồ sơ'
                ];
            }
            $file = $model->file;
            if (!$model->delete()) {
                return [
                    'code' => 400,
                    'msg' => 'Xoá hồ sơ thất bại'
                ];
            }
            if ($file != null && file_exists(\Yii::getAlias('@backend/web') . '/uploads/ho-so/' . $file)) {
                @unlink(\Yii::getAlias('@backend/web') . '/uploads/ho-so/' . $file);
            }
            return [
                'code' => 200,
                'msg' => 'Xoá hồ sơ thành công'
            ];
        }
    }
}
