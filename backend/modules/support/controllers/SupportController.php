<?php

namespace backend\modules\support\controllers;

use Yii;
use backend\modules\support\models\Support;
use backend\modules\support\models\search\SupportSearch;
use backend\components\MyController;
use yii\db\Exception;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\MyComponent;
use yii\web\Response;

/**
 * SupportController implements the CRUD actions for Support model.
 */
class SupportController extends MyController
{
    public function actionIndex()
    {
        $searchModel = new SupportSearch();
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
        if (Yii::$app->request->isAjax && $this->findModel($id)) {
            return $this->renderAjax('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    public function actionCreate()
    {
        if (Yii::$app->request->isAjax) {
            $model = new Support();

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                try {
                    $model->save();
                    return [
                        'status' => 200,
                        'mess' => Yii::$app->params['create-success'],
                    ];
                } catch (\yii\db\Exception $exception) {
                    return [
                        'status' => 400,
                        'mess' => Yii::$app->params['create-danger'],
                        'error' => $exception->getMessage(),
                    ];
                }
            }

            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Support model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                try {
                    $model->save();
                    return [
                        'status' => 200,
                        'mess' => Yii::$app->params['update-success'],
                    ];
                } catch (\yii\db\Exception $exception) {
                    return [
                        'status' => 400,
                        'mess' => Yii::$app->params['update-danger'],
                        'error' => $exception->getMessage(),
                    ];
                }
            }

            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }
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

    public function actionUpdateView($id = null, $user = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $support = Support::find()->where(['id' => $id])->one();
        if ($support == null || $user == null) {
            return [
            'code' => 404
        ];
        }
        if (!is_array($support->users_view)) {
            $support->users_view = [];
        }
        if (in_array($user, $support->users_view)) {
            return [
            'code' => 200
        ];
        }
        $users_view = $support->users_view;
        $users_view[] = $user;
        try {
            $support->updateAttributes([
                'users_view' => $users_view
            ]);
            return [
                'code' => 200
            ];
        } catch (Exception $ex) {
            return [
                'code' => 400
            ];
        }
    }

    protected function findModel($id)
    {
        if (($model = Support::find()->where(['support.id' => $id])->joinWith(['supportCatagoryHasOne'])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
