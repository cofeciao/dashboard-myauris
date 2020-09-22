<?php

namespace backend\modules\affiliate\controllers;

use Yii;
use backend\modules\clinic\models\Clinic;
use backend\modules\affiliate\models\search\AffiliateCustomerSearch;
use backend\components\MyController;
use yii\web\NotFoundHttpException;
use backend\components\MyComponent;

/**
 * AffiliateCustomerController implements the CRUD actions for Clinic model.
 */
class AffiliateCustomerController extends MyController
{
    public function actionIndex()
    {
        $searchModel = new AffiliateCustomerSearch();
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

    public function actionAffiliate($id)
    {
        if (Yii::$app->request->isAjax) {
        }
    }

    protected function findModel($id)
    {
        if (($model = Clinic::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
