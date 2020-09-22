<?php

namespace backend\modules\clinic\controllers;

use backend\components\MyComponent;
use backend\modules\clinic\models\PhongKhamDichVu;
use Yii;
use backend\modules\clinic\models\PhongKhamSanPham;
use backend\modules\clinic\models\search\PhongKhamSanPhamSearch;
use backend\components\MyController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ClinicSanPhamController implements the CRUD actions for PhongKhamSanPham model.
 */
class ClinicSanPhamController extends MyController
{
    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $cache = Yii::$app->cache;
        $key = 'redis-get-san-pham';
        $cache->delete($key);
    }

    public function actionIndex()
    {
        $searchModel = new PhongKhamSanPhamSearch();
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
        if (Yii::$app->request->isAjax) {
            $model = new PhongKhamSanPham();

            if ($model->load(Yii::$app->request->post())) {
                $model->don_gia = str_replace('.', '', $model->don_gia);
//                $model->services_id = json_encode($model->services_id);

                if ($model->validate()) {
                    Yii::$app->response->format = Response::FORMAT_JSON;

                    try {
                        if ($model->save()) {
                            return [
                                'status' => 200,
                                'mess' => Yii::$app->params['create-success'],
                                'error' => $model->getErrors(),
                            ];
                        } else {
                            return [
                                'status' => 403,
                                'mess' => Yii::$app->params['create-danger'],
                                'error' => $model->getErrors(),
                            ];
                        }
                    } catch (\yii\db\Exception $exception) {
                        return [
                            'status' => 400,
                            'mess' => Yii::$app->params['create-danger'],
                            'error' => $exception->getMessage(),
                        ];
                    }
                }
            }

            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing PhongKhamSanPham model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = $this->findModel($id);
//            $model->services_id = json_decode($model->services_id);

            if ($model->load(Yii::$app->request->post())) {
                $model->don_gia = str_replace('.', '', $model->don_gia);
//                $model->services_id = json_encode($model->services_id);

                if ($model->validate()) {
                    Yii::$app->response->format = Response::FORMAT_JSON;

                    try {
                        if ($model->save()) {
                            return [
                                'status' => 200,
                                'mess' => Yii::$app->params['update-success'],
                                'error' => $model->getErrors(),
                            ];
                        } else {
                            return [
                                'status' => 403,
                                'mess' => Yii::$app->params['update-danger'],
                                'error' => $model->getErrors(),
                            ];
                        }
                    } catch (\yii\db\Exception $exception) {
                        return [
                            'status' => 400,
                            'mess' => Yii::$app->params['update-danger'],
                            'error' => $exception->getMessage(),
                        ];
                    }
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

    protected function findModel($id)
    {
        if (($model = PhongKhamSanPham::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDanhSachSanPham($dich_vu = null)
    {
        Yii::$app->response->format = Response::FORMAT_HTML;
        $products = PhongKhamSanPham::getArraySanPhamByDichVu($dich_vu);
//        $service = PhongKhamDichVu::find()->where(['id' => $dich_vu])->one();
//        if ($service == null) return '';
//        $products = PhongKhamSanPham::find()->where(['LIKE', 'services_id', '"' . $service->primaryKey . '"'])->published()->all();
        if (!is_array($products) || count($products) <= 0) {
            return '';
        }
        $return = '';
        foreach ($products as $product) {
            $return .= '<option value="' . $product->primaryKey . '">' . $product->name . '</option>';
        }
        return $return;
    }
}