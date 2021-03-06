<?php

namespace backend\modules\clinic\controllers;

use backend\components\MyComponent;
use backend\modules\clinic\models\Clinic;
use backend\modules\clinic\models\PhongKhamDonHang;
use backend\modules\clinic\models\PhongKhamLichDieuTriTree;
use Yii;
use backend\modules\clinic\models\PhongKhamLichDieuTri;
use backend\modules\clinic\models\search\PhongKhamLichDieuTriSearch;
use backend\components\MyController;
use yii\db\Transaction;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ClinicDieuTriController implements the CRUD actions for PhongKhamLichDieuTri model.
 */
class ClinicDieuTriController extends MyController
{
    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $cache = Yii::$app->cache;
        $key = 'redis-clinic-dieu-tri-listener';
        $cache->delete($key);
    }

    public function actionDanhGia()
    {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');
            $data = PhongKhamLichDieuTri::find()->where(['id' => $id])->one();
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($data !== null) {
                $data->danh_gia = 1;
                if ($data->save()) {
                    return ['status' => '200', 'result' => 'Hãy nói với khách hàng bắt đầu đánh giá dịch vụ.'];
                }
            }
            return ['status' => '403', 'result' => 'Lỗi rồi, xin liên hệ bộ phận kỹ thuật'];
        }
    }

    public function actionIndex()
    {
        $searchModel = new PhongKhamLichDieuTriSearch();
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
        $model = new PhongKhamLichDieuTri();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
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
     * Updates an existing PhongKhamLichDieuTri model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = $this->findModel($id);

            if ($model === false) {
                return [
                    'status' => 400,
                    'mess' => 'Lịch điều trị không tồn tại.',
                ];
            }

            $customer = $this->findCustomer($model->customer_id);
            $modelOld = $model->getOldAttributes();

            if ($model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                try {
                    $model->time_dieu_tri = strtotime($model->time_dieu_tri);
                    $model->time_start = strtotime($model->time_start) != 0 ? strtotime($model->time_start) : null;
                    $model->time_end = strtotime($model->time_end) != 0 ? strtotime($model->time_end) : null;

                    if ($model->time_start > $model->time_end) {
                        return [
                            'status' => 403,
                            'mess' => 'Ngày bắt đầu không thể trước ngày kết thúc.',
                        ];
                    }

                    $transaction = Yii::$app->db->beginTransaction(
                        Transaction::SERIALIZABLE
                    );

                    if ($model->validate() && $model->save()) {
                        $modelNews = $model->getAttributes();
                        unset($modelOld['updated_at']);
                        unset($modelNews['updated_at']);

                        if ($modelNews != $modelOld) {
                            $dieuTriTree = new PhongKhamLichDieuTriTree();
                            $arr = $model->getAttributes();
                            $arr['dieu_tri_id'] = $model->getPrimaryKey();
                            unset($arr['id']);

                            foreach ($arr as $key => $item) {
                                $dieuTriTree->$key = $item;
                            }

                            if ($dieuTriTree->save()) {
                                $transaction->commit();
                                return [
                                    'status' => 200,
                                    'mess' => Yii::$app->params['update-success'],
                                ];
                            } else {
                                $transaction->rollBack();
                                return [
                                    'status' => 403,
                                    'mess' => Yii::$app->params['update-danger'],
                                ];
                            }
                        } else {
                            $transaction->commit();
                            return [
                                'status' => 200,
                                'mess' => Yii::$app->params['update-success'],
                            ];
                        }
                    }
                } catch (\yii\db\Exception $exception) {
                    $transaction->rollBack();
                    return [
                        'status' => 403,
                        'mess' => $exception->getMessage(),
                    ];
                }
            }

            return $this->renderAjax('update', [
                'model' => $model,
                'customer' => $customer,
            ]);
        }
    }

    public function actionStartTime()
    {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');
            $dieutri = $this->findModel($id);
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($dieutri === false) {
                return [
                    'status' => false,
                    'result' => 'Lịch điều trị không tồn tại',
                ];
            }
            $dieutri->time_start = time();
            $dieutri->time_end = null;
            if ($dieutri->save()) {
                return [
                    'status' => true,
                    'result' => 'Bắt đầu lịch điều trị thành công.',
                ];
            } else {
                return [
                    'status' => false,
                    'result' => $dieutri->getErrors(), //'Bạn đã cập nhật thất bại.',
                ];
            }
        }
    }

    public function actionEndTime()
    {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');
            $dieutri = $this->findModel($id);
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($dieutri === false) {
                return [
                    'status' => false,
                    'result' => 'Lịch điều trị không tồn tại',
                ];
            }
            if ($dieutri->time_start == null) {
                return [
                    'status' => false,
                    'result' => 'Bạn phải cập nhật thời gian bắt đầu trước.',
                ];
            }
            $dieutri->time_end = time();
            if ($dieutri->save()) {
                return [
                    'status' => true,
                    'result' => 'Kết thúc lịch điều trị thành công.',
                ];
            } else {
                return [
                    'status' => false,
//                    'result' => $dieutri->getErrors(),
                    'result' => 'Bạn đã cập nhật thất bại.',
                ];
            }
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


    protected function findModel($id)
    {
        $dieuTri = PhongKhamLichDieuTri::find()->where(['id' => $id])->one();
        if ($dieuTri !== null) {
            return $dieuTri;
        }

        return false;
    }

    protected function findOrder($id)
    {
        $order = PhongKhamDonHang::find()->where(['customer_id' => $id])->one();
        if ($order !== null) {
            return $order;
        }

        return false;
    }

    protected function findCustomer($id)
    {
        $model = Clinic::findOne($id);
        if (($model !== null)) {
            return $model;
        }

        return false;
    }
}
