<?php

namespace backend\modules\customer\models;

use backend\modules\customer\models\query\Dep365CustomerOnlineComeQuery;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;

/**
 * This is the model class for table "dep365_customer_online_come".
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
class Dep365CustomerOnlineCome extends \yii\db\ActiveRecord
{
    const STATUS_PUBLISHED = 1;
    const STATUS_DRAFF = 0;
    const STATUS_ACCEPT = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dep365_customer_online_come';
    }

    public static function find()
    {
        return new Dep365CustomerOnlineComeQuery(get_called_class());
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
            [['status', 'accept', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
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
            'name' => Yii::t('backend', 'Tình trạng khách đến'),
            'slug' => Yii::t('backend', 'Slug'),
            'mota' => Yii::t('backend', 'Mota'),
            'status' => Yii::t('backend', 'Status'),
            'accept' => Yii::t('backend', 'Đồng ý'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
        ];
    }

    public static function getNameTrangThaiKhachDen($id)
    {
        $cache = Yii::$app->cache;
        $key = 'get-name-trang-thai-khach-den-' . $id;

        $data = $cache->get($key);

        if ($data == false) {
            $data = self::find()->where(['id' => $id])->one();
            $cache->set($key, $data, 86400 * 7);
        }
        if ($data === null) {
            $name = null;
        } else {
            $name = $data->name;
        }
        return $name;
    }

    public function getNameTrangThaiKhachDenNotStatic($id)
    {
        $cache = Yii::$app->cache;
        $key = 'get-name-trang-thai-den-not-static-' . $id;

        $data = $cache->get($key);

        if ($data == false) {
            $data = self::find()->where(['id' => $id])->one();
            $cache->set($key, $data);
        }

        if ($data === null) {
            $name = null;
        } else {
            $name = $data->name;
        }
        return $name;
    }


    public function getNameTrangThaiKhachDenNotStaticColor($id)
    {
        $cache = Yii::$app->cache;
        $key = 'get-name-trang-thai-den-not-static-' . $id;

        $data = $cache->get($key);

        if ($data == false) {
            $data = self::find()->where(['id' => $id])->one();
            $cache->set($key, $data);
        }
        if ($data === null) {
            $name = null;
        } else {
            if($data->accept === 1){
                $name = '<span class="badge badge-success badge-pill ">' . $data->name . '</span>';
            } else {
                $name = '<span class="badge badge-warning badge-pill ">' . $data->name . '</span>';
            }
        }
        return $name;
    }

    public static function getCustomerOnlineCome($isLetan = null)
    {
        $cache = Yii::$app->cache;
        $key = 'redis-get-customer-online-come';
        $data = $cache->get($key);
        if ($data === false) {
            $query = self::find();
            if ($isLetan != null) {
                $query->where(['is_create' => $isLetan]);
            }
            $data = $query->published()->all();
            $cache->set($key, $data, 86400 * 30);
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

    public static function getCustomerOnlineComeArrayAccept()
    {
        $result = [];
        $data = self::find()->where(['accept' => Dep365CustomerOnlineCome::STATUS_ACCEPT])->all();
        foreach ($data as $item) {
            $result[$item->id] = $item->name;
        }
        return $result;
    }
}
