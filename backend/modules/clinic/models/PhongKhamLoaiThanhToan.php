<?php

namespace backend\modules\clinic\models;

use backend\modules\clinic\models\query\PhongKhamLoaiThanhToanQuery;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "phong_kham_loai_thanh_toan".
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
class PhongKhamLoaiThanhToan extends \yii\db\ActiveRecord
{
    const STATUS_PUBLISHED = 1;
    const STATUS_DRAFF = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'phong_kham_loai_thanh_toan';
    }

    public static function find()
    {
        return new PhongKhamLoaiThanhToanQuery(get_called_class());
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
                //'preserveNonEmptyValues' => true,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => time(),
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
            'mota' => Yii::t('backend', 'Mota'),
            'status' => Yii::t('backend', 'Status'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
        ];
    }

    public static function getOneLTT($id)
    {
        return self::findOne($id);
    }

    public static function getClinicLoaiThanhToan()
    {
        $cache = Yii::$app->cache;
        $key = 'redis-get-clinic-loai-thanh-toan';
        $data = $cache->get($key);
        if ($data === false) {
            $data = self::find()->published()->all();
            $cache->set($key, $data, 86400 * 30);
        }
        return $data;
    }

    public function getUserCreatedBy($id)
    {
        if ($id == null) {
            return false;
        }
        $user = UserProfile::find()->where(['user_id' => $id])->one();
        return $user;
    }

    public function getUserUpdatedBy($id)
    {
        if ($id == null) {
            return false;
        }
        $user = UserProfile::find()->where(['user_id' => $id])->one();
        return $user;
    }

    public static function getListLoaiThanhToan(){
        return ArrayHelper::map(PhongKhamLoaiThanhToan::getClinicLoaiThanhToan(), 'id', 'name');
    }
}
