<?php

namespace backend\modules\api\components;

use backend\modules\api\modules\v1\models\UserApi;
use Yii;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\ContentNegotiator;
use yii\rest\ActiveController;
use yii\web\Response;

use yii\filters\auth\CompositeAuth;
use yii\filters\auth\QueryParamAuth;

class RestVerifyController extends ActiveController
{
    public $serializer = 'tuyakhov\jsonapi\Serializer';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['bootstrap'] = [
            'class' => ContentNegotiator::class,
            'formats' => [
                'application/vnd.api+json' => Response::FORMAT_JSON,
            ],
        ];

        return $behaviors;
    }

    protected function verbs()
    {
        $verbs = parent::verbs();
        $verbs['index'] = ['POST']; // only for POST
        return $verbs;
    }

    public function actions()
    {
        return [
        ];
    }
}
