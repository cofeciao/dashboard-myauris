<?php

namespace backend\modules\log\models;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Yii;
use backend\models\CallLogModel;

class CallLog extends CallLogModel
{
    public static function getStatusCall($status)
    {
        switch ($status) {
            case 1:
                $result = '<div class="badge badge-primary">Khách bắt máy</div>';
                break;
            case 2:
                $result = '<div class="badge bg-blue-grey">Khách không bắt máy</div>';
                break;
            case 3:
                break;
            case 4:
                $result = '<div class="badge badge-warning">Nhân viên tự tắt</div>';
                break;
            case 5:
                $result = '<div class="badge badge-danger">Nhân viên từ chối</div>';
                break;
            case 6:
                $result = '<div class="badge badge-danger">Cuộc gọi nhỡ</div>';
                break;
            case 7:
                break;
            case 8:
                $result = '<div class="badge badge-primary">Nhân viên bắt máy</div>';
                break;
            default:
                $result = null;
                break;
        }
        return $result;
    }

    public function getDetailCall($callId)
    {
//        $url = 'http://acd-api.vht.com.vn/rest/softphones/cdrs';
//
//        $api_key = 'fc5e75859bb425deb8ae3d36ddcd36bb';
//        $api_secret = '4e45b1e32eacc36c9cf766dff0d91372';
//
//        $client = new Client([
//            'headers' => [
//                'Content-Type' => 'application/json',
//                'Authorization' => 'Basic ' . base64_encode($api_key . ':' . $api_secret)
//            ]
//        ]);
//
//        try {
//            $response = $client->request('GET', $url, [
//                'sdk_call_id' => 'call-vn-1-NQEL2HHB01-1551194663527'
//            ]);
//
//            $body = $response->getBody();
//            $body = json_decode($body);

//            var_dump($body);
        $arr = ['key_index' => 'https://acd-api.vht.com.vn/rest/recordings/bmljUlNWczFZVGFLTGk4ZWFRMlpqaGxNbXRxRHFlSkJ4bHRWVU1yOUgwbWhSOTdyRlhFbW45M0hHQmIvY2liOVFLZ0o4a3lxUzcwdE1rc3E5eWw0c2trS3ZTMUUzRkpMYzVJTXV3VmdJSkZqdEFGLzdRNlpML2NwdFBQK3RUL3pWWnFMRWVNTXdmamtqaEVQQzFRekN0NE51SldrclVEODI5aWY0QkhMQ0NjPQ=='];
        return json_encode($arr);
//        } catch (ClientException $e) {
//            var_dump('123');
//            return ['status' => '403'];
//        }
    }
}
