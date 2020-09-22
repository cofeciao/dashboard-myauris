<?php

namespace backend\modules\clinic\models;

use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use Yii;

class LichDieuTri extends PhongKhamLichDieuTri
{
    const SCENARIO_DIEU_TRI = 'dieu-tri';
    const SCENARIO_TAI_KHAM = 'tai-kham';

    public $type;

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_VALIDATE => ['type'],
                ],
                'value' => function () {
                    if ($this->scenario == self::SCENARIO_TAI_KHAM) return self::SCENARIO_TAI_KHAM;
                    return $this->type;
                }
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['time_dieu_tri'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['time_dieu_tri'],
                ],
                'value' => function () {
                    if (is_numeric($this->time_dieu_tri)) return $this->time_dieu_tri;
                    if (is_string($this->time_dieu_tri)) return strtotime($this->time_dieu_tri);
                    return time();
                }
            ],
        ]);
    }

    public function rules()
    {
        return [
            [['customer_id', 'customer_code', 'order_code', 'time_dieu_tri'], 'required'],
            [['id_list_chuphinh', 'ekip', 'tro_thu', 'room_id', 'thao_tac'], 'required', 'on' => self::SCENARIO_DIEU_TRI],
            [['ekip', 'last_dieu_tri', 'id_list_chuphinh'], 'integer', 'on' => self::SCENARIO_DIEU_TRI],
            [['huong_dieu_tri', 'note'], 'string', 'on' => self::SCENARIO_DIEU_TRI],
            [['time_end'], 'validateTimeEnd', 'on' => self::SCENARIO_DIEU_TRI],
            [['time_start', 'time_end'], 'safe', 'on' => self::SCENARIO_TAI_KHAM],
            [['tai_kham'], 'required', 'on' => self::SCENARIO_TAI_KHAM],
            [['tai_kham'], 'safe', 'on' => self::SCENARIO_TAI_KHAM],
            [['tai_kham'], 'exist', 'targetClass' => PhongKhamLichDieuTri::class, 'targetAttribute' => 'id', 'on' => self::SCENARIO_TAI_KHAM],
            [['time_end'], 'validateTimeEnd', 'on' => self::SCENARIO_TIMEEND],
            [['customer_id'], 'integer'],
            [['customer_code', 'order_code'], 'string', 'max' => 25],
            [['type'], 'string', 'max' => 255]
        ];
    }


    public function beforeSave($insert)
    {
        // if ($this->last_dieu_tri == 1) {
        //     $donHang = PhongKhamDonHang::findOne($this->order_code);
        //     $donHang->trang_thai_dich_vu = PhongKhamDonHang::HOAN_THANH_DICH_VU;
        //     $donHang->save();
        // }
        // return parent::beforeSave($insert);
    }

    public function countLichDieuTriCuoi()
    {
        return self::find()->where(['tai_kham' => NULL, 'last_dieu_tri' => 1])->exist();
    }
}
