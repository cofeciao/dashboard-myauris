<?php

namespace backend\modules\clinic\models;

use backend\modules\clinic\models\query\PhongKhamSanPhamQuery;
use common\models\UserProfile;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "phong_kham_san_pham".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $don_gia
 * @property string $mota
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class PhongKhamSanPham extends \yii\db\ActiveRecord
{
    const STATUS_PUBLISHED = 1;
    const STATUS_DRAFF = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'phong_kham_san_pham';
    }

    public static function find()
    {
        return new PhongKhamSanPhamQuery(get_called_class());
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
            [['name', 'slug', 'don_gia', 'services_id'], 'required'],
            [['name'], 'filter', 'filter' => 'trim'],
            [
                'name',
                'unique',
                'targetClass' => '\backend\modules\clinic\models\PhongKhamSanPham',
                'message' => 'Tên đã tồn tại',
                'filter' => function ($query) {
                    $query->andWhere(['not', ['id' => $this->getId()]]);
                },
            ],
            [['status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['don_gia'], 'safe', 'message' => 'Đơn giá chưa đúng định dạng.'],
            [['mota'], 'string'],
            [['name', 'slug'], 'string', 'max' => 255],
            [['services_id'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'name' => Yii::t('backend', 'Sản phẩm'),
            'slug' => Yii::t('backend', 'Slug'),
            'services_id' => Yii::t('backend', 'Dịch vụ'),
            'don_gia' => Yii::t('backend', 'Đơn giá'),
            'mota' => Yii::t('backend', 'Mota'),
            'status' => Yii::t('backend', 'Status'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
        ];
    }

    public static function getOneSanPham($id)
    {
        return self::findOne($id);
    }

    /*
     * Lấy toàn bộ sản phẩm active
     */
    public static function getSanPham()
    {
        $cache = Yii::$app->cache;
        $key = 'redis-get-san-pham';
        $data = $cache->get($key);
        if ($data === false) {
            $data = self::find()->published()->all();
            $cache->set($key, $data, 86400 * 30);
        }

        return $data;
    }

    public static function getSanPhamArray()
    {
        $data = self::getSanPham();
        $result = [];

        foreach ($data as $key => $value) {
            $result[$value->id] = $value->name;
        }

        return $result;
    }

    public static function getArraySanPhamByDichVu($dichVuId = null)
    {
        $sanPham = null;
        if ($dichVuId == null) {
            $sanPham = self::find()->published()->all();
        } else {
            $sanPham = self::find()->where(['services_id' => $dichVuId])->published()->all();
        }

//        $sanPham = self::find()->where(['LIKE', 'services_id', '"' . $dichVuId . '"'])->published()->all();
        return $sanPham;
//        if ($sanPham == null) return [];
//        return ArrayHelper::map($sanPham, 'id', 'name');
    }

    public function getDichVuHasOne()
    {
        return $this->hasOne(PhongKhamDichVu::class, ['id' => 'services_id']);
    }

    public function getSanPhamOne($id)
    {
        return self::find()->where(['id' => $id])->published()->one();
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

    public static function getListSanPhamByListId($arrId)
    {
        if (empty($arrId)) {
            return [];
        }
        $query = PhongKhamSanPham::find()->select(['id', 'name'])
            ->where(['in', 'id', $arrId]);
        $data = $query->all();
        $result = [];
        foreach ($data as $item) {
            $result[$item->id] = $item->name;
        }
        return $result;
    }
}
