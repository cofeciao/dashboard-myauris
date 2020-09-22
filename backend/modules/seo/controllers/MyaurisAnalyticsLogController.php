<?php

namespace backend\modules\seo\controllers;

use Yii;
use backend\modules\seo\models\MyaurisAnalyticsLog;
use backend\modules\seo\models\search\MyaurisAnalyticsLogSearch;
use backend\components\MyController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\MyComponent;

/**
 * MyaurisAnalyticsLogController implements the CRUD actions for MyaurisAnalyticsLog model.
 */
class MyaurisAnalyticsLogController extends MyController
{
    public function actionIndex()
    {
        $searchModel = new MyaurisAnalyticsLogSearch();
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

    protected function findModel($id)
    {
        if (($model = MyaurisAnalyticsLog::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
    }
}
