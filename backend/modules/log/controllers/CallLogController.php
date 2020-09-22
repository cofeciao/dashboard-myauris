<?php

namespace backend\modules\log\controllers;

use backend\components\MyComponent;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Yii;
use backend\modules\log\models\CallLog;
use backend\modules\log\models\search\CallLogSearch;
use backend\components\MyController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CallLogController implements the CRUD actions for CallLog model.
 */
class CallLogController extends MyController
{
    public function actionIndex()
    {
        $searchModel = new CallLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (MyComponent::hasCookies('pageSize')) {
            $dataProvider->pagination->pageSize = MyComponent::getCookies('pageSize');
        } else {
            $dataProvider->pagination->pageSize = 20;
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

    public function actionGetDetailCall()
    {
        $url = 'http://acd-api.vht.com.vn/rest/softphones/cdrs';

        $api_key = 'fc5e75859bb425deb8ae3d36ddcd36bb';
        $api_secret = '4e45b1e32eacc36c9cf766dff0d91372';

        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($api_key . ':' . $api_secret)
            ]
        ]);

        try {
            $response = $client->request('GET', $url, [
                'sdk_call_id' => 'call-vn-1-73VH6572P1-1552085318336'
            ]);

            $body = $response->getBody();
            $body = json_decode($body);

            var_dump($body);
        } catch (ClientException $e) {
            var_dump('123');
        }
        die;
        return [];
    }

    public function actionGetFile()
    {
        $url = 'https://acd-api.vht.com.vn/rest/cdrs';

        $api_key = 'fc5e75859bb425deb8ae3d36ddcd36bb';
        $api_secret = '4e45b1e32eacc36c9cf766dff0d91372';

        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($api_key . ':' . $api_secret)
            ]
        ]);

        try {
            $response = $client->request('GET', $url, []);

            $body = $response->getBody();
            $body = json_decode($body);

            var_dump($body);
        } catch (ClientException $e) {
            var_dump('123');
        }
        die;
        return [];
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

    protected function findModel($id)
    {
        if (($model = CallLog::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
