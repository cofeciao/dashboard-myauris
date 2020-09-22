<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 15-Feb-19
 * Time: 11:33 AM
 */

namespace backend\modules\api\components;

use backend\modules\api\modules\v1\models\UserApi;
use Yii;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\ContentNegotiator;
use yii\rest\ActiveController;
use yii\web\Response;

class RestController extends ActiveController
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

        $behaviors['authenticator']['authMethods']['basicAuth'] = [
            'class' => HttpBasicAuth::class,
            'auth' => function ($username, $password) {
                $user = UserApi::findByUsername($username);

                if ($user !== null && $user->validatePassword($password)) {
                    $roleUser = $user->getRoleName($user->id);
                    if ($roleUser == UserApi::USER_API) {
                        return $user;
                    }
                }
                return null;
            }
        ];

        return $behaviors;
    }

    public function actions()
    {
        return [
        ];
    }
}
