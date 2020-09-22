<?php

namespace backend\modules\api\modules\v3\components;

use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpHeaderAuth; // X-Api-Key
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

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpHeaderAuth::className(),
            ],
        ];

        return $behaviors;
    }
}
