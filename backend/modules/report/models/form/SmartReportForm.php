<?php
/**
 * Created by PhpStorm.
 * User: luken
 * Date: 8/15/2020
 * Time: 16:56
 */

namespace backend\modules\report\models\form;

class SmartReportForm extends \yii\base\Model
{
    public $data;
    public $report_timestamp;

    public function rules()
    {
        return [
            [['data'], 'safe'],
            [['report_timestamp'], 'required'],
            [['report_timestamp'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('backend', 'ID'),
            'id_khoan_chi' => \Yii::t('backend', 'Khoản Chi'),
            'tien_da_chi' => \Yii::t('backend', 'Tiền Đã Chi'),
            'tien_cho_duyet' => \Yii::t('backend', 'Tiền Chờ Duyệt'),
            'report_timestamp' => \Yii::t('backend', 'Thời gian'),
            'status' => \Yii::t('backend', 'Status'),
        ];
    }
}