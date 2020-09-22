<?php

namespace backend\modules\toothstatus\controllers;

use Yii;
use backend\modules\toothstatus\models\DichVu;
use backend\modules\toothstatus\models\search\DichVuSearch;
use backend\components\MyController;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use backend\components\MyComponent;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

/**
 * DichVuController implements the CRUD actions for DichVu model.
 */
class DichVuController extends MyController
{
    public function actionIndex()
    {
        $searchModel = new DichVuSearch();
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
        if ($this->findModel($id)) {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        } else {
            return $this->redirect(['index']);
        }
    }

    public function actionCreate()
    {
        $model = new DichVu();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->price != null) {
                $model->price = str_replace('.', '', $model->price);
            }

            foreach ([0, 1, 2] as $i) {
                $before = 'image_' . $i . 'b';
                $after = 'image_' . $i . 'a';
                $$before = UploadedFile::getInstance($model, $before);
                $$after = UploadedFile::getInstance($model, $after);
                $model->$before = $$before;
                $model->$after = $$after;
            }
            if ($model->validate()) {
                $list_images = [];
                foreach ([0, 1, 2] as $i) {
                    $before = 'image_' . $i . 'b';
                    $after = 'image_' . $i . 'a';
                    $image_before = null;
                    $image_after = null;
                    if ($$before != null) {
                        $img = $$before->baseName . '.' . $$before->extension;
                        if ($$before->saveAs('uploads/tmp/' . $img)) {
                            $urlImage = Yii::$app->basePath . '/web/uploads/tmp/' . $img;
                            $image_before = $this->createImage('@backend/web', $urlImage, null, null, '/uploads/rang/dich-vu/', null, true, true);
                            $this->createImage('@backend/web', $urlImage, 150, 150, '/uploads/rang/dich-vu/150x150/', $image_before, true, true);
                            $this->createImage('@backend/web', $urlImage, 300, 300, '/uploads/rang/dich-vu/300x300/', $image_before, true, true);
                            $this->createImage('@backend/web', $urlImage, 600, 600, '/uploads/rang/dich-vu/600x600/', $image_before, true, true);
                            $this->deleteImage('@backend/web', '/uploads/tmp/', $img);
                            $model->$before = null;
                        }
                    }
                    if ($$after != null) {
                        $img = $$after->baseName . '.' . $$after->extension;
                        if ($$after->saveAs('uploads/tmp/' . $img)) {
                            $urlImage = Yii::$app->basePath . '/web/uploads/tmp/' . $img;
                            $image_after = $this->createImage('@backend/web', $urlImage, null, null, '/uploads/rang/dich-vu/', null, true, true);
                            $this->createImage('@backend/web', $urlImage, 150, 150, '/uploads/rang/dich-vu/150x150/', $image_after, true, true);
                            $this->createImage('@backend/web', $urlImage, 300, 300, '/uploads/rang/dich-vu/300x300/', $image_after, true, true);
                            $this->createImage('@backend/web', $urlImage, 600, 600, '/uploads/rang/dich-vu/600x600/', $image_after, true, true);
                            $this->deleteImage('@backend/web', '/uploads/tmp/', $img);
                            $model->$after = null;
                        }
                    }
                    $list_images[$i]['before'] = $image_before;
                    $list_images[$i]['after'] = $image_after;
                }
                $model->customer_image = json_encode($list_images, true);
                try {
                    if (!$model->save()) {
                        if (is_array($model->customer_image)) {
                            $customer_image = json_decode($model->customer_image, true);
                            foreach ($customer_image as $k => $image) {
                                if (isset($image['before']) && !in_array($image['before'], [null, ''])) {
                                    $this->deleteImage('@backend/web', '/uploads/rang/dich-vu/', $image['before']);
                                    $this->deleteImage('@backend/web', '/uploads/rang/dich-vu/150x150/', $image['before']);
                                    $this->deleteImage('@backend/web', '/uploads/rang/dich-vu/300x300/', $image['before']);
                                    $this->deleteImage('@backend/web', '/uploads/rang/dich-vu/600x600/', $image['before']);
                                }
                                if (isset($image['after']) && !in_array($image['after'], [null, ''])) {
                                    $this->deleteImage('@backend/web', '/uploads/rang/dich-vu/', $image['after']);
                                    $this->deleteImage('@backend/web', '/uploads/rang/dich-vu/150x150/', $image['after']);
                                    $this->deleteImage('@backend/web', '/uploads/rang/dich-vu/300x300/', $image['after']);
                                    $this->deleteImage('@backend/web', '/uploads/rang/dich-vu/600x600/', $image['after']);
                                }
                            }
                        }
                        Yii::$app->session->setFlash('alert', [
                            'body' => Yii::$app->params['create-danger'],
                            'class' => 'bg-danger',
                        ]);
                    } else {
                        Yii::$app->session->setFlash('alert', [
                            'body' => Yii::$app->params['create-success'],
                            'class' => 'bg-success',
                        ]);
                    }
                } catch (\yii\db\Exception $exception) {
                    Yii::$app->session->setFlash('alert', [
                        'body' => Yii::$app->params['create-danger'],
                        'class' => 'bg-danger',
                    ]);
                }
            } else {
                $msg = '';
                foreach ($model->getErrors() as $error) {
                    $msg .= $error[0] . '<br/>';
                }
                Yii::$app->session->setFlash('alert', [
                    'body' => Yii::$app->params['create-danger'],
                    'class' => 'bg-danger',
                ]);
            }
            return $this->refresh();
        } else {
            $model->load(Yii::$app->request->get());
        }
        if (is_array($model->tinh_trang_rang)) {
            $model->tinh_trang_rang = ArrayHelper::map($model->tinh_trang_rang, 'name', 'name');
        }
        if (is_array($model->do_tuoi)) {
            $model->do_tuoi = ArrayHelper::map($model->do_tuoi, 'name', 'name');
        }
        if (is_array($model->lua_chon)) {
            $model->lua_chon = ArrayHelper::map($model->lua_chon, 'name', 'name');
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing DichVu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->price != null) {
                $model->price = str_replace('.', '', $model->price);
            }

            foreach ([0, 1, 2] as $i) {
                $before = 'image_' . $i . 'b';
                $after = 'image_' . $i . 'a';
                $$before = UploadedFile::getInstance($model, $before);
                $$after = UploadedFile::getInstance($model, $after);
                $model->$before = $$before;
                $model->$after = $$after;
            }
            if ($model->validate()) {
                $list_images = [];
                $list_images_old = json_decode($model->customer_image, true);
                foreach ([0, 1, 2] as $i) {
                    $before = 'image_' . $i . 'b';
                    $after = 'image_' . $i . 'a';
                    $image_before = null;
                    $image_after = null;
                    if ($$before != null) {
                        $img = $$before->baseName . '.' . $$before->extension;
                        if ($$before->saveAs('uploads/tmp/' . $img)) {
                            $urlImage = Yii::$app->basePath . '/web/uploads/tmp/' . $img;
                            $image_before = $this->createImage('@backend/web', $urlImage, null, null, '/uploads/rang/dich-vu/', null, true, true);
                            $this->createImage('@backend/web', $urlImage, 150, 150, '/uploads/rang/dich-vu/150x150/', $image_before, true, true);
                            $this->createImage('@backend/web', $urlImage, 300, 300, '/uploads/rang/dich-vu/300x300/', $image_before, true, true);
                            $this->createImage('@backend/web', $urlImage, 600, 600, '/uploads/rang/dich-vu/600x600/', $image_before, true, true);
                            $this->deleteImage('@backend/web', '/uploads/tmp/', $img);
                            $model->$before = null;
                        }
                    } elseif (isset($list_images_old[$i]['before']) && $list_images_old[$i]['before'] != null) {
                        $image_before = $list_images_old[$i]['before'];
                        unset($list_images_old[$i]['before']);
                    }
                    if ($$after != null) {
                        $img = $$after->baseName . '.' . $$after->extension;
                        if ($$after->saveAs('uploads/tmp/' . $img)) {
                            $urlImage = Yii::$app->basePath . '/web/uploads/tmp/' . $img;
                            $image_after = $this->createImage('@backend/web', $urlImage, null, null, '/uploads/rang/dich-vu/', null, true, true);
                            $this->createImage('@backend/web', $urlImage, 150, 150, '/uploads/rang/dich-vu/150x150/', $image_after, true, true);
                            $this->createImage('@backend/web', $urlImage, 300, 300, '/uploads/rang/dich-vu/300x300/', $image_after, true, true);
                            $this->createImage('@backend/web', $urlImage, 600, 600, '/uploads/rang/dich-vu/600x600/', $image_after, true, true);
                            $this->deleteImage('@backend/web', '/uploads/tmp/', $img);
                            $model->$after = null;
                        }
                    } elseif (isset($list_images_old[$i]['after']) && $list_images_old[$i]['after'] != null) {
                        $image_after = $list_images_old[$i]['after'];
                        unset($list_images_old[$i]['after']);
                    }
                    $list_images[$i]['before'] = $image_before;
                    $list_images[$i]['after'] = $image_after;
                }
                $model->customer_image = json_encode($list_images, true);
                try {
                    $model->save();
                    if (is_array($list_images_old)) {
                        foreach ($list_images_old as $k => $image_old) {
                            if (isset($image_old['before']) && !in_array($image_old['before'], [null, ''])) {
                                $this->deleteImage('@backend/web', '/uploads/rang/dich-vu/', $image_old['before']);
                                $this->deleteImage('@backend/web', '/uploads/rang/dich-vu/150x150/', $image_old['before']);
                                $this->deleteImage('@backend/web', '/uploads/rang/dich-vu/300x300/', $image_old['before']);
                                $this->deleteImage('@backend/web', '/uploads/rang/dich-vu/600x600/', $image_old['before']);
                            }
                            if (isset($image_old['after']) && !in_array($image_old['after'], [null, ''])) {
                                $this->deleteImage('@backend/web', '/uploads/rang/dich-vu/', $image_old['after']);
                                $this->deleteImage('@backend/web', '/uploads/rang/dich-vu/150x150/', $image_old['after']);
                                $this->deleteImage('@backend/web', '/uploads/rang/dich-vu/300x300/', $image_old['after']);
                                $this->deleteImage('@backend/web', '/uploads/rang/dich-vu/600x600/', $image_old['after']);
                            }
                        }
                    }
                    Yii::$app->session->setFlash('alert', [
                        'body' => Yii::$app->params['update-success'],
                        'class' => 'bg-success',
                    ]);
                } catch (\yii\db\Exception $exception) {
                    if (is_array($model->customer_image)) {
                        $customer_image = json_decode($model->customer_image, true);
                        foreach ($customer_image as $k => $image) {
                            if (isset($image['before']) && !in_array($image['before'], [null, ''])) {
                                $this->deleteImage('@backend/web', '/uploads/rang/dich-vu/', $image['before']);
                                $this->deleteImage('@backend/web', '/uploads/rang/dich-vu/150x150/', $image['before']);
                                $this->deleteImage('@backend/web', '/uploads/rang/dich-vu/300x300/', $image['before']);
                                $this->deleteImage('@backend/web', '/uploads/rang/dich-vu/600x600/', $image['before']);
                            }
                            if (isset($image['after']) && !in_array($image['after'], [null, ''])) {
                                $this->deleteImage('@backend/web', '/uploads/rang/dich-vu/', $image['after']);
                                $this->deleteImage('@backend/web', '/uploads/rang/dich-vu/150x150/', $image['after']);
                                $this->deleteImage('@backend/web', '/uploads/rang/dich-vu/300x300/', $image['after']);
                                $this->deleteImage('@backend/web', '/uploads/rang/dich-vu/600x600/', $image['after']);
                            }
                        }
                    }
                    Yii::$app->session->setFlash('alert', [
                        'body' => $exception->getMessage(),
                        'class' => 'bg-danger',
                    ]);
                }
            } else {
                Yii::$app->session->setFlash('alert', [
                    'body' => Yii::$app->params['update-danger'],
                    'class' => 'bg-danger',
                ]);
            }
            return $this->refresh();
        } else {
            $model->load(Yii::$app->request->get());
            $model->tinh_trang_rang = $model->tinhTrangRangHasMany;
            $model->do_tuoi = $model->doTuoiHasMany;
            $model->lua_chon = $model->luaChonHasMany;
        }
        $model->tinh_trang_rang = ArrayHelper::map($model->tinh_trang_rang, 'name', 'name');
        $model->do_tuoi = ArrayHelper::map($model->do_tuoi, 'name', 'name');
        $model->lua_chon = ArrayHelper::map($model->lua_chon, 'name', 'name');

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
                $model = $this->findModel($id);
                $list_images = json_decode($model->customer_image, true);
                if ($model->delete()) {
                    if (is_array($list_images)) {
                        foreach ($list_images as $k => $image) {
                            if ($image != null) {
                                $this->deleteImage('@backend/web', '/uploads/rang/dich-vu/', $image);
                                $this->deleteImage('@backend/web', '/uploads/rang/dich-vu/150x150/', $image);
                                $this->deleteImage('@backend/web', '/uploads/rang/dich-vu/300x300/', $image);
                                $this->deleteImage('@backend/web', '/uploads/rang/dich-vu/600x600/', $image);
                            }
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

    public function actionDeleteImage($id = null)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = DichVu::find()->where(['id' => $id])->one();
            $img = Yii::$app->request->post('img');
            $key = Yii::$app->request->post('key');
            $tmp = explode('-', $key);
            $customer_image = json_decode($model->customer_image, true);
            /*return [
                'status' => 'failure',
                'data' => $customer_image,
                'tmp' => $tmp,
                'key' => $key,
                'img' => $img,
                'check' => $model == null || in_array($key, [null, '']) ||
                    !is_array($tmp) || count($tmp) <= 0 ||
                    !array_key_exists($tmp[1], $customer_image) || !array_key_exists($tmp[0], $customer_image[$tmp[1]]) ||
                    $customer_image[$tmp[1]][$tmp[0]] != $img,
                'abc' => $customer_image[$tmp[1]][$tmp[0]]
            ];*/
            if ($model == null || in_array($key, [null, '']) ||
                !is_array($tmp) || count($tmp) <= 0 ||
                !array_key_exists($tmp[1], $customer_image) || !array_key_exists($tmp[0], $customer_image[$tmp[1]]) ||
                $customer_image[$tmp[1]][$tmp[0]] != $img
            ) {
                return [
                'status' => 'failure'
            ];
            }
            $img = $customer_image[$tmp[1]][$tmp[0]];
            $customer_image[$tmp[1]][$tmp[0]] = '';
            try {
                $model->updateAttributes([
                    'customer_image' => json_encode($customer_image, true)
                ]);
                $this->deleteImage('@backend/web', '/uploads/rang/dich-vu/', $img);
                $this->deleteImage('@backend/web', '/uploads/rang/dich-vu/150x150/', $img);
                $this->deleteImage('@backend/web', '/uploads/rang/dich-vu/300x300/', $img);
                $this->deleteImage('@backend/web', '/uploads/rang/dich-vu/600x600/', $img);
                return [
                    'status' => 'success'
                ];
            } catch (\yii\db\Exception $ex) {
                return [
                    'status' => 'failure'
                ];
            }
        }
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
//                        $this->findModel($id)->delete();
                        $model = $this->findModel($id);
                        $list_images = json_decode($model->customer_image);
                        if ($model->delete()) {
                            if (is_array($list_images)) {
                                foreach ($list_images as $k => $image) {
                                    if ($image != null) {
                                        $this->deleteImage('@backend/web', '/uploads/rang/', $image);
                                        $this->deleteImage('@backend/web', '/uploads/rang/150x150/', $image);
                                        $this->deleteImage('@backend/web', '/uploads/rang/300x300/', $image);
                                        $this->deleteImage('@backend/web', '/uploads/rang/600x600/', $image);
                                    }
                                }
                            }
                        }
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
        if (($model = DichVu::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
