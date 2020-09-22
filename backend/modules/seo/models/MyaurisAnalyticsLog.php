<?php

namespace backend\modules\seo\models;

use Yii;

/**
 * This is the model class for table "myauris_analytics_log".
 *
 * @property int $id
 * @property string $from_url null - direct link
 * @property string $first_url first url customer connect
 * @property string $call_url url customer click button call
 * @property int $time
 * @property string $cookie_user_id cookie user id of customer on myauris
 * @property string $device_info
 * @property string $phone phone of customer
 */
class MyaurisAnalyticsLog extends \yii\db\ActiveRecord
{
    public $logs;
    public $logs_detail;
    public $max_time;

    public static function tableName()
    {
        return 'myauris_analytics_log';
    }

    public function behaviors()
    {
        return [
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cookie_user_id'], 'required'],
            [['time', 'created_at'], 'integer'],
            [['device_info'], 'string'],
            [['from_url', 'referer_url', 'first_url', 'event_url', 'event_name', 'cookie_user_id'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 25],
            [['logs'], 'text']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'from_url' => Yii::t('backend', 'From Url'),
            'referer_url' => Yii::t('backend', 'Referer Url'),
            'first_url' => Yii::t('backend', 'First Url'),
            'event_url' => Yii::t('backend', 'Call Url'),
            'event_name' => Yii::t('backend', 'Event Name'),
            'time' => Yii::t('backend', 'Time'),
            'cookie_user_id' => Yii::t('backend', 'Cookie User ID'),
            'device_info' => Yii::t('backend', 'Device Info'),
            'phone' => Yii::t('backend', 'Phone'),
            'created_at' => Yii::t('backend', 'Created At'),
            'logs' => Yii::t('backend', 'Logs'),
            'logs_detail' => Yii::t('backend', 'Chi tiết'),
            'max_time' => Yii::t('backend', 'Thời gian thao tác'),
        ];
    }
}
