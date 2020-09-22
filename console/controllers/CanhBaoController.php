<?php


namespace console\controllers;


use backend\modules\baocao\components\BaoCaoFacebook;
use backend\modules\customer\components\CustomerComponents;
use backend\modules\customer\models\Dep365SendSms;
use backend\modules\setting\models\Setting;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Yii;
use yii\console\Controller;

class CanhBaoController extends Controller
{
    public function actionCanhBaoChayAdsFacebookToSms()
    {
        set_time_limit(1200);
        ini_set("log_errors", 1);
        ini_set("error_log", "error.log");

        $from = date('01-m-Y');
        $yesterday = strtotime(date('d-m-Y') . '-1 day');
        $to = date('d-m-Y', $yesterday);
        $tongTien = BaoCaoFacebook::tongTien($from, $to, null, null, null);

        //Thực thu
        $doanhThuTheoThangData = CustomerComponents::getRevenue(strtotime($from), strtotime($to));
        $dt = 0;
        foreach ($doanhThuTheoThangData as $value) {
            $dt += $value->tien;
        }

        $tyle = round(($tongTien/$dt)*100, 2);

        if($tyle > 30) {
            $phoneArr = $this->getPhoneCanhBaoSetting();
            if ($phoneArr) {
                $content = 'abc';
                $content = trim($content);
                foreach ($phoneArr as $phone) {
                    $this->sendSms(1, 0, $content, $phone);
                }
            }
        }
    }

    protected function getPhoneCanhBaoSetting()
    {
        $setting = Setting::find()
            ->where(['key_value' => 'phone_sms_canh_bao'])
            ->one();

        if ($setting !== null) {
            return explode(',', $setting->value);
        }
        return false;
    }

    protected function sendSms($customerId = 1, $smsLanThu = 0, $content, $phone)
    {
        $uuid = 100;
        $status = 100;
        $url = 'http://sms3.vht.com.vn/ccsms/Sms/SMSService.svc/ccsms/json';

        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);

        try {
            $response = $client->request('POST', $url, [
                'body' => $this->createJsonSms($content, $phone)
            ]);

            $body = $response->getBody();
            $body = json_decode($body);

            foreach ($body as $key => $items) {
                foreach ($items as $keys => $values) {
                    foreach ($values as $keyss => $item) {
                        foreach ($item as $keysss => $value) {
                            $uuid = $value->id;
                            $status = $value->status;
                        }
                    }
                }
            }

            $sms = new Dep365SendSms();
            $sms->sms_uuid = $uuid;
            $sms->status = $status;
            $sms->customer_id = $customerId;
            $sms->sms_text = $content;
            $sms->sms_to = $phone;
            $sms->sms_lanthu = $smsLanThu;
            if (!$sms->save()) {
                return false;
            }
            return $status;
        } catch (ClientException $e) {
            return false;
//            return $e->getRequest();
//            return $e->getResponse();
        }
    }

    protected function createJsonSms($content, $phone)
    {
        $brandname = '';
        $api_key = '';
        $api_secret = '';

        $cache = Yii::$app->cache;
        $key = 'redis-get-vht-send-sms';
        $setting = $cache->get($key);
        if ($setting === false) {
            $setting = Setting::find()->where(['in', 'id', [1, 2, 3]])->all();
            $cache->set($key, $setting);
        }

        foreach ($setting as $value) {
            if ($value->id == 1) {
                $brandname = $value->value;
            }
            if ($value->id == 2) {
                $api_key = $value->value;
            }
            if ($value->id == 3) {
                $api_secret = $value->value;
            }
        }
        $param = [
            'submission' => [
                'api_key' => $api_key,
                'api_secret' => $api_secret,
                'sms' => [
                    [
                        'id' => '0',
                        'brandname' => $brandname,
                        'text' => $content,
                        'to' => $phone,
                    ]
                ],
            ],
        ];
        return json_encode($param);
    }
}