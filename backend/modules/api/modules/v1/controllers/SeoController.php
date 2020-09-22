<?php

namespace backend\modules\api\modules\v1\controllers;

use backend\modules\api\components\RestController;
use backend\modules\api\modules\v1\models\SeoModel;
use yii\db\Transaction;
use yii\filters\auth\CompositeAuth;
use yii\filters\Cors;

class SeoController extends RestController
{
    public $modelClass = 'backend\modules\api\modules\v1\models\SeoModel';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                // restrict access to
                'Origin' => ['http://myauris.tm', 'https://myauris.vn'],
//                'Access-Control-Allow-Origin' => ['*'],
                // Allow only POST and PUT methods
                'Access-Control-Request-Method' => ['POST'],
                // Allow only headers 'X-Wsse'
                'Access-Control-Request-Headers' => ['X-Wsse'],
                // Allow credentials (cookies, authorization headers, etc.) to be exposed to the browser
                'Access-Control-Allow-Credentials' => true,
                // Allow OPTIONS caching
                'Access-Control-Max-Age' => 3600,
                // Allow the X-Pagination-Current-Page header to be exposed to the browser.
                'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page'],
            ]
        ];
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class
        ];
        return $behaviors;
    }

    public function actionCustomerMyaurisLogs()
    {
        if (\Yii::$app->request->post()) {
            $user_id = \Yii::$app->request->post('user_id');
            $event_name = \Yii::$app->request->post('event_name');
            $timer = \Yii::$app->request->post('timer');
            $event_url = \Yii::$app->request->post('event_url');
            $logs = \Yii::$app->request->post('logs');
            if ($user_id == null || $event_url == null || $timer == null) return [
                'code' => 400,
                'msg' => 'Thiếu dữ liệu'
            ];
            $transaction = \Yii::$app->db->beginTransaction(Transaction::SERIALIZABLE);
            $errors = [];
            $time_log = time();
            if (is_array($logs)) {
                foreach ($logs as $i => $log) {
                    $log = array_merge([
                        'from_url' => null,
                        'referer_url' => null,
                        'first_url' => null,
                        'time' => null,
                        'user_id' => null
                    ], $log);
                    $modelConnect = new SeoModel();
                    $modelConnect->scenario = SeoModel::SCENARIO_CONNECT;
                    $modelConnect->setAttributes([
                        'from_url' => $log['from_url'],
                        'referer_url' => $log['referer_url'],
                        'first_url' => $log['first_url'],
                        'time' => (int)($log['time'] / 1000),
                        'cookie_user_id' => $user_id,
                        'created_at' => $time_log
                    ]);
                    if (!$modelConnect->validate() || !$modelConnect->save()) {
                        $errors[$i] = [
                            'data' => [
                                'from_url' => $log['from_url'],
                                'referer_url' => $log['referer_url'],
                                'first_url' => $log['first_url'],
                                'time' => (int)($log['time'] / 1000),
                                'cookie_user_id' => $user_id,
                                'created_at' => $time_log
                            ],
                            'log' => $log,
                            'error' => $modelConnect->getErrors()
                        ];
                    }
                }
            }
            $modelCall = new SeoModel();
            $modelCall->scenario = SeoModel::SCENARIO_CALL;
            $modelCall->setAttributes([
                'event_url' => $event_url,
                'time' => (int)($timer / 1000),
                'event_name' => $event_name,
                'cookie_user_id' => $user_id,
                'created_at' => $time_log
            ]);
            if (!$modelCall->validate() || !$modelCall->save()) {
                $errors[] = [
                    'data' => [
                        'event_url' => $event_url,
                        'time' => (int)($timer / 1000),
                        'event_name' => $event_name,
                        'cookie_user_id' => $user_id,
                        'created_at' => $time_log
                    ],
                    'error' => $modelCall->getErrors()
                ];
            }
            if (count($errors) > 0) {
                $transaction->rollBack();
                return [
                    'code' => 400,
                    'msg' => 'Lỗi lưu dữ liệu',
                    'error' => $errors
                ];
            }
            $transaction->commit();
            return [
                'code' => 200,
                'msg' => 'Lưu thành công'
            ];
        }
        return [
            'code' => 400,
            'msg' => 'Bạn không thể sử dụng chức năng này'
        ];
    }
}
