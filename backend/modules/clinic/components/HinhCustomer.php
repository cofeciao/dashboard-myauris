<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 23-Mar-19
 * Time: 2:39 PM
 */

namespace backend\modules\clinic\components;

use backend\components\GapiComponent;
use backend\components\MyComponent;
use backend\modules\clinic\models\CustomerImages;
use Yii;
use backend\components\MyController;
use backend\modules\clinic\models\search\CustomerSearch;
use yii\helpers\Url;
use yii\web\Response;

class HinhCustomer extends MyController
{
    public function actionIndex()
    {
        $searchModel = new CustomerSearch();
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

    public function actionDownload($fileId, $type = 'gFile')
    {
        if (!in_array($type, ['gFile', 'local'])) {
            Yii::$app->response->statusCode = 400;
            return new \yii\web\BadRequestHttpException();
        }
        Yii::$app->response->format = Response::FORMAT_RAW;
        if ($type == 'local') {
            $file = CustomerImages::findOne($fileId);
            if ($file == null || $file->google_id == null) {
                Yii::$app->response->statusCode = 404;
                return new \yii\web\NotFoundHttpException();
            }
            $gId = $file->google_id;
        } else {
            $gId = $fileId;
        }

        $service = GapiComponent::getService();
        $file = GapiComponent::getFile($service, $gId);
        if ($file == null) {
            Yii::$app->response->statusCode = 404;
            return new \yii\web\NotFoundHttpException();
        }
        $image = GapiComponent::downloadFile($service, $gId, Url::to('@backend/web/downloads/' . $file['name']));

        Yii::$app->response->headers->set('content-type', $file['mimeType']);
        Yii::$app->response->headers->set('charset', 'utf-8');
        Yii::$app->response->headers->set('content-disposition', $file['name']);
        Yii::$app->response->headers->set('title', $file['name']);
        return Yii::$app->response->sendFile($image)->on(Response::EVENT_AFTER_SEND, function ($event) {
            unlink($event->data);
        }, $image);
    }
}
