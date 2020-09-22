<?php

namespace backend\modules\clinic\models;

use backend\modules\clinic\models\query\PhongKhamKpiQuery;
use backend\modules\customer\models\Dep365CustomerOnlineDichVu;
use common\models\UserProfile;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "phong_kham_kpi".
 *
 * @property int $id
 * @property int $kpi_tuong_tac
 * @property int $kpi_lich_hen
 * @property int $kpi_lich_moi
 * @property int $kpi_khach_den
 * @property int $kpi_khach_lam
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 */
class PhongKhamKpi extends \yii\db\ActiveRecord
{
    const STATUS_DISABLED = 0;
    const STATUS_PUBLISHED = 1;

    public static function tableName()
    {
        return 'phong_kham_kpi';
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['kpi_time'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['kpi_time'],
                ],
                'value' => function () {
                    if ($this->kpi_time == null) {
                        return null;
                    }
                    return strtotime('01-' . $this->kpi_time);
                }
            ],
        ];
    }

    public static function find()
    {
        return new PhongKhamKpiQuery(get_called_class());
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kpi_tuong_tac', 'kpi_lich_hen', 'kpi_lich_moi', 'kpi_khach_den', 'kpi_khach_lam', 'kpi_time', 'id_dich_vu'], 'required'],
            [['status'], 'integer'],
            [['kpi_tuong_tac', 'kpi_lich_hen', 'kpi_lich_moi', 'kpi_khach_den', 'kpi_khach_lam', 'kpi_time'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'kpi_tuong_tac' => Yii::t('backend', 'Kpi tương tác'),
            'kpi_lich_hen' => Yii::t('backend', 'Kpi lịch hẹn'),
            'kpi_lich_moi' => Yii::t('backend', 'Kpi lịch mới'),
            'kpi_khach_den' => Yii::t('backend', 'Kpi khách đến'),
            'kpi_khach_lam' => Yii::t('backend', 'Kpi khách làm'),
            'kpi_time' => Yii::t('backend', 'Thời gian'),
            'id_dich_vu' => Yii::t('backend', 'Dịch vụ'),
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

    public function getDichVuOnlineHasOne()
    {
        return $this->hasOne(Dep365CustomerOnlineDichVu::class, ['id' => 'id_dich_vu']);
    }
}
