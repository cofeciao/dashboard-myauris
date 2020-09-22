<?php

namespace backend\modules\location\models;

use Yii;

/**
 * This is the model class for table "ward".
 *
 * @property int $id
 * @property string $name
 * @property string $Type
 * @property string $LatiLongTude
 * @property int $DistrictID
 * @property int $SortOrder
 * @property int $status
 * @property int $IsDeleted
 */
class Ward extends \yii\db\ActiveRecord
{
    const WARD_PHUONG = 'Phường';
    const WARD_THITRAN = 'Thị Trấn';
    const WARD_XA = 'Xã';

    public static function tableName()
    {
        return 'ward';
    }

    public function rules()
    {
        return [
            [['name', 'DistrictID'], 'required'],
            [['DistrictID', 'SortOrder', 'status', 'IsDeleted'], 'integer'],
            [['name', 'Type', 'LatiLongTude'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'name' => Yii::t('backend', 'Ward'),
            'Type' => Yii::t('backend', 'Type'),
            'LatiLongTude' => Yii::t('backend', 'Tọa độ'),
            'DistrictID' => Yii::t('backend', 'Quận/Huyện'),
            'SortOrder' => Yii::t('backend', 'Sort Order'),
            'status' => Yii::t('backend', 'Status'),
            'IsDeleted' => Yii::t('backend', 'Is Deleted'),
        ];
    }

    public static function getWardType()
    {
        return [
            self::WARD_PHUONG => Yii::t('location', 'Phường'),
            self::WARD_THITRAN => Yii::t('location', 'Thị Trấn'),
            self::WARD_XA => Yii::t('location', 'Xã'),
        ];
    }

    public function getQuanHuyen()
    {
        return $this->hasOne(District::class, ['id' => 'DistrictID']);
    }
}
