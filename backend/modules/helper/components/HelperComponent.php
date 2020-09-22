<?php

namespace backend\modules\helper\components;

use backend\modules\customer\models\Dep365SendSms;
use backend\modules\setting\models\Setting;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Yii;
use yii\base\Component;

class HelperComponent extends Component
{
    public static function sendSms($customerId, $smsLanThu, $content, $phone)
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
                'body' => self::createJsonSms($content, $phone)
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
            $sms->sms_time_send = null;
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

    protected static function createJsonSms($content, $phone)
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

    public static function smsErrorStatus($status)
    {
        switch ($status) {
            case 0:
                $result = 'Thành công';
                break;
            case 2:
                $result = 'lỗi hệ thống';
                break;
            case 3:
                $result = 'Sai user hoặc mật khẩu';
                break;
            case 4:
                $result = 'Ip không được phép';
                break;
            case 5:
                $result = 'Chưa khai báo brandname/dịch vụ';
                break;
            case 6:
                $result = 'Lặp nội dung';
                break;
            case 7:
                $result = 'Thuê bao từ chối nhận tin';
                break;
            case 8:
                $result = 'Không được phép gửi tin';
                break;
            case 9:
                $result = 'Chưa khai báo template';
                break;
            case 10:
                $result = 'Định dạng thuê bao không đúng';
                break;
            case 11:
                $result = 'Có tham số không hợp lệ';
                break;
            case 12:
                $result = 'Tài khoản không đúng';
                break;
            case 13:
                $result = 'Gửi tin: lỗi kết nối';
                break;
            case 14:
                $result = 'Gửi tin: lỗi kết nối';
                break;
            case 15:
                $result = 'Tài khoản hết hạn';
                break;
            case 16:
                $result = 'Hết hạn dịch vụ';
                break;
            case 17:
                $result = 'Hết hạn mức gửi test';
                break;
            case 18:
                $result = 'Hủy gửi tin (CSKH)';
                break;
            case 19:
                $result = 'Hủy gửi tin (KD)';
                break;
            case 20:
                $result = 'Gateway chưa hỗ trợ Unicode';
                break;
            case 21:
                $result = 'Chưa set giá trả trước';
                break;
            case 22:
                $result = 'Tài khoản chưa kích hoạt';
                break;
            case 25:
                $result = 'Chưa khai báo partner cho user';
                break;
            case 26:
                $result = 'Chưa khai báo GateOwner cho user';
                break;
            case 27:
                $result = 'Gửi tin: gate trả mã lỗi';
                break;
            case 31:
                $result = 'Bạn không thể gửi tin tới số điện thoại 11 số';
                break;
            default:
                $result = 'Hãy liên hệ lập trình viên';
                break;
        }
        return $result;
    }
}
