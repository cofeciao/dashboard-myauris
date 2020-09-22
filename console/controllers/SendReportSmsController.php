<?php

namespace console\controllers;

use backend\components\MtSmsComponent;
use backend\models\SiteModel;
use backend\modules\customer\models\CustomerToken;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\setting\models\Dep365CoSo;
use backend\modules\setting\models\Dep365SettingSmsSend;
use common\helpers\MyHelper;
use common\models\UserProfile;
use console\models\CustomerSms;
use Yii;
use backend\modules\customer\models\Dep365SendSms;
use backend\modules\setting\models\Setting;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use yii\console\Controller;
use yii\db\Exception;

class SendReportSmsController extends Controller
{
    public function actionIndex()
    {
        return false;
    }

    public function actionAutoSendSms()
    {
        set_time_limit(1200);
        ini_set("log_errors", 1);
        ini_set("error_log", "error.log");

        //lấy ra toàn bộ khách hàng có lịch hẹn là ngày mai và gửi sms
        $tommorow = 1;
        $dataTommorowSendSms = CustomerSms::getCustomerSendSms($tommorow);
        foreach ($dataTommorowSendSms as $key => $value) {
            $coso = new Dep365CoSo();
            $coso = $coso->getCoSoOne($value->co_so);
            if ($coso == null) {
                return [
                    'status' => 403,
                    'text' => 'Chưa có dữ liệu',
                ];
            }
//            $address = $coso->address;
//
//            $nameCustomer = $value->forename == null ? $value->name : $value->forename;
//            $nameCustomer = mb_convert_case($nameCustomer, MB_CASE_TITLE, "UTF-8");

//            $customer = MyHelper::smsKhongDau($nameCustomer);
//            $time = date('H:i', $value->time_lichhen);
//            $date = date('d-m-Y', $value->time_lichhen);
//
//            $employees = MyHelper::smsKhongDau(UserProfile::find()->where(['user_id' => $value->created_by])->one()->fullname);
//            $sex = $value->sex == 0 ? 'chi' : 'anh';

//            $smsSend = new Dep365SettingSmsSend();
//            $smsChar = $smsSend->getSettingSmsSendOne($tommorow)->content;
//
//            $smsChar = str_replace('{$address}', $address, $smsChar);
//            $smsChar = str_replace('{$customer}', $customer, $smsChar);
//            $smsChar = str_replace('{$time}', $time, $smsChar);
//            $smsChar = str_replace('{$date}', $date, $smsChar);
//            $smsChar = str_replace('{$employees}', $employees, $smsChar);
//            $smsChar = str_replace('{$sex}', $sex, $smsChar);
//            $this->sendSms($value->id, $tommorow, $smsChar, $value->phone);
            $this->sendSms($value->id, $tommorow);
        }

        //lấy ra toàn bộ khách hàng có lịch hẹn cách 3 ngày sau và gửi sms
        $tommorow3 = 3;
        $dataTommorowSendSms = CustomerSms::getCustomerSendSms($tommorow3);
        foreach ($dataTommorowSendSms as $key => $value) {
            $coso = new Dep365CoSo();
            $coso = $coso->getCoSoOne($value->co_so);
            if ($coso == null) {
                return [
                    'status' => 403,
                    'text' => 'Chưa có dữ liệu',
                ];
            }
//            $address = $coso->address;

//            $nameCustomer = $value->forename == null ? $value->name : $value->forename;
//            $nameCustomer = mb_convert_case($nameCustomer, MB_CASE_TITLE, "UTF-8");
//
//            $customer = MyHelper::smsKhongDau($nameCustomer);
//            $time = date('H:i', $value->time_lichhen);
//            $date = date('d-m-Y', $value->time_lichhen);
//
//            $employees = MyHelper::smsKhongDau(UserProfile::find()->where(['user_id' => $value->created_by])->one()->fullname);
//            $sex = $value->sex == 0 ? 'chi' : 'anh';
//
//            $smsSend = new Dep365SettingSmsSend();
//            $smsChar = $smsSend->getSettingSmsSendOne($tommorow3)->content;
//
//            $smsChar = str_replace('{$address}', $address, $smsChar);
//            $smsChar = str_replace('{$customer}', $customer, $smsChar);
//            $smsChar = str_replace('{$time}', $time, $smsChar);
//            $smsChar = str_replace('{$date}', $date, $smsChar);
//            $smsChar = str_replace('{$employees}', $employees, $smsChar);
//            $smsChar = str_replace('{$sex}', $sex, $smsChar);
//            $this->sendSms($value->id, $tommorow3, $smsChar, $value->phone);
            $this->sendSms($value->id, $tommorow3);
        }

        //lấy ra toàn bộ khách hàng có lịch hẹn cách 7 ngày sau và gửi sms
        $tommorow7 = 7;
        $dataTommorowSendSms = CustomerSms::getCustomerSendSms($tommorow7);
        foreach ($dataTommorowSendSms as $key => $value) {
            $coso = new Dep365CoSo();
            $coso = $coso->getCoSoOne($value->co_so);
            if ($coso == null) {
                return [
                    'status' => 403,
                    'text' => 'Chưa có dữ liệu',
                ];
            }
//            $address = $coso->address;
//
//            $nameCustomer = $value->forename == null ? $value->name : $value->forename;
//            $nameCustomer = mb_convert_case($nameCustomer, MB_CASE_TITLE, "UTF-8");
//
//            $customer = MyHelper::smsKhongDau($nameCustomer);
//            $time = date('H:i', $value->time_lichhen);
//            $date = date('d-m-Y', $value->time_lichhen);
//
//            $employees = MyHelper::smsKhongDau(UserProfile::find()->where(['user_id' => $value->created_by])->one()->fullname);
//            $sex = $value->sex == 0 ? 'chi' : 'anh';
//
//            $smsSend = new Dep365SettingSmsSend();
//            $smsChar = $smsSend->getSettingSmsSendOne($tommorow7)->content;
//
//            $smsChar = str_replace('{$address}', $address, $smsChar);
//            $smsChar = str_replace('{$customer}', $customer, $smsChar);
//            $smsChar = str_replace('{$time}', $time, $smsChar);
//            $smsChar = str_replace('{$date}', $date, $smsChar);
//            $smsChar = str_replace('{$employees}', $employees, $smsChar);
//            $smsChar = str_replace('{$sex}', $sex, $smsChar);
//            $this->sendSms($value->id, $tommorow7, $smsChar, $value->phone);
            $this->sendSms($value->id, $tommorow7);
        }
    }

    public function actionSendSmsDanhGiaCuaKhachHang()
    {
        return true;
        set_time_limit(1200);
        ini_set("log_errors", 1);
        ini_set("error_log", "error.log");
        $content = Setting::find()->where(['key_value' => 'sms_danh_gia_cua_khach_hang'])->one()->value;
        $time = time();
        $customerToken = CustomerToken::find()
            ->joinWith(['customerHasOne'])
            ->where([
                'type' => CustomerToken::TYPE_CUSTOMER_FEEDBACK,
                CustomerToken::tableName() . '.status' => CustomerToken::STATUS_DISABLED
            ])
            ->andWhere($time . '-customer_token.created_at>7200')
            ->andWhere('expired_at IS NULL OR expired_at>' . $time)
            ->all();
        foreach ($customerToken as $token) {
            $sex = array_key_exists($token->customerHasOne->sex, Dep365CustomerOnline::getSex()) ? [
                Dep365CustomerOnline::SEX_WOMAN => 'Chi',
                Dep365CustomerOnline::SEX_MAN => 'Anh',
            ][$token->customerHasOne->sex] : '';
            if ($token->customerHasOne->full_name != null) {
                $customer = $token->customerHasOne->full_name;
            } elseif ($token->customerHasOne->forename != null) {
                $customer = $token->customerHasOne->forename;
            } else {
                $customer = $token->customerHasOne->full_name;
            }
            $customer = MyHelper::smsKhongDau($customer);
            $link = 'https://myauris.vn/feedback/' . $token->token;
            $sms = str_replace('{$sex}', $sex, $content);
            $sms = str_replace('{$customer}', $customer, $sms);
            $sms = str_replace('{$link}', $link, $sms);
            $status = $this->sendSms($token->customerHasOne->primaryKey, 0, $sms, $token->customerHasOne->phone);
            if ($status === 0 || $status === 6) {
                $token->updateAttributes([
                    'status' => CustomerToken::STATUS_PUBLISHED
                ]);
            }
        }
    }

    public function actionSendReportSms()
    {
        set_time_limit(1200);
        ini_set("log_errors", 1);
        ini_set("error_log", "error.log");
        $phoneArr = $this->getPhoneSetting();
        if ($phoneArr) {
            $content = 'BC ' . date('d-m-Y', time() - 86400) . ': ';
            $day = date('d-m-Y', time() - 86400);
            $siteModel = new SiteModel();
            $phoneNew = $siteModel->newPhone($day) ?: 0;
            $callSuccess = $siteModel->callSuccess($day) ?: 0;
            $lichDHFromKHnew = $siteModel->khachDH($day) ?: 0;
            $lichDHFromKHOld = $siteModel->khachOldFailToDatHen($day) ?: 0;
            $totalDatHen = $lichDHFromKHnew + $lichDHFromKHOld;
            $content .= 'SDT moi: ' . $phoneNew . ', goi duoc: ' . $callSuccess . ' --- Lich moi: ' . $lichDHFromKHnew . ', Lich cu: ' . $lichDHFromKHOld . ' --- TONG LICH: ' . $totalDatHen . ', ';


            $data = $siteModel->khachDatHenWithCoSo($day);
            foreach ($data as $item) {
                $content .= 'CS' . $item['name'] . ': ' . $item['numberCS'] . ', ';
            }

            $content = trim($content);
            $content = rtrim($content, ',');

            foreach ($phoneArr as $phone) {
                $this->sendSms(1, 0, $content, $phone);
            }
        }
    }

    protected function sendSms($customerId = 1, $smsLanThu = 0, $content = null, $phone = null)
    {
        if ($content == null && $phone == null) {
            $customerModel = new Dep365CustomerOnline();
            $customer = $customerModel->getById($customerId);
            if ($customer == null) return false;
            $nameCustomer = $customer->forename == null ? $customer->name : $customer->forename;
            $nameCustomer = mb_convert_case($nameCustomer, MB_CASE_TITLE, "UTF-8");
            $time = date('H:i', $customer->time_lichhen);
            $date = date('d-m-Y', $customer->time_lichhen);
            $phone = $customer->phone;
            $mtSms = new MtSmsComponent([
                'data' => [
                    'name' => $nameCustomer,
                    'sex' => $customer->sex,
                    'phone' => $phone,
                    'date' => $date,
                    'time' => $time
                ]
            ]);
        } else {
            $mtSms = new MtSmsComponent([
                'data' => [
                    'phone' => $phone
                ]
            ]);
            $mtSms->setMsgContent($content);
        }
        if ($mtSms->sendSms()) {
            try {
                $sms = new Dep365SendSms([
                    'sms_uuid' => 0,
                    'status' => $mtSms->getStatusCode(),
                    'customer_id' => $customerId,
                    'sms_text' => $mtSms->getMsgContent(),
                    'sms_to' => $phone,
                    'sms_lanthu' => $smsLanThu,
                    'type' => 'mtsms_vht'
                ]);
                $sms->save();
                return $mtSms->getStatusCode();
            } catch (Exception $ex) {
                Yii::warning('Log sms send failed: ' . $ex->getMessage());
                return false;
            }
        } else {
            Yii::warning('Send sms error ' . $mtSms->getStatusCode() . ': ' . $mtSms->getMessage());
            return false;
        }
        /*$uuid = 100;
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
        }*/
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

    protected function getPhoneSetting()
    {
        $setting = Setting::find()
            ->where(['key_value' => 'phone_sms'])
            ->one();

        if ($setting !== null) {
            return explode(',', $setting->value);
        }
        return false;
    }
}
