<?php

namespace backend\modules\api\modules\v2\components;

use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpHeaderAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\ContentNegotiator;
use yii\rest\ActiveController;
use yii\web\Response;

class RestfullController extends ActiveController
{
    public $serializer = 'tuyakhov\jsonapi\Serializer';
    public $modelClass = '';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['bootstrap'] = [
            'class' => ContentNegotiator::class,
            'formats' => [
                'application/vnd.api+json' => Response::FORMAT_JSON,
            ],
        ];

        //  need for app X-Api-Key
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'authMethods' => [
                HttpHeaderAuth::class,
            ],
        ];

        return $behaviors;
    }

    protected function verbs()
    {
        $verbs = parent::verbs();
        $verbs['save-log-cskh'] = ['POST']; // only for POST
        return $verbs;
    }
}
