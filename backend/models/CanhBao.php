<?php

namespace backend\models;

use common\models\UserProfile;
use DateTime;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "canh_bao".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $type
 * @property string $description
 * @property int $status
 * @property int $created_at
 */
class CanhBao extends ActiveRecord
{
    const KHACH_HANG_DANH_GIA = 1;

    const DIRECT_SALE_CHOT_FAIL = 2;
    const DIRECT_SALE_CHOT_THANH_CONG = 3;

    const TY_LE_CHOT_DUOI_48 = 4;
    const TY_LE_CHOT_TREN_65 = 5;

    const TY_LE_LICH_TUONG_TAC_TREN_12 = 6;
    const TY_LE_LICH_TUONG_TAC_DƯƠI_10 = 18;

    const DOI_ONLINE_TY_LE_TB_DUOI_8 = 7;
    const DOI_ONLINE_TY_LE_TB_TREN_11 = 8;

    const DIREC_SALE_CHOT_TB_DUOI_48 = 9;
    const DIREC_SALE_CHOT_TB_TREM_65 = 10;

    const TY_LE_DEN_LICH_HEN_DUOI_50 = 11;
    const TY_LE_DEN_LICH_HEN_TREN_70 = 12;

    const GIA_TB_TUONG_TAC_TREN_200 = 13;
    const GIA_TB_TUONG_TAC_DUOI_120 = 14;

    const LAST_UPDATE = 15;
    const CHOT_FAIL = 16;
    const CHOT_DONE = 17;
    const AVG_DONG_Y = 19;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'canh_bao';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'preserveNonEmptyValues' => true,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'created_by',

            ],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type'], 'required'],
            [['type', 'date', 'user_id', 'parent_id', 'status', 'created_at', 'created_by'], 'integer'],
            [['description'], 'string', 'max' => 255]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'type' => Yii::t('backend', 'Type'),
            'user_id' => Yii::t('backend', 'User Id'),
            'parent_id' => Yii::t('backend', 'Parent Id'),
            'date' => Yii::t('backend', 'Date'),
            'description' => Yii::t('backend', 'Description'),
            'status' => Yii::t('backend', 'Status'),
            'created_by' => Yii::t('backend', 'Created By'),
            'created_at' => Yii::t('backend', 'Created At'),
        ];
    }

    public static function lastUpdate($warningSave = false)
    {
        $last_update = CanhBao::findOne(['type' => CanhBao::LAST_UPDATE]);
        $begin = strtotime(date('1-8-Y'));
        if (!empty($last_update)) {
            $begin = $last_update->date;
        }
        if ($warningSave == true) {
            if (!empty($last_update)) {
                $last_update->date = time();
                $last_update->update();
            } else {
                $last_update = new CanhBao();
                $last_update->type = CanhBao::LAST_UPDATE;
                $last_update->date = time();
                $last_update->save();
            }
        }
        return $begin;
    }

    public static function getCustomerInfo($id)
    {
        $data = [];
        $customer = Customer::find()->select(['full_name', 'name', 'phone'])->where(['id' => $id])->one();
        if (isset($customer)) {
            if (isset($customer->name)) {
                $data['name'] = $customer->name;
            } else {
                $data['name'] = $customer->full_name;
            }
            $data['phone'] = $customer->phone;
        }
        return $data;
    }
}
