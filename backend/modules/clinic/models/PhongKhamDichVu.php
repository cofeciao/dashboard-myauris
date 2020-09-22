<?php

namespace backend\modules\clinic\models;

use backend\modules\clinic\models\query\PhongKhamDichVuQuery;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "phong_kham_dich_vu".
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
class PhongKhamDichVu extends \yii\db\ActiveRecord
{
    const STATUS_PUBLISHED = 1;
    const STATUS_DRAFF = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'phong_kham_dich_vu';
    }

    public static function find()
    {
        return new PhongKhamDichVuQuery(get_called_class());
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
            [['name'], 'filter', 'filter' => 'trim'],
            ['name', 'unique',
                'targetClass' => '\backend\modules\clinic\models\PhongKhamSanPham',
                'message' => 'Tên đã tồn tại',
                'filter' => function ($query) {
                    $query->andWhere(['not', ['id' => $this->getId()]]);
                },
            ],
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
            'name' => Yii::t('backend', 'Dịch vụ'),
            'slug' => Yii::t('backend', 'Slug'),
            'mota' => Yii::t('backend', 'Mota'),
            'status' => Yii::t('backend', 'Status'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
        ];
    }

    public static function getOneDichVu($id)
    {
        return self::findOne($id);
    }

    public static function getDichVu()
    {
        $cache = Yii::$app->cache;
        $key = 'redis-get-dich-vu';
        $data = $cache->get($key);
        if ($data === false) {
            $data = self::find()->published()->all();
            $cache->set($key, $data, 86399 * 30);
        }
        return $data;
    }

    public static function getListServices($array = [])
    {
        if ($array != null) {
            return self::find()->where(['IN', 'id', $array])->all();
        }

        return null;
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

    public function getId()
    {
        return $this->id;
    }
}
