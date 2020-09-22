<?php

namespace backend\modules\customer\models;

use backend\modules\customer\models\query\Dep365CustomerOnlineNguonQuery;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;

/**
 * This is the model class for table "dep365_customer_online_fail_status".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $mota
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class Dep365CustomerOnlineFailStatus extends \yii\db\ActiveRecord
{
    const STATUS_PUBLISHED = 1;
    const STATUS_DRAFF = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dep365_customer_online_fail_status';
    }

    public static function find()
    {
        return new Dep365CustomerOnlineNguonQuery(get_called_class());
    }

    public function behaviors()
    {
        return [
            'slug' => [
                'class' => SluggableBehavior::class,
                'attribute' => 'name',
                'slugAttribute' => 'slug',
                'immutable' => false, //only create 1
                'ensureUnique' => true, //
            ],
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
            [['name', 'slug'], 'required'],
            [['mota'], 'string'],
            [['status'], 'integer'],
            [['time_nhaclich'], 'integer', 'min' => 0],
            [['name', 'slug'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'name' => Yii::t('backend', 'Lý do Fail'),
            'slug' => Yii::t('backend', 'Slug'),
            'mota' => Yii::t('backend', 'Mota'),
            'time_nhaclich' => Yii::t('backend', 'Nhắc lịch sau (...) ngày'),
            'status' => Yii::t('backend', 'Status'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
        ];
    }

    public static function getCustomerOnlineStatusFail()
    {
        $cache = Yii::$app->cache;
        $key = 'redis-get-customer-online-status-fail';
        $data = $cache->get($key);
        if ($data === false) {
            $data = self::find()->published()->all();
            $cache->set($key, $data);
        }
        return $data;
    }

    public function getUserCreatedBy($id)
    {
        $user = UserProfile::find()->where(['user_id' => $id])->one();
        return $user;
    }

    public function getUserUpdatedBy($id)
    {
        $user = UserProfile::find()->where(['user_id' => $id])->one();
        return $user;
    }

    public static function getListOnlineStatusFailArray()
    {
        $data = self::getCustomerOnlineStatusFail();
        $result = [];
        foreach ($data as $key => $item) {
            $result[$item->id] = $item->name;
        }
        return $result;
    }
}
