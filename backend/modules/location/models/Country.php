<?php

namespace backend\modules\location\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;

/**
 * This is the model class for table "country".
 *
 * @property int $id
 * @property string $CountryCode
 * @property string $CommonName
 * @property string $FormalName
 * @property string $CountryType
 * @property string $CountrySubType
 * @property string $Sovereignty
 * @property string $Capital
 * @property string $CurrencyCode
 * @property string $CurrencyName
 * @property string $TelephoneCode
 * @property string $CountryCode3
 * @property string $CountryNumber
 * @property string $InternetCountryCode
 * @property int $SortOrder
 * @property int $status
 * @property string $Flags
 * @property int $IsDeleted
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'country';
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
            [['CountryCode'], 'required'],
            [['SortOrder', 'status', 'IsDeleted', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['CountryCode', 'CommonName', 'FormalName', 'CountryType', 'CountrySubType', 'Sovereignty', 'Capital', 'CurrencyCode', 'CurrencyName', 'TelephoneCode', 'CountryCode3', 'CountryNumber', 'InternetCountryCode', 'Flags'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'CountryCode' => Yii::t('backend', 'Country Code'),
            'CommonName' => Yii::t('backend', 'Tên Chung'),
            'FormalName' => Yii::t('backend', 'Tên Chính Thức'),
            'CountryType' => Yii::t('backend', 'Loại quốc gia'),
            'CountrySubType' => Yii::t('backend', 'Country Sub Type'),
            'Sovereignty' => Yii::t('backend', 'Sovereignty'),
            'Capital' => Yii::t('backend', 'Thủ Đô'),
            'CurrencyCode' => Yii::t('backend', 'Mã tiền tệ'),
            'CurrencyName' => Yii::t('backend', 'Tiền tệ'),
            'TelephoneCode' => Yii::t('backend', 'Mã điện thoại'),
            'CountryCode3' => Yii::t('backend', 'Country Code3'),
            'CountryNumber' => Yii::t('backend', 'Country Number'),
            'InternetCountryCode' => Yii::t('backend', 'Code Internet'),
            'SortOrder' => Yii::t('backend', 'Sort Order'),
            'status' => Yii::t('backend', 'Status'),
            'Flags' => Yii::t('backend', 'Flags'),
            'IsDeleted' => Yii::t('backend', 'Is Deleted'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
        ];
    }

    public static function getCountry()
    {
        $cache = Yii::$app->cache;
        $key = 'redis-get-country';
        $data = $cache->get($key);
        if ($data === false) {
            $data = self::find()->all();
            $cache->set($key, $data);
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
}
