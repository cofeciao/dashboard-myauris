<?php


namespace backend\modules\social\controllers;

use backend\components\MyComponent;
use backend\components\MyController;
use backend\controllers\CustomerController;
use backend\modules\customer\models\Dep365CustomerOnlineCome;
use backend\modules\customer\models\Dep365CustomerOnlineDathenStatus;
use backend\modules\customer\models\Dep365CustomerOnlineStatus;
use backend\modules\social\models\SocialFacebook;

class SocialFacebookController extends MyController
{
    public function actionIndex()
    {
        $status = Dep365CustomerOnlineStatus::getStatusCustomerOnline();
        $dat_hen = Dep365CustomerOnlineDathenStatus::getDatHenStatus();
        $come = Dep365CustomerOnlineCome::getCustomerOnlineCome();
        $filter = [];
        foreach ($status as $item) {
            $filter['status'][$item->id] =  $item->name;
        }
        foreach ($dat_hen as $item) {
            $filter['dat_hen'][$item->id] =  $item->name;
        }
        foreach ($come as $item) {
            $filter['come'][$item->id] =  $item->name;
        }
        $data_filter = new SocialFacebook();
        $dataProvider = $data_filter->search(\Yii::$app->request->queryParams);
        if (MyComponent::hasCookies('pageSize')) {
            $dataProvider->pagination->pageSize = MyComponent::getCookies('pageSize');
        } else {
            $dataProvider->pagination->pageSize = 10;
        }
        $pageSize = $dataProvider->pagination->pageSize;
        $totalCount = $dataProvider->totalCount;
        $totalPage = (($totalCount + $pageSize - 1) / $pageSize);
        return $this->render(
            'index',
            [
                'filter' => $filter,
                'data_filter' => $data_filter,
                'dataProvider' => $dataProvider,
                'totalPage' => $totalPage
            ]
        );
    }
    public function actionPerpage($perpage)
    {
        MyComponent::setCookies('pageSize', $perpage);
    }
}
