<?php

namespace backend\modules\api\modules\v1\models;

use backend\modules\seo\models\MyaurisAnalyticsLog;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

class SeoModel extends MyaurisAnalyticsLog
{
    const SCENARIO_CONNECT = 'connect';
    const SCENARIO_CALL = 'call';

    public function rules()
    {
        return [
            [['first_url'], 'required', 'on' => self::SCENARIO_CONNECT],
            [['event_url', 'event_name'], 'required', 'on' => self::SCENARIO_CALL],
            [['from_url', 'referer_url', 'first_url', 'event_url', 'event_name'], 'string', 'max' => 255],
            [['time', 'created_at'], 'integer'],
            [['cookie_user_id'], 'required'],
            [['cookie_user_id'], 'string', 'max' => 255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'from_url' => \Yii::t('backend', 'Nguồn truy cập'),
            'referer_url' => \Yii::t('backend', 'Url nguồn truy cập'),
            'first_url' => \Yii::t('backend', 'Trang đầu truy cập'),
            'event_url' => \Yii::t('backend', 'Trang xử lý event'),
            'event_name' => \Yii::t('backend', 'Tên event'),
            'time' => \Yii::t('backend', 'Thời gian'),
            'cookie_user_id' => \Yii::t('backend', 'Cookie User ID'),
            'created_at' => \Yii::t('backend', 'Created At'),
        ];
    }
}
