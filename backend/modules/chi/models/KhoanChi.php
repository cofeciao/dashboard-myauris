<?php

namespace backend\modules\chi\models;

use backend\modules\chi\models\query\KhoanChiQuery;
use backend\modules\report\models\SmartReportModel;
use common\models\UserProfile;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "thuchi_khoan_chi".
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
class KhoanChi extends \yii\db\ActiveRecord
{
    const STATUS_DISABLED = 0;
    const STATUS_PUBLISHED = 1;

    public $danhMucChi;
//    public $da_chi;
//    public $cho_duyet;
//    public $money;

    public static function tableName()
    {
        return 'thuchi_khoan_chi';
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
        return new KhoanChiQuery(get_called_class());
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'category', 'code'], 'required'],
            ['code', 'unique', 'targetClass' => self::class, 'targetAttribute' => 'code'],
            [['category', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 500],
            [['danhMucChi'], 'safe']
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
            'category' => Yii::t('backend', 'Nhóm chi'),
            'code' => Yii::t('backend', 'Code'),
            'description' => Yii::t('backend', 'Description'),
            'status' => Yii::t('backend', 'Status'),
            'created_at' => Yii::t('backend', 'Created At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'updated_by' => Yii::t('backend', 'Updated By'),
            'danhMucChi' => Yii::t('backend', 'Danh mục chi'),
        ];
    }

    public function getDanhMucChiHasOne()
    {
        return $this->hasOne(DanhMucChi::class, ['id' => 'category'])->via('nhomChiHasOne');
    }

    public function getNhomChiHasOne()
    {
        return $this->hasOne(NhomChi::class, ['id' => 'category']);
    }

    public function getDeXuatChiHasMany()
    {
        return $this->hasMany(DeXuatChi::class, ['khoan_chi' => 'id']);
    }

    public function getSmartReportHasMany()
    {
        return $this->hasMany(SmartReportModel::class, ['id_khoan_chi' => 'id']);
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

    public static function getListKhoanChi()
    {
        return self::find()->published()->all();
    }

    public static function getListKhoanChiByNhomChi($id)
    {
        return self::find()->where([self::tableName() . '.category' => $id])->published()->all();
    }

    public static function getKhoanChiByCode($code = null)
    {
        if ($code == null) {
            return null;
        }

        return self::find()->where(['code' => $code])->published()->one();
    }

    public static function getDanhMucChiByKhoanChi($id = null)
    {
        if ($id == null) {
            return null;
        }
        $get = self::find()->joinWith(['nhomChiHasOne'])->where([self::tableName() . '.id' => $id])->one();
        if ($get == null || $get->nhomChiHasOne == null || $get->nhomChiHasOne->danhMucHasOne == null) {
            return null;
        }

        return $get->nhomChiHasOne->danhMucHasOne;
    }

    public function getDeXuatChiDaChi()
    {
        return $this->hasMany(DeXuatChi::class, ['khoan_chi' => 'id'])->where(['IN', DeXuatChi::tableName() . '.status', [
            DeXuatChi::STATUS_KE_TOAN_DUYET,
            DeXuatChi::STATUS_HOAN_THANH
        ]])->sum(DeXuatChi::tableName() . '.so_tien_chi');
    }

    public function getDeXuatChiChoDuyet()
    {
        return $this->hasMany(DeXuatChi::class, ['khoan_chi' => 'id'])
            ->where([DeXuatChi::tableName() . '.status' => DeXuatChi::STATUS_DANG_DOI_DUYET])
            ->sum(DeXuatChi::tableName() . '.so_tien_chi');
    }

    public function getTienDaChi(string $from = null, string $to = null)
    {
        $query = $this->hasMany(SmartReportModel::class, ['id_khoan_chi' => 'id']);
        if ($from != null && $to != null) {
            $query->andWhere(['BETWEEN', SmartReportModel::tableName() . '.report_timestamp', $from, $to]);
        }
        return $query->sum(SmartReportModel::tableName() . '.tien_da_chi');
    }

    public function getTienChoDuyet(string $from = null, string $to = null)
    {
        $query = $this->hasMany(SmartReportModel::class, ['id_khoan_chi' => 'id']);
        if ($from != null && $to != null) {
            $query->andWhere(['BETWEEN', SmartReportModel::tableName() . '.report_timestamp', $from, $to]);
        }
        return $query->sum(SmartReportModel::tableName() . '.tien_cho_duyet');
    }
}
