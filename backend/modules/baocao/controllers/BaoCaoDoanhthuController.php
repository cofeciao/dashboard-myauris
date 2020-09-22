<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 16-May-19
 * Time: 10:12 AM
 */

namespace backend\modules\baocao\controllers;

use backend\components\MyComponent;
use backend\modules\baocao\models\doanhthu\search\BaoCaoDonHangModelSearch;
use Yii;
use backend\components\MyController;
use backend\modules\clinic\components\BaoCaoDoanhThu;

class BaoCaoDoanhthuController extends MyController
{
    public function actionIndex()
    {
        $searchModel = new BaoCaoDonHangModelSearch();
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
}
