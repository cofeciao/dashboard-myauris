<?php


namespace backend\modules\support\controllers;

use backend\modules\support\models\search\SupportSearch;
use Yii;
use backend\components\MyController;
use backend\modules\support\models\Support;
use backend\modules\support\models\SupportCatagory;
use yii\data\ActiveDataProvider;
use yii\web\Response;

class ListSupportController extends MyController
{
    public function actionIndex($catagory_id)
    {
        $catagory = SupportCatagory::getCatagoryById($catagory_id);

        $searchModel = new SupportSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $catagory_id);
        $dataProvider->pagination->pageSize = 12;

        return $this->render('index', [
            'listDataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'catagory' => $catagory
        ]);
    }

    public function actionList($id)
    {
        $catagory = SupportCatagory::getCatagoryById($id);

        $query = Support::getSupportByCatagory($id);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => 9
            ],
        ]);

        return $this->render('list', [
            'listDataProvider' => $dataProvider,
            'catagory' => $catagory
        ]);
    }

    public function actionView($catagory_id, $id)
    {
        if (($model = Support::getSupport($id)) !== null) {
            $searchModel = new SupportSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $catagory_id);
            return $this->render('view', [
                'model' => $model,
                'searchModel' => $searchModel,
            ]);
        } else {
            return $this->redirect(['index']);
        }
    }

    public function actionSearch()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $text = Yii::$app->request->post('text');
            $id = Yii::$app->request->post('id');

            if ($text !== '') {
                $model = Support::searchSupport($text);
            } else {
                $model = Support::getSupportByCatagory($id);
                $model = $model->all();
            }

            return $this->renderPartial('_searchHtml', [
                'model' => $model
            ]);
        }
    }
}
