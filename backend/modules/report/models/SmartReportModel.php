<?php

namespace backend\modules\report\models;

use backend\modules\report\models\query\SmartReportModelQuery;
use common\models\UserProfile;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "smart_report".
 *
 * @property int $id
 * @property int $id_khoan_chi
 * @property string $tien_da_chi
 * @property string $tien_cho_duyet
 * @property int $report_timestamp
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 */
class SmartReportModel extends \yii\db\ActiveRecord
{
    const STATUS_DISABLED = 0;
    const STATUS_PUBLISHED = 1;

    public static function tableName()
    {
        return 'smart_report';
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
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['report_timestamp'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['report_timestamp'],
                ],
                'value' => function () {
                    if (is_numeric($this->report_timestamp)) return $this->report_timestamp;
                    if (is_string($this->report_timestamp)) return strtotime('01-' . $this->report_timestamp);
                    return null;
                }
            ],
        ];
    }

    public static function find()
    {
        return new SmartReportModelQuery(get_called_class());
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['report_timestamp'], 'required'],
            [['id_khoan_chi', 'status'], 'integer'],
            [['tien_da_chi', 'tien_cho_duyet', 'report_timestamp'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'id_khoan_chi' => Yii::t('backend', 'Khoản Chi'),
            'tien_da_chi' => Yii::t('backend', 'Tiền Đã Chi'),
            'tien_cho_duyet' => Yii::t('backend', 'Tiền Chờ Duyệt'),
            'report_timestamp' => Yii::t('backend', 'Thời gian'),
            'status' => Yii::t('backend', 'Status'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
        ];
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

}
