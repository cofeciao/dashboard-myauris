<?php


namespace backend\modules\api\modules\v1\controllers;


use backend\components\MtSmsComponent;
use backend\modules\api\components\RestController;
use backend\modules\log\models\Dep365SendSms;
use yii\db\Exception;

class SmsController extends RestController
{
    public $modelClass = Dep365SendSms::class;

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [

        ]);
    }

    public function actionSendSmsCoupon()
    {
        $phone = \Yii::$app->request->post('phone');
        $coupon_code = \Yii::$app->request->post('coupon_code');
        $coupon_value = \Yii::$app->request->post('coupon_value');
        $coupon_expired = \Yii::$app->request->post('coupon_expired');
        $mtSms = new MtSmsComponent([
            'type' => MtSmsComponent::TYPE_COUPON,
            'data' => [
                'phone' => $phone,
                'coupon_code' => $coupon_code,
                'coupon_value' => $coupon_value,
                'coupon_expired' => $coupon_expired,
            ]
        ]);
        $sendSms = $mtSms->sendSms();
        if($sendSms) {
            try {
                $sms = new Dep365SendSms([
                    'sms_uuid' => 0,
                    'status' => $mtSms->getStatusCode(),
                    'customer_id' => 1,
                    'sms_text' => $mtSms->getMsgContent(),
                    'sms_to' => $phone,
                    'sms_time_send' => null,
                    'sms_lanthu' => 1,
                    'type' => 'mtsms_vht'
                ]);
                $sms->save();
            } catch (Exception $ex){
            }
        }
        return [
            'code' => $mtSms->getStatusCode() == 1 ? 200 : 403,
            'msgCode' => $mtSms->getStatusCode(),
            'msg' => $mtSms->getMessage()
        ];
    }
}