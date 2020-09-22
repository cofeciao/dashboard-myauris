<?php

namespace backend\modules\clinic\controllers;

use backend\components\MyComponent;
use backend\components\MyController;
use backend\modules\clinic\models\PhongKhamKpi;
use backend\modules\clinic\models\search\PhongKhamKpiSearch;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * PhongKhamKpiController implements the CRUD actions for PhongKhamKpi model.
 */
class PhongKhamKpiController extends MyController
{
    public function actionIndex()
    {
        $searchModel = new PhongKhamKpiSearch();
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

    public function actionCreate()
    {
        if (Yii::$app->request->isAjax) {
            $model = new PhongKhamKpi();

            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing PhongKhamKpi model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = $this->findModel($id);

            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }

    }

    public function actionValidatePhongKhamKpi($id = null)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = new PhongKhamKpi();
            if ($id != null) {
                $model = PhongKhamKpi::find()->where(['id' => $id])->one();
            }
            if ($model->load(Yii::$app->request->post())) {
                return ActiveForm::validate($model);
            }
        }
    }

    public function actionSubmitPhongKhamKpi($id = null)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

//            $model = PhongKhamKpi::find()->where(['BETWEEN', 'created_at', strtotime(date('01-m-Y')), strtotime(date('t-m-Y', strtotime(date('01-m-Y')))) + 86399])->one();

//            if ($model == null) {
                $model = new PhongKhamKpi();
//            }

            if ($id != null) {
                $model = PhongKhamKpi::find()->where(['id' => $id])->one();
            }

            if ($model == null) {
                return [
                    'code' => 404,
                    'msg' => 'Không tìm thấy dữ liệu'
                ];
            }
            if (!$model->load(Yii::$app->request->post()) || !$model->validate() || !$model->save()) {
                return [
                    'code' => 400,
                    'msg' => $id == null ? Yii::$app->params['create-danger'] : Yii::$app->params['update-danger'],
                    'error' => $model->getErrors()
                ];
            }

//            var_dump($model); die;
            return [
                'code' => 200,
                'msg' => $id == null ? Yii::$app->params['create-success'] : Yii::$app->params['update-success']
            ];
        }
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
        if (($model = PhongKhamKpi::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
    }
}
