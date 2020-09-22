<?php

namespace backend\modules\baocao\controllers;

use Yii;
use backend\modules\baocao\models\BaocaoLocation;
use backend\modules\baocao\models\search\BaocaoLocationSearch;
use backend\components\MyController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * BaocaoLocationController implements the CRUD actions for AbLocation model.
 */
class BaocaoLocationController extends MyController
{
    public function init()
    {
        parent::init();
        $cache = Yii::$app->cache;
        $key = 'redis-get-baocao-location';
        $cache->delete($key);
    }

    public function actionIndex()
    {
        $searchModel = new BaocaoLocationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $cookies = Yii::$app->request->cookies;
        if ($cookies->has('pageSize')) {
            $dataProvider->pagination->pageSize = $cookies['pageSize']->value;
        } else {
            $dataProvider->pagination->pageSize = 50;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPerpage($perpage)
    {
        $cookies = Yii::$app->response->cookies;
        if ($cookies->add(new \yii\web\Cookie([
            'name' => 'pageSize',
            'value' => $perpage,
        ]))) ;
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
        $model = new BaocaoLocation();
        if ($model->list_province == null) {
            $model->list_province = json_encode([]);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->list_province = json_encode($model->list_province);
            if ($model->validate()) {
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
        }
        $model->list_province = json_decode($model->list_province);

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AbLocation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->list_province == null) {
            $model->list_province = json_encode([]);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->list_province = json_encode($model->list_province);
            if ($model->validate()) {
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
        }
        $model->list_province = json_decode($model->list_province);

        return $this->render('update', [
            'model' => $model,
        ]);
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
        if (($model = BaocaoLocation::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
