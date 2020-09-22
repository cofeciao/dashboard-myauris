<?php


namespace backend\modules\helper\models;

use backend\modules\setting\models\Setting;
use common\helpers\MyHelper;
use yii\base\Model;

class SendSmsCustomer extends Model
{
    public $date_create_from;
    public $date_create_to;
    public $date_dathen_from;
    public $date_dathen_to;

    public $status;
    public $den_or_khong_den;

    public $who_create;

    public $content;

    public function rules()
    {
        return [
            [['date_create_from', 'date_create_to', 'date_dathen_from', 'date_dathen_to'], 'safe'],
            ['date_create_to', 'required', 'when' => function ($model) {
                return $model->date_create_from != null;
            }, 'whenClient' => "function (attribute, value) {
                return $('#sendsmscustomer-date_create_from').val() != '';
            }"],
            ['date_create_from', 'required', 'when' => function ($model) {
                return $model->date_create_to != null;
            }, 'whenClient' => "function (attribute, value) {
                return $('#sendsmscustomer-date_create_to').val() != '';
            }"],
            ['date_dathen_from', 'required', 'when' => function ($model) {
                return $model->date_dathen_to != null;
            }, 'whenClient' => "function (attribute, value) {
                return $('#sendsmscustomer-date_dathen_to').val() != '';
            }"],
            ['date_dathen_to', 'required', 'when' => function ($model) {
                return $model->date_dathen_from != null;
            }, 'whenClient' => "function (attribute, value) {
                return $('#sendsmscustomer-date_dathen_from').val() != '';
            }"],
            [['status', 'den_or_khong_den', 'who_create'], 'integer'],
            [['content'], 'required'],
            [['content'], 'trim'],
            [['content'], 'match', 'pattern' => '/^[^\{\}\[\]\|\\\~\^\n]+$/'],
            [['content'], 'validateSmsChar'],
            [['content'], 'string', 'max' => 300],
        ];
    }

    public function attributeLabels()
    {
        return [
            'content' => \Yii::t('frontend', 'Nội dung tin nhắn'),
            'date_create_to' => 'Đến ngày',
            'date_create_from' => 'Từ ngày',
            'date_dathen_to' => 'Đến ngày',
            'date_dathen_from' => 'Từ ngày'
        ];
    }

    public function validateSmsChar($attribute, $params)
    {
        $str = strtolower(MyHelper::smsKhongDau($this->content));
        $data = Setting::findOne(4);
        $keyWord = explode(',', $data->value);

        foreach ($keyWord as $item) {
            $word = strtolower($item);
            if (MyHelper::containsWord($str, $word)) {
                $this->addError($attribute, 'Có chứa ký tự đặc biệt: ' . $word);
            }
        }
    }
}
