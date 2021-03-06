<?php

namespace backend\modules\customer\models;

use backend\modules\customer\models\query\Dep365CustomerOnlineDichVuQuery;
use common\helpers\MyHelper;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;

class Dep365CustomerOnlineDichVu extends \yii\db\ActiveRecord
{
    const STATUS_PUBLISHED = 1;
    const STATUS_DRAFF = 0;

    const IS_NGUON_LE_TAN = 2;
    const IS_NGUON_TV_ONLINE = 1;

    /*
     * Tổng tương tác từng dịch vụ
     * PhongKhamController
     */
    public $total_tuongTac;
    public $total_lichMoi;
    public $total_lichHen;
    public $total_khachDen;
    public $total_khachLam;
    public $co_so;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dep365_customer_online_dich_vu';
    }

    public static function find()
    {
        return new Dep365CustomerOnlineDichVuQuery(get_called_class());
    }

    public function behaviors()
    {
        return [
            'slug' => [
                'class' => SluggableBehavior::class,
                'immutable' => false, //only create 1
                'ensureUnique' => true, //
                'value' => function () {
                    return MyHelper::createAlias($this->name);
                }
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
            [['name', 'slug'], 'required'],
            [['name'], 'unique'],
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
            'name' => Yii::t('backend', 'Sản phẩm'),
            'slug' => Yii::t('backend', 'Slug'),
            'status' => Yii::t('backend', 'Status'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
        ];
    }

    public function afterFind()
    {
        parent::afterFind(); // TODO: Change the autogenerated stub
    }

    public function getUserCreatedBy($id)
    {
        if ($id == null) {
            $id = 2;
        }
        $user = UserProfile::find()->where(['user_id' => $id])->one();
        return $user;
    }

    public function getUserUpdatedBy($id)
    {
        if ($id == null) {
            $id = 2;
        }
        $user = UserProfile::find()->where(['user_id' => $id])->one();
        return $user;
    }

    public static function getById($id)
    {
        return self::find()->where([self::tableName() . '.id' => $id])->one();
    }

    public static function getSanPhamDichVu()
    {
        return self::find()->published()->all();
    }

    public static function getSanPhamDichVuArray()
    {
        $data = self::getSanPhamDichVu();
        $result = [];
        foreach ($data as $key => $item) {
            $result[$item->id] = $item->name;
        }
        return $result;
    }
}
