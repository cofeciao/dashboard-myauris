<?php

namespace backend\modules\customer\models;

use backend\modules\customer\models\query\Dep365CustomerOnlineFailDathenQuery;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;

/**
 * This is the model class for table "dep365_customer_online_fail_dathen".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $mota
 * @property int $status
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 */
class Dep365CustomerOnlineFailDathen extends \yii\db\ActiveRecord
{
    const STATUS_PUBLISHED = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dep365_customer_online_fail_dathen';
    }

    public static function find()
    {
        return new Dep365CustomerOnlineFailDathenQuery(get_called_class());
    }

    public function behaviors()
    {
        return [
            'slug' => [
                'class' => SluggableBehavior::class,
                'attribute' => 'name',
                'slugAttribute' => 'slug',
                'immutable' => true, //only create 1
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
            [['name'], 'required'],
            [['mota'], 'string'],
            [['status'], 'integer'],
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
            'mota' => Yii::t('backend', 'Mô tả'),
            'status' => Yii::t('backend', 'Status'),
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

    public static function getCustomerOnlineDatHenFail()
    {
        $cache = Yii::$app->cache;
        $key = 'redis-get-customer-online-dat-hen-fail';
        $data = $cache->get($key);
        if ($data === false) {
            $data = self::find()->published()->all();
            $cache->set($key, $data);
        }
        return $data;
    }

    public static function getCustomerOnlineDatHenFailArray()
    {
        $locations = self::getCustomerOnlineDatHenFail();
        $data = [];
        foreach ($locations as $location) {
            $data[$location->id] = $location->name;
        }
        return $data;
    }
}
