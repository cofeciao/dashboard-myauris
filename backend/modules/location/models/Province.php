<?php

namespace backend\modules\location\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;

/**
 * This is the model class for table "province".
 *
 * @property int $id
 * @property string $name
 * @property string $Type
 * @property int $TelephoneCode
 * @property string $ZipCode
 * @property int $CountryId
 * @property string $CountryCode
 * @property int $SortOrder
 * @property int $status
 * @property int $IsDeleted
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class Province extends \yii\db\ActiveRecord
{
    const PROVINCE_TINH = 'Tỉnh';
    const PROVINCE_CITY = 'Thành phố';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'province';
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
            [['name', 'CountryId'], 'required'],
            [['TelephoneCode', 'CountryId', 'SortOrder', 'status', 'IsDeleted', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name', 'Type', 'ZipCode'], 'string', 'max' => 255],
            [['CountryCode'], 'string', 'max' => 25],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'name' => Yii::t('backend', 'Province'),
            'Type' => Yii::t('backend', 'Type'),
            'TelephoneCode' => Yii::t('backend', 'Telephone Code'),
            'ZipCode' => Yii::t('backend', 'Zip Code'),
            'CountryId' => Yii::t('backend', 'Country'),
            'CountryCode' => Yii::t('backend', 'Country Code'),
            'SortOrder' => Yii::t('backend', 'Sort Order'),
            'status' => Yii::t('backend', 'Status'),
            'IsDeleted' => Yii::t('backend', 'Is Deleted'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
        ];
    }

    public static function getProvinceOne($id)
    {
        $cache = Yii::$app->cache;
        $key = 'redis-get-province-' . $id;
        $data = $cache->get($key);
        if ($data === false) {
            $data = self::find()->where(['id' => $id])->one();
            $cache->set($key, $data);
        }
        return $data;
    }

    public static function getProvince()
    {
        $cache = Yii::$app->cache;
        $key = 'redis-get-province';
        $data = $cache->get($key);
        if ($data === false) {
            $data = self::find()->orderBy(['SortOrder' => SORT_ASC])->all();
            $cache->set($key, $data);
        }
        return $data;
    }

    public static function provinceType()
    {
        return [
            self::PROVINCE_TINH => Yii::t('backend', 'Tỉnh'),
            self::PROVINCE_CITY => Yii::t('backend', 'City')
        ];
    }

    public function getCountry()
    {
        return $this->hasOne(Country::class, ['id' => 'CountryId']);
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

    // Get list [id => name] by list id
    public static function getArrayProvinceByListId($arrId)
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
