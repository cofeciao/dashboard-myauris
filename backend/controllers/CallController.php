<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 05-Jan-19
 * Time: 8:56 AM
 */

namespace backend\controllers;

use backend\components\MyController;
use backend\models\CallLogModel;
use backend\models\CustomerModel;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\general\models\ContactPhone;
use common\models\UserProfile;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use yii\web\Response;

class CallController extends MyController
{
    public function actionIndex()
    {
        $callLogLast = CallLogModel::find()->orderBy(['id' => SORT_DESC])->one();
        var_dump($callLogLast);
    }

    public function actionCallLog()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $callLog = new CallLogModel();
            $data = \Yii::$app->request->post('callLog');
            $nhac_lich_id = \Yii::$app->request->post('nhac_lich_id');
            foreach ($data as $key => $value) {
                $callLog->setAttribute($key, $value);
            }
            if ($nhac_lich_id != null) {
                $callLog->setAttributes('nhac_lich_id', $nhac_lich_id);
            }
//            $callLogLast = CallLogModel::find()->orderBy(['id' => SORT_DESC, 'call_den_di' => 2])->one();
//            if ($callLogLast->call_status)
            if ($callLog->save()) {
                $status = '200';
            } else {
                $status = '403';
            }

            return ['status' => $status];
        }
    }

    public function actionGetCallInfo()
    {
        if (\Yii::$app->request->isAjax) {
//            if (\Yii::$app->request->getUserIP() != '127.0.0.1') {
            $result = $this->getInfo();
            $result['idNhanVien'] = \Yii::$app->user->id;
            $result['nameNhanVien'] = UserProfile::getFullName();
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return $result;
//            }
        }
    }

    public function actionGetCustomer()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $phone = \Yii::$app->request->post('phone');

            $idCustomer = null;
            $nameCustomer = $phone;
            $idNhanVien = null;
            $nameNhanVien = 'Trống';

            $contact = ContactPhone::find()->where(['phone' => $phone])->one();

            if ($contact !== null) {
                $nameCustomer = $contact->name;
            } else {
                $customer = CustomerModel::getCustomerByPhoneOne($phone);
                if ($customer !== null) {
                    $idCustomer = $customer->id;
                    $nameCustomer = $customer->full_name == null ? $customer->forename : $customer->full_name;
                    $idNhanVien = $customer->permission_user;
                    $nameNhanVien = UserProfile::getFullName($idNhanVien);
                }
            }

            return ['status' => '200', 'idCustomer' => $idCustomer, 'nameCustomer' => $nameCustomer, 'idNhanVien' => $idNhanVien, 'nameNhanVien' => $nameNhanVien];
        }
    }

    protected function getInfo()
    {
        $url = 'https://acd-api.vht.com.vn/rest/softphones/login';

        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'AppPlatform' => 'Web',
                'AppName' => 'vcall',
                'AppVersion' => '1.0'
            ]
        ]);
        $status = 200;
        $token = null;
        $acc_vpbx = null;
        $acc_ext = null;
        $err = '';

        try {
            $response = $client->request('POST', $url, [
                'body' => $this->setBodyCall(),
            ]);

            $body = $response->getBody();
            $body = json_decode($body);

            $token = $body->token;
            $acc_vpbx = $body->account->vpbx;
            $acc_ext = $body->account->extension;
        } catch (ClientException $e) {
            if (in_array(\Yii::$app->user->id, ['102', '146'])) {
                die(var_dump($e));
            }

            $status = '403';
            $err = $e;
        }
        return ['status' => $status, 'token' => $token, 'vpbx' => $acc_vpbx, 'ext' => $acc_ext, 'err' => $err];
    }

    public function actionCall()
    {
//        $token = $this->getToken();
        return $this->render('call', [
//            'token' => $token,
        ]);
    }

    protected function getToken()
    {
        $url = 'https://acd-api.vht.com.vn/rest/softphones/login';

        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'AppPlatform' => 'Web',
                'AppName' => 'vcall',
                'AppVersion' => '1.0'
            ]
        ]);

        try {
            $response = $client->request('POST', $url, [
                'body' => $this->setBodyCall(),
            ]);

            $body = $response->getBody();
            $body = json_decode($body);

            $token = $body->token;
        } catch (ClientException $e) {
            var_dump($e->getRequest());
            var_dump($e->getResponse());
        }
        return $token;
    }

    protected function setBodyCall()
    {
        $body = [
            'username' => \Yii::$app->user->identity->vpbx_acc,
            'password' => \Yii::$app->user->identity->vpbx_pass,
        ];
        return json_encode($body);
    }
}
