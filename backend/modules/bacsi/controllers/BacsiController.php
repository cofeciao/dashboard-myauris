<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tran
 * Date: 06-04-2019
 * Time: 10:48 AM
 */

namespace backend\modules\bacsi\controllers;

use backend\components\MyComponent;
use backend\models\search\DirectSaleSearch;
use backend\components\MyController;
use backend\modules\bacsi\models\BacsiModel;
use backend\modules\bacsi\models\DentalTagModel;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\widgets\ActiveForm;

class BacsiController extends MyController
{
    public function actionIndex()
    {
        $searchModel = new DirectSaleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (MyComponent::hasCookies('pageSize')) {
            $dataProvider->pagination->defaultPageSize = MyComponent::getCookies('pageSize');
        } else {
            $dataProvider->pagination->defaultPageSize = 10;
        }

        $pageSize = $dataProvider->pagination->defaultPageSize;

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

    public function actionUpdate($id)
    {
        $customer = BacsiModel::findOne($id);
        if ($customer == null) {
            return $this->redirect(['index']);
        }
        $listTag = DentalTagModel::find()->joinWith(['tinhTrangRangHasMany'])->where(['customer_id' => $id])->indexBy('id')->all();
        foreach($listTag as $id_tag => $tag) {
            if (is_array($tag->tinhTrangRangHasMany)) {
                $listTag[$id_tag]->tinh_trang_rang = ArrayHelper::map($tag->tinhTrangRangHasMany, 'name', 'name');
            }
        }

        return $this->render('update', [
            'customer' => $customer,
            'listTag' => $listTag
        ]);
    }

    public function actionValidateHuongDieuTri($id = null)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = BacsiModel::find()->where(['id' => $id])->one();
            if ($model !== null && $model->load(Yii::$app->request->post())) {
                return ActiveForm::validate($model);
            }
        }
    }

    public function actionSubmitHuongDieuTri($id = null){
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = BacsiModel::find()->where(['id' => $id])->one();
            if($model == null) return [
                'code' => 404,
                'msg' => 'Không tìm thấy dữ liệu'
            ];
            if(!$model->load(Yii::$app->request->post()) || !$model->validate()) return [
                'code' => 400,
                'msg' => 'Có lỗi khi kiểm tra dữ liệu hướng điều trị',
                'error' => $model->getErrors()
            ];
            if (!$model->save()) return [
                'code' => 400,
                'msg' => 'Có lỗi khi lưu hướng điều trị',
                'error' => $model->getErrors()
            ];
            return [
                'code' => 200,
                'msg' => 'Cập nhật hướng điều trị thành công'
            ];
        }
    }

    public function actionCreateOrUpdateTag($id)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $code = 200;
            $data = [];
            $model = new DentalTagModel();
            if ($id != null) {
                $model = DentalTagModel::findOne($id);
            }
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if (!$model->save()) {
                    $code = 501;
                    $data = [
                        'msg' => 'Lưu thất bại!'
                    ];
                    /*$data = '';
                    foreach ($model->getErrors() as $error) {
                        $data .= $error[0] . '<br/>';
                    }*/
                } else {
                    $data = [
                        'msg' => 'Lưu thành công!',
                        'key' => $model->getPrimaryKey(),
                        'ketqua_thamkham' => $model->ketqua_thamkham
                    ];
                }
            } else {
                $code = 400;
                $msg = '';
                foreach ($model->getErrors() as $error) {
                    $msg .= $error[0] . '<br/>';
                }
                $data = [
                    'msg' => $msg
                ];
            }
            return [
                'code' => $code,
                'data' => $data
            ];
        }
        return $this->redirect(['index']);
    }

    public function actionDeleteTag()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $code = 200;
            $msg = '';
            $id = Yii::$app->request->post('id');
            $model = DentalTagModel::findOne($id);
            if ($model === null) {
                $code = 403;
                $msg = "Không tìm thấy dữ liệu!";
            } else {
                if (!$model->delete()) {
                    $code = 400;
                    $msg = "Xoá thất bại!";
                } else {
                    $msg = "Xoá thành công!";
                }
            }
            return [
                'code' => $code,
                'msg' => $msg
            ];
        }
        return $this->redirect(['index']);
    }

    public function actionLoadTagHtml()
    {
        Yii::$app->response->format = Response::FORMAT_HTML;
        $request = Yii::$app->request;
        $id = $request->post('id');
        $tag = $request->post('tag');
        if ($id != null) {
            $model = DentalTagModel::find()->where(['customer_id' => $id, 'tag' => $tag])->one();
        }
        if ($model == null) {
            $model = new DentalTagModel();
            $model->customer_id = $id;
            $model->tag = $tag;
        }
        return $this->renderPartial('_tagHtml', [
            'model' => $model
        ]);
    }

    public function actionCheckFinal()
    {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');

            $model = $this->findModel($id);

            try {
                if ($model->customer_bacsi_check_final == 1) {
                    $model->customer_bacsi_check_final = 0;
                } else {
                    $model->customer_bacsi_check_final = 1;
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
        $model = BacsiModel::findOne($id);
        if (($model !== null)) {
            return $model;
        }

        return false;
    }
}
