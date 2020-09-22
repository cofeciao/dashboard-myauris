<?php

namespace backend\modules\cskh\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;
use backend\modules\cskh\models\query\CskhQuanLyQuery;

/**
 * This is the model class for table "cskh_quan_ly".
 *
 * @property int $id
 * @property int $customer_id
 * @property int $user_id
 * @property int $status
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $updated_at
 */
class CskhQuanLy extends \yii\db\ActiveRecord
{
    // const STATUS_DISABLED = 0;
    // const STATUS_PUBLISHED = 1;


    const STATUS_KHONG_LAM_PHIEN = 0;
    const STATUS_CHAM_SOC = 1;
    public static function tableName()
    {
        return 'cskh_quan_ly';
    }

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'preserveNonEmptyValues' => true,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'user_id', 'status', 'created_at', 'created_by', 'updated_by', 'updated_at'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_CHAM_SOC],
            [['status', 'user_id'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => 'Customer ID',
            'user_id' => 'CSKH phụ trách',
            'status' => 'Trạng thái',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }

    public static function getListStatusCSKH()
    {
        return [
            self::STATUS_CHAM_SOC => 'Chăm sóc',
            self::STATUS_KHONG_LAM_PHIEN => 'Không làm phiền',
        ];
    }

    public function getStatus()
    {
        $list = self::getListStatusCSKH();
        return ($list[$this->status]) ? $list[$this->status] : '';
    }

    public function getUserCreatedBy($id)
    {
        if ($id == null)
            return null;
        $user = UserProfile::find()->where(['user_id' => $id])->one();
        return $user;
    }

    public function getUserUpdatedBy($id)
    {
        if ($id == null)
            return null;
        $user = UserProfile::find()->where(['user_id' => $id])->one();
        return $user;
    }

    public function getUserCskhHasOne()
    {
        return $this->hasOne(UserProfile::class, ['user_id' => 'user_id']);
    }
}
