<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 20-May-19
 * Time: 1:41 PM
 */

namespace backend\modules\log\controllers;

use backend\components\MyComponent;
use backend\components\MyController;
use backend\modules\log\components\VhtCallLogComponent;
use backend\modules\log\data\VhtDataCallLog;
use yii\data\Pagination;
use yii\widgets\LinkPager;

class VhtCallLogController extends MyController
{
    public function actionIndex()
    {
        $params = [];

        if (MyComponent::hasCookies('pageSize')) {
            $pageSize = MyComponent::getCookies('pageSize');
            $params['limit'] = MyComponent::getCookies('pageSize');
        } else {
            $pageSize = 10;
            $params['limit'] = 10;
        }
        if (\Yii::$app->request->get('page') == null) {
            $currentPage = 1;
            $params['page'] = 1;
        } else {
            $currentPage = (int)\Yii::$app->request->get('page');
            $params['page'] = (int)\Yii::$app->request->get('page');
        }
        $from_number = \Yii::$app->request->get('from_number');
        $to_extension = \Yii::$app->request->get('to_extension');
        $to_number = \Yii::$app->request->get('to_number');
        if (!in_array($from_number, [null, ''])) {
            $params['from_number'] = $from_number;
        }
        if (!in_array($to_extension, [null, ''])) {
            $params['to_extension'] = $to_extension;
        }
        if (!in_array($to_number, [null, ''])) {
            $params['to_number'] = $to_number;
        }

        $VhtCallLog = new VhtCallLogComponent([], $params);
        $model = $VhtCallLog->ConnectVht();

        $pages = new Pagination([
            'totalCount' => $model != null ? $model->total : null,
            'defaultPageSize' => $pageSize
        ]);

        $dataProvider = new VhtDataCallLog([
            'allModels' => $model != null ? $model->items : null,
            'total' => $model != null ? $model->total : null,
            'pagination' => [
                'defaultPageSize' => $pageSize
            ]
        ]);

        if (MyComponent::hasCookies('pageSize')) {
            $dataProvider->pagination->pageSize = MyComponent::getCookies('pageSize');
        } else {
            $dataProvider->pagination->pageSize = 10;
        }

        $totalCount = $dataProvider->totalCount;
        $totalPage = (($totalCount + $pageSize - 1) / $pageSize);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'totalPage' => $totalPage,
            'model' => $model,
            'pageSize' => $pageSize,
            'currentPage' => $currentPage,
            'pages' => $pages
        ]);
    }

    public function actionPerpage($perpage)
    {
        MyComponent::setCookies('pageSize', $perpage);
    }
}
