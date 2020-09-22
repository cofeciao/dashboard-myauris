<?php

namespace backend\modules\setting\models;

use backend\modules\setting\models\query\Dep365SettingSmsSendQuery;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;

/**
 * This is the model class for table "dep365_setting_sms_send".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $content
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class Dep365SettingSmsSend extends \yii\db\ActiveRecord
{
    const STATUS_PUBLISHED = 1;
    const STATUS_DRAFF = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dep365_setting_sms_send';
    }

    public static function find()
    {
        return new Dep365SettingSmsSendQuery(get_called_class());
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
            [['name', 'slug', 'content'], 'required'],
            [['content'], 'string'],
            [['status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
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
            'name' => Yii::t('backend', 'Name'),
            'slug' => Yii::t('backend', 'Slug'),
            'content' => Yii::t('backend', 'Content'),
            'status' => Yii::t('backend', 'Status'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
        ];
    }

    public static function getSettingSmsSend()
    {
        $cache = Yii::$app->cache;
        $key = 'redis-setting-sms-send';
        $result = $cache->get($key);
        if ($result === false) {
            $result = self::find()->published()->all();
            $cache->set($key, $result);
        }
        return $result;
    }

    public function getSettingSmsSendOne($id)
    {
        $cache = Yii::$app->cache;
        $key = 'redis-setting-sms-send-' . $id;
        $result = $cache->get($key);
        if ($result === false) {
            $result = self::find()->where(['id' => $id])->published()->one();
            $cache->set($key, $result);
        }
        return $result;
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
}
