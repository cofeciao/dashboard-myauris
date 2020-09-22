<?php

namespace backend\modules\location\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;

/**
 * This is the model class for table "district".
 *
 * @property int $id
 * @property string $name
 * @property string $Type
 * @property string $LatiLongTude
 * @property int $ProvinceId
 * @property int $SortOrder
 * @property int $status
 * @property int $IsDeleted
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class District extends \yii\db\ActiveRecord
{
    const DISTRICT_QUAN = 'Quận';
    const DISTRICT_HUYEN = 'Huyện';
    const DISTRICT_THIXA = 'Thị xã';
    const DISTRICT_THANHPHO = 'Thành phố';

    public static function tableName()
    {
        return 'district';
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
            [['ProvinceId'], 'required'],
            [['ProvinceId', 'SortOrder', 'status', 'IsDeleted', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name', 'Type', 'LatiLongTude'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'name' => Yii::t('backend', 'District'),
            'Type' => Yii::t('backend', 'Type'),
            'LatiLongTude' => Yii::t('backend', 'Tọa Độ'),
            'ProvinceId' => Yii::t('backend', 'Province ID'),
            'SortOrder' => Yii::t('backend', 'Sort Order'),
            'status' => Yii::t('backend', 'Status'),
            'IsDeleted' => Yii::t('backend', 'Is Deleted'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
        ];
    }

    public static function getDistrict()
    {
        $cache = Yii::$app->cache;
        $key = 'redis-get-district';
        $data = $cache->get($key);
        if ($data === false) {
            $data = self::find()->orderBy(['name' => SORT_DESC])->all();
            $cache->set($key, $data);
        }
        return $data;
    }

    public static function getDistrictType()
    {
        return [
            self::DISTRICT_HUYEN => Yii::t('location', 'Huyện'),
            self::DISTRICT_QUAN => Yii::t('location', 'Quận'),
            self::DISTRICT_THANHPHO => Yii::t('location', 'Thành phố'),
            self::DISTRICT_THIXA => Yii::t('location', 'Thị xã'),
        ];
    }

    public function getProvince()
    {
        return $this->hasOne(Province::class, ['id' => 'ProvinceId']);
    }

    public static function getDistrictByProvince($province = null)
    {
        return self::find()->where(['ProvinceId' => $province, 'status' => 1])->all();
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

    public static function getArrayDistrictByListId($arrId)
    {
        if (empty($arrId)) {
            return [];
        }
        $query = self::find()->select(['id', 'name'])
            ->where(['in', 'id', $arrId]);
        $data = $query->all();
        $result = [];
        foreach ($data as $item) {
            $result[$item->id] = $item->name;
        }
        return $result;
    }
}
