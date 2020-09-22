<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 15-Jan-19
 * Time: 2:43 PM
 */

namespace backend\controllers;

use backend\components\MyController;
use backend\models\Customer;
use backend\modules\location\models\District;
use Yii;
use yii\web\Response;

class CustomerController extends MyController
{
    public $cache;
    private $authorization = null;

    public function init()
    {
        $this->cache = Yii::$app->cache;
        $endDate = strtotime(date('d-m-Y')) + 86399;
        $key = 'redis-call-success-' . $endDate;
        $key1 = 'redis-new-phone-' . $endDate;
        $key2 = 'redis-khach-dh-' . $endDate;
        $key3 = 'redis-khach-old-fail-to-dathen-' . $endDate;
        $this->cache->delete($key);
        $this->cache->delete($key1);
        $this->cache->delete($key2);
        $this->cache->delete($key3);
        $this->authorization = base64_encode(API_USER . ':' . API_PASS);
        parent::init();
    }

    public function actionGetDistrict()
    {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');
            $key = 'redis-get-district-' . $id;
            $result = $this->cache->get($key);
            if ($result === false) {
                $result = [];
                $district = District::find()->where(['ProvinceId' => $id])->orderBy(['name' => SORT_ASC])->all();
                foreach ($district as $item) {
                    $result[] = [
                        'id' => $item->id,
                        'name' => $item->name,
                    ];
                }
                $this->cache->set($key, $result, 86400);
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $result;
        }
    }

    public function actionCreateAffiliate()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $customer = Yii::$app->request->post('customer');
            $data_customer = Customer::find()->where(['id' => $customer])->one();
            if ($data_customer == null) {
                return [
                'status' => 404,
                'mess' => 'Không tìm thấy thông tin khách hàng',
            ];
            }
            $data = $data_customer->getAttributes(['id', 'customer_code', 'full_name', 'forename', 'avatar', 'phone', 'sex', 'birthday', 'slug', 'face_customer']);
            if ($data['avatar'] != null && file_exists(Yii::$app->basePath . '/web/uploads/avatar/200x200/' . $data['avatar'])) {
                $data['avatar'] = Yii::getAlias('@frontendUrl') . '/uploads/avatar/200x200/' . $data['avatar'];
            } else {
                $data['avatar'] = null;
            }
            $client = new \GuzzleHttp\Client();
            $response = $client->request('POST', API_URL . '/customer/create-customer', [
                'headers' => [
                    'Authorization' => 'Basic ' . $this->authorization,
                ],
                'form_params' => [
                    'customer' => json_encode($data)
                ]
            ]);
            $res = json_decode($response->getBody()->getContents(), true);
            if(!is_array($res)) return [
                'status' => 400,
                'mess' => 'Có lỗi khi khởi tạo affiliate',
                'data' => $res
            ];
            if ($res['status'] == 200 && $data_customer->is_affiliate_created == 0) {
                $data_customer->updateAttributes([
                    'is_affiliate_created' => 1
                ]);
            }
            return $res;
        }
    }
}
