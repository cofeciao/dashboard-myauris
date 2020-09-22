<?php

namespace backend\modules\clinic\controllers;

use backend\components\MyComponent;
use backend\controllers\CustomerController;
use backend\modules\clinic\models\Clinic;
use Yii;
use yii\db\Exception;
use yii\db\Transaction;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Response;
use yii\widgets\ActiveForm;
use backend\modules\clinic\models\search\PhongKhamDonHangSearch;
use backend\modules\user\models\User;
use backend\modules\clinic\models\PhongKhamDonHang;


/**
 * Default controller for the `clinic` module
 */
class ClinicCheckController extends CustomerController
{
    public function init()
    {
        parent::init();
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        
        
        $searchModel = new PhongKhamDonHangSearch();
        $customer = null;
        $dataProvider = $searchModel->searchClinicCheck(Yii::$app->request->queryParams);

        $sum_don_hang = $dataProvider->query->sum('thanh_tien') - $dataProvider->query->sum('chiet_khau');

        if (MyComponent::hasCookies('pageSize')) {
            $dataProvider->pagination->pageSize = MyComponent::getCookies('pageSize');
        } else {
            $dataProvider->pagination->pageSize = 10;
        }
        $pageSize = $dataProvider->pagination->pageSize;
        $totalCount = $dataProvider->totalCount;
        $totalPage = (($totalCount + $pageSize - 1) / $pageSize);

        return $this->render('index', [
            'customer' => $customer,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalPage' => $totalPage,
            'sum_don_hang' => $sum_don_hang
        ]);
    }

    public function actionPerpage($perpage)
    {
        MyComponent::setCookies('pageSize', $perpage);
    }

    protected function findModel($id)
    {
        $model = Clinic::findOne($id);
        if (($model !== null)) {
            return $model;
        }

        return false;
    }

}
