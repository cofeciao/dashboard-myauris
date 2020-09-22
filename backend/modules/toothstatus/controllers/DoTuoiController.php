<?php

namespace backend\modules\toothstatus\controllers;

use Yii;
use backend\modules\toothstatus\models\DoTuoi;
use backend\modules\toothstatus\models\search\DoTuoiSearch;
use backend\components\MyController;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\MyComponent;
use yii\web\UploadedFile;

/**
 * DoTuoiController implements the CRUD actions for DoTuoi model.
 */
class DoTuoiController extends MyController
{
    public function actionIndex()
    {
        $searchModel = new DoTuoiSearch();
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
        $model = new DoTuoi();

        if ($model->load(Yii::$app->request->post())) {
            $image = UploadedFile::getInstance($model, 'image');

            if ($image != null) {
                $fileName = $image->baseName . '.' . $image->extension;
                $image->saveAs(Yii::getAlias('@backend/web') . '/uploads/tmp/' . $fileName);

                $image = $this->createImage('@backend/web', Yii::getAlias('@backend/web') . '/uploads/tmp/' . $fileName, 150, 150, '/uploads/rang/do-tuoi/150x150/', null, true, true);

                if ($image != false) {
                    $model->image = $image;
                    $this->createImage('@backend/web', Yii::getAlias('@backend/web') . '/uploads/tmp/' . $fileName, 300, 300, '/uploads/rang/do-tuoi/300x300/', $image, true, true);
                    $this->createImage('@backend/web', Yii::getAlias('@backend/web') . '/uploads/tmp/' . $fileName, 600, 600, '/uploads/rang/do-tuoi/600x600/', $image, true, true);
                }
                $this->deleteImage(Yii::getAlias('@frontend/web'), '/uploads/tmp/', $fileName);
            }
            if ($model->validate() && $model->save()) {
                Yii::$app->session->setFlash('alert', [
                    'body' => Yii::$app->params['create-success'],
                    'class' => 'bg-success',
                ]);
            } else {
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
     * Updates an existing DoTuoi model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $image = UploadedFile::getInstance($model, 'image');
            $oldImage = $model->getOldAttribute('image');

            if ($image != null) {
                $fileName = $image->baseName . '.' . $image->extension;
                $image->saveAs(Yii::getAlias('@backend/web') . '/uploads/tmp/' . $fileName);

                $image = $this->createImage('@backend/web', Yii::getAlias('@backend/web') . '/uploads/tmp/' . $fileName, 150, 150, '/uploads/rang/do-tuoi/150x150/', null, true, true);

                if ($image != false) {
                    $model->image = $image;
                    $this->createImage('@backend/web', Yii::getAlias('@backend/web') . '/uploads/tmp/' . $fileName, 300, 300, '/uploads/rang/do-tuoi/300x300/', $image, true, true);
                    $this->createImage('@backend/web', Yii::getAlias('@backend/web') . '/uploads/tmp/' . $fileName, 600, 600, '/uploads/rang/do-tuoi/600x600/', $image, true, true);
                }
                if ($oldImage != null) {
                    $this->deleteImage(Yii::getAlias('@frontend/web'), '/uploads/rang/do-tuoi/150x150/', $oldImage);
                    $this->deleteImage(Yii::getAlias('@frontend/web'), '/uploads/rang/do-tuoi/300x300/', $oldImage);
                    $this->deleteImage(Yii::getAlias('@frontend/web'), '/uploads/rang/do-tuoi/600x600/', $oldImage);
                }
                $this->deleteImage(Yii::getAlias('@frontend/web'), '/uploads/tmp/', $fileName);
            } else {
                $model->image = $oldImage;
            }

            if ($model->validate() && $model->save()) {
                Yii::$app->session->setFlash('alert', [
                    'body' => Yii::$app->params['update-success'],
                    'class' => 'bg-success',
                ]);
            } else {
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

    protected function findModel($id)
    {
        if (($model = DoTuoi::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
