<?php

namespace backend\modules\baocao\controllers;

use Yii;
use backend\modules\baocao\models\BaocaoChayAdsAdswords;
use backend\modules\baocao\models\search\BaocaoChayAdsAdswordsSearch;
use backend\components\MyController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\MyComponent;
use yii\web\Response;

/**
 * BaocaoChayAdwordsController implements the CRUD actions for BaocaoChayAdwords model.
 */
class BaocaoChayAdsAdswordsController extends MyController
{
    public function actionIndex()
    {
        $searchModel = new BaocaoChayAdsAdswordsSearch();

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
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'totalPage'    => $totalPage,
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
        $model = new BaocaoChayAdsAdswords();

        if ($model->load(Yii::$app->request->post())) {
            //$model->validate()
            Yii::$app->response->format = Response::FORMAT_JSON;
            try {
                $model->ngay_tao = strtotime($model->ngay_tao);

                if ($model->validate()) {
                    if ($model->save()) {
                        return [
                            'status' => 200,
                            'mess'   => Yii::$app->params['update-success'],
                            'error'  => $model->getErrors(),
                        ];
                    } else {
                        if (Yii::$app->user->id == 1) {
                            var_dump($model->getErrors());
                            die;
                        }

                        return [
                            'status' => 403,
                            'mess'   => Yii::$app->params['update-danger'],
                            'error'  => $model->getErrors(),
                        ];
                    }
                } else {
                    if (Yii::$app->user->id == 1) {
                        var_dump($model->getErrors());
                        die;
                    }
                    $error = '';
                    foreach ($model->getErrors() as $k => $v) {
                        $error .= $v[0] . '<br/>';
                    }

                    return [
                        'status' => 400,
                        'mess'   => 'Lỗi kiểm tra dữ liệu!',
                        'error'  => $error,
                    ];
                }
            } catch (\yii\db\Exception $exception) {
                return [
                    'status' => 400,
                    'mess'   => 'Lỗi kiểm tra dữ liệu!',
                    'error'  => $exception->getMessage(),
                ];
            }
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BaocaoChayAdwords model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $model->ngay_tao            = strtotime($model->ngay_tao);
                try {
                    if ($model->validate()) {
                        if ($model->save()) {
                            return [
                                'status' => 200,
                                'mess'   => Yii::$app->params['update-success'],
                                'error'  => $model->getErrors(),
                            ];
                        } else {
                            if (Yii::$app->user->id == 1) {
                                var_dump($model->getErrors());
                                die;
                            }

                            return [
                                'status' => 403,
                                'mess'   => Yii::$app->params['update-danger'],
                                'error'  => $model->getErrors(),
                            ];
                        }
                    } else {
                        if (Yii::$app->user->id == 1) {
                            var_dump($model->getErrors());
                            die;
                        }
                        $error = '';
                        foreach ($model->getErrors() as $k => $v) {
                            $error .= $v[0] . '<br/>';
                        }

                        return [
                            'status' => 400,
                            'mess'   => 'Lỗi kiểm tra dữ liệu!',
                            'error'  => $error,
                        ];
                    }
                } catch (\yii\db\Exception $exception) {
                    return [
                        'status' => 400,
                        'mess'   => 'Lỗi kiểm tra dữ liệu!',
                        'error'  => $exception->getMessage(),
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
            $id                         = Yii::$app->request->post('id');
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
            $action         = Yii::$app->request->post('action');
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
        if (($model = BaocaoChayAdsAdswords::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
