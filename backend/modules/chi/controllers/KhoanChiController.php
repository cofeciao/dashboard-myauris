<?php

namespace backend\modules\chi\controllers;

use Yii;
use backend\modules\chi\models\KhoanChi;
use backend\modules\chi\models\search\KhoanChiSearch;
use backend\components\MyController;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\MyComponent;
use yii\web\Response;

/**
 * KhoanChiController implements the CRUD actions for KhoanChi model.
 */
class KhoanChiController extends MyController
{
    public function actionIndex()
    {
        $searchModel  = new KhoanChiSearch();
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
            return $this->renderAjax('view', [
                'model' => $this->findModel($id),
            ]);
        }

        return $this->redirect(['index']);
    }

    public function actionCreate()
    {
        $model = new KhoanChi();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            try {
                $model->save();
                Yii::$app->session->setFlash('alert', [
                    'body'  => Yii::$app->params['create-success'],
                    'class' => 'bg-success',
                ]);
            } catch (\yii\db\Exception $exception) {
                Yii::$app->session->setFlash('alert', [
                    'body'  => Yii::$app->params['create-danger'],
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
     * Updates an existing KhoanChi model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
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
                    'body'  => Yii::$app->params['update-success'],
                    'class' => 'bg-success',
                ]);
            } catch (\yii\db\Exception $exception) {
                Yii::$app->session->setFlash('alert', [
                    'body'  => $exception->getMessage(),
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
        if (($model = KhoanChi::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
    }

    public function actionLoadKhoanChiByNhomChi()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $nhomchi                    = Yii::$app->request->post('nhomchi');
            $list                       = KhoanChi::find()->where(['category' => $nhomchi])->published()->all();
            $data                       = '';
            if ($list != null) {
                foreach ($list as $nhom) {
                    $data .= '<option value="' . $nhom->id . '">' . $nhom->name . '</option>';
                }
            }

            return [
                'code' => 200,
                'data' => $data
            ];
        }
    }

    public function actionGetListKhoanChi()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $res = KhoanChi::find()->select(['id', 'name as text'])->asArray();
            $q   = Yii::$app->request->get('q', '');
//            echo '<pre>';
//            print_r($q);
//            echo '</pre>';
            if ( ! empty($q)) {
                $res = $res->andFilterWhere(['like', 'name', $q]);
            }

//            echo '<pre>';
//            print_r($res->createCommand()->getRawSql());
//            echo '</pre>';die;
            $res = $res->all();

//            echo '<pre>';
//            print_r($res);
//            echo '</pre>';
//            die;


//            $res = ArrayHelper::map($res, 'id', 'name');
            if ( ! empty($res)) {
                return $res;
            }

        }
        die();


    }
}
