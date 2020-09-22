<?php

namespace backend\modules\labo\controllers;

use backend\components\MyComponent;
use backend\components\MyController;
use backend\modules\labo\models\LaboGiaiDoanImage;
use backend\modules\labo\models\search\SearchLaboGiaiDoanImage;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * LaboGiaiDoanImageController implements the CRUD actions for LaboGiaiDoanImage model.
 */
class LaboGiaiDoanImageController extends MyController
{
    public function actionIndex()
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
        }
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = LaboGiaiDoanImage::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCreate()
    {
        $model = new LaboGiaiDoanImage();

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            if ($model->validate()) {

                try {
                    $fileName = time() . '.' . $model->imageFile->extension;
                    if ($model->upload($fileName)) {
                        $model->image = $fileName;
                    }
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
                if(Yii::$app->request->post('another_page') == 1){
                    return $this->redirect(Yii::$app->request->referrer);
                }
                return $this->refresh();
            }
        }

//        if()
//        echo Yii::$app->request->post('another_page');


        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing LaboGiaiDoanImage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            if ($model->validate()) {

                try {
                    if (empty($model->image)) {
                        $fileName = time() . '.' . $model->imageFile->extension;
                    }else{
                        $fileName = $model->image;
                    }

                    if ($model->upload($fileName)) {
                        $model->image = $fileName;
                    }


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
                if ($model = $this->findModel($id)) {
                    if ($model->delete()) {
                        $model->deleteFile($model->image);
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
}
