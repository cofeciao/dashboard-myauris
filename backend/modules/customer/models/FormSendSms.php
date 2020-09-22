<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 26-Dec-18
 * Time: 2:50 PM
 */

namespace backend\modules\customer\models;

use backend\modules\setting\models\Dep365CoSo;
use backend\modules\setting\models\Dep365SettingSmsSend;
use yii\base\Model;
use backend\modules\setting\models\Setting;
use common\helpers\MyHelper;

class FormSendSms extends Model
{
    const LAN1 = 1;
    const LAN3 = 3;
    const LAN7 = 7;
    const LANKHAC = 0;

    public $sms_text;
    public $customer_id;
    public $sms_to;
    public $sms_lanthu;

    public function rules()
    {
        return [
            [['sms_text', 'sms_lanthu'], 'required'],
            [['sms_text'], 'trim'],
            [['sms_text'], 'match', 'pattern' => '/^[^\{\}\[\]\|\\\~\^\n]+$/'],
            [['sms_text'], 'validateSmsChar'],
            [['sms_text'], 'string', 'max' => 300],
            [['customer_id', 'sms_to'], 'string'],
            [['sms_lanthu'], 'integer'],
            [['sms_lanthu'], 'checkLanSms'],
            [['customer_id'], 'integer']
        ];
    }

    public function scenarios()
    {
        $scenarios = [
            'checkSms' => ['sms_text'],
            'checkSms' => ['sms_lanthu'],
        ];

        return array_merge(parent::scenarios(), $scenarios);
    }


    public function attributeLabels()
    {
        return [
            'sms_text' => \Yii::t('frontend', 'Nội dung tin nhắn'),
            'sms_lanthu' => 'Lần nhắn tin'
        ];
    }

    public function checkLanSms($attribute, $params)
    {
        if ($this->sms_lanthu == 0) {
            return true;
        }
        $today = strtotime(date("d-m-Y"));
        $customer = Dep365CustomerOnline::findOne($this->customer_id);
        if ($customer->province == 97) {
            return false;
        }

        $dateTime = $customer->time_lichhen;
        $date = date('d-m-Y', $dateTime);
        $date = strtotime($date);
        $day = ($date - $today) / 86400;
//        $day = $day == 0 ? 1 : $day;

        if ($this->sms_lanthu == $day) {
            $query = Dep365SendSms::find()->where(['sms_lanthu' => $this->sms_lanthu])->andWhere(['status' => 0]);
            $query->andWhere(['customer_id' => $this->customer_id]);
            $query->andWhere(['between', 'created_at', strtotime(date('d-m-Y')), strtotime(date('d-m-Y')) + 86399]);

            $smsExit = $query->one();
            if (!$smsExit) {
                return true;
            } else {
                $this->addError($attribute, 'Bạn đã gửi loại tin này.');
            }
        }

        if ($this->sms_lanthu > $day) {
            $this->addError($attribute, 'Đã quá thời gian gửi tin.');
        }
        if ($this->sms_lanthu < $day) {
            $this->addError($attribute, 'Chưa đến thời gian gửi tin.');
        }
    }

    public function validateSmsChar($attribute, $params)
    {
        $str = strtolower(MyHelper::smsKhongDau($this->sms_text));
        $data = Setting::findOne(4);
        $keyWord = explode(',', $data->value);

        foreach ($keyWord as $item) {
            $word = strtolower($item);
            if (MyHelper::containsWord($str, $word)) {
                $this->addError($attribute, 'Có chứa ký tự đặc biệt: ' . $word);
            }
        }
    }

    public static function getLanSms()
    {
        $result = [];
        $data = Dep365SettingSmsSend::getSettingSmsSend();
        foreach ($data as $item) {
            $result[$item->gia_tri] = $item->name;
        }
        return $result;
    }
}
