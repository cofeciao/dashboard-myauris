<?php

namespace backend\modules\testab\controllers;

use Yii;
use backend\modules\testab\models\AbCampaign;
use backend\modules\testab\models\search\AbCampaignSearch;
use backend\components\MyController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CampaignController implements the CRUD actions for AbCampaign model.
 */
class CampaignController extends MyController
{
    public function actionIndex()
    {
        $searchModel = new AbCampaignSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $cookies = Yii::$app->request->cookies;
        if ($cookies->has('pageSize')) {
            $dataProvider->pagination->pageSize = $cookies['pageSize']->value;
        } else {
            $dataProvider->pagination->pageSize = 20;
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
        $model = new AbCampaign();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            try {
                if ($model->btn_form == 'end') {
                    $model->end_date = time();
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
            return $this->refresh();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AbCampaign model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->end_date != null) {
            Yii::$app->session->setFlash('alert', [
                'body' => 'Bạn không thể cập nhật chiến dịch này nữa.',
                'class' => 'bg-danger',
            ]);
            return $this->redirect(['index']);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            try {
                if ($model->btn_form == 'end') {
                    $model->end_date = time();
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
            return $this->redirect(['index']);
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
        if (($model = AbCampaign::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
