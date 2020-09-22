<?php

namespace backend\modules\chi\models;

use backend\modules\chi\models\query\NhomChiQuery;
use common\models\UserProfile;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "thuchi_nhom_chi".
 *
 * @property int $id
 * @property string $name
 * @property int $category
 * @property string $code
 * @property string $description
 * @property int $status
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 */
class NhomChi extends \yii\db\ActiveRecord
{
    const STATUS_DISABLED = 0;
    const STATUS_PUBLISHED = 1;

    public static function tableName()
    {
        return 'thuchi_nhom_chi';
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
            ]
        ];
    }

    public static function find()
    {
        return new NhomChiQuery(get_called_class());
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'category', 'code'], 'required'],
            [['category', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 500],
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
            'category' => Yii::t('backend', 'Category'),
            'code' => Yii::t('backend', 'Code'),
            'description' => Yii::t('backend', 'Description'),
            'status' => Yii::t('backend', 'Status'),
            'created_at' => Yii::t('backend', 'Created At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'updated_by' => Yii::t('backend', 'Updated By'),
        ];
    }

    public function getDanhMucHasOne()
    {
        return $this->hasOne(DanhMucChi::class, ['id' => 'category']);
    }

    public function getKhoanChiHasMany()
    {
        return $this->hasMany(KhoanChi::class, ['category' => 'id']);
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

    public static function getNhomChiByDanhMuc($id)
    {
        return self::find()->where([self::tableName() . '.category' => $id])->all();
    }

    public static function getNhomChiByKhoanChi($id)
    {
        return self::find()->joinWith(['khoanChiHasMany'])->where([KhoanChi::tableName() . '.id' => $id])->one();
    }
}
