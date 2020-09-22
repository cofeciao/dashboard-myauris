<?php

namespace backend\modules\recommend\controllers;

use backend\components\MyComponent;
use backend\components\MyController;
use backend\modules\appmyauris\models\TableTemp;
use backend\modules\recommend\models\Recommend;
use backend\modules\recommend\models\SearchRecommend;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * RecommendController implements the CRUD actions for Recommend model.
 */
class RecommendController extends MyController
{
    public function actionIndex()
    {
        $searchModel = new SearchRecommend();
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
        if (($model = Recommend::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCreate()
    {
        $model = new Recommend();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
//            echo "<pre>";
//            print_r(Yii::$app->request->post());
//            echo "</pre>";
//            die;
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

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Recommend model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
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

    public function actionDemo()
    {
        $model = new Recommend();

        if ($model->load(Yii::$app->request->post()) ){ //&& $model->validate()) {

            $post = Yii::$app->request->post('Recommend');

            $dataProvider = $model->recommend($post);

            if (MyComponent::hasCookies('pageSize')) {
                $dataProvider->pagination->pageSize = MyComponent::getCookies('pageSize');
            } else {
                $dataProvider->pagination->pageSize = 10;
            }

            $pageSize = $dataProvider->pagination->pageSize;

            $totalCount = $dataProvider->totalCount;

            $totalPage = (($totalCount + $pageSize - 1) / $pageSize);


            return $this->render('demo/step2', [
                'dataProvider' => $dataProvider,
                'totalPage' => $totalPage,
            ]);

        }

        return $this->render('demo/step1', [
            'model' => $model,
        ]);
    }

    public function actionCopy($id)
    {
        try {
            $model = $this->findModel($id);
            $newModel = new Recommend();
            $newModel->setAttributes($model->attributes);
            $newModel->save();
            Yii::$app->session->setFlash('alert', [
                'body' => "Copy thành công",
                'class' => 'bg-success',
            ]);
            return $this->redirect(['index']);
        } catch (\yii\db\Exception $exception) {
            echo 0;
        }
    }
}
