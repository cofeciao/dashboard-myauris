<?php

namespace backend\modules\booking\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;
use backend\modules\booking\models\query\TimeWorkQuery;

/**
 * This is the model class for table "dep365_time_work".
 *
 * @property int $id
 * @property string $time
 * @property int $status
 * @property int $sort
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 */
class TimeWork extends \yii\db\ActiveRecord
{
    const STATUS_PUBLISHED = 1;
    const TIME_START = '08:00';
    const TIME_END = '18:01';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dep365_time_work';
    }

    public static function find()
    {
        return new TimeWorkQuery(get_called_class());
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
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['time', 'name'], 'required'],
            [['time', 'name'], 'unique', 'filter' => function ($query) {
                $query->andWhere("id<>'{$this->getPrimaryKey()}'");
            }],
            [['status', 'sort', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['time'], 'string', 'max' => 100],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'name' => Yii::t('backend', 'Thời gian làm việc'),
            'time' => Yii::t('backend', 'Giờ bắt đầu'),
            'status' => Yii::t('backend', 'Status'),
            'sort' => Yii::t('backend', 'Thứ tự'),
            'created_at' => Yii::t('backend', 'Created At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'updated_by' => Yii::t('backend', 'Updated By'),
        ];
    }

    public function getUserCreatedBy($id)
    {
        if ($id == null) {
            return null;
        }
        $user = UserProfile::find()->where(['user_id' => $id])->one();
        return $user;
    }

    public function getUserUpdatedBy($id)
    {
        if ($id == null) {
            return null;
        }
        $user = UserProfile::find()->where(['user_id' => $id])->one();
        return $user;
    }

    public function getById($id)
    {
        if ($id == null) {
            return null;
        }
        return self::find()->where(['id' => $id])->published()->one();
    }

    public static function getTimeWork()
    {
        $cache = Yii::$app->cache;
        $key = 'redis-get-time-work';
        $data = $cache->get($key);
        if ($data === false) {
            $data = self::find()->published()->orderBy(['sort' => SORT_ASC])->all();
            $cache->set($key, $data);
        }
        return $data;
    }

    public static function getTimeWorkArray()
    {
        $timework = [];
        $list = self::getTimeWork();
        foreach ($list as $time) {
            $timework[$time->id] = $time->name;
        }
        return $timework;
    }
}
