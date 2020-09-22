<?php

namespace backend\modules\toothstatus\models;

use backend\modules\toothstatus\models\query\TinhTrangRangQuery;
use common\helpers\MyHelper;
use cornernote\linkall\LinkAllBehavior;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;

/**
 * This is the model class for table "tinh_trang_rang".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $image
 * @property string $description
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class TinhTrangRang extends \yii\db\ActiveRecord
{
    const STATUS_PUBLISHED = 1;
    const SCENARIO_BACSI = 'bac-si';
    public $ky_thuat;
    public $count;

    public static function tableName()
    {
        return 'tinh_trang_rang';
    }

    public function behaviors()
    {
        return [
            'slug' => [
                'class' => SluggableBehavior::class,
                'immutable' => true, //only create 1
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
            LinkAllBehavior::class,
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'ky_thuat'], 'required'],
            [['name'], 'unique'],
            [['description'], 'string'],
            [['status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name', 'slug', 'image'], 'string', 'max' => 255],
            [['js_bac_si'], 'string', 'max' => 50, 'on' => self::SCENARIO_BACSI]
        ];
    }

    public static function find()
    {
        return new TinhTrangRangQuery(get_called_class());
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
            'image' => Yii::t('backend', 'Image'),
            'description' => Yii::t('backend', 'Description'),
            'status' => Yii::t('backend', 'Status'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
            'ky_thuat' => Yii::t('backend', 'Kỹ thuật'),
            'js_bac_si' => Yii::t('backend', 'Hiệu ứng form răng'),
        ];
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

    public function afterSave($insert, $changedAttributes)
    {
        $kythuats = [];
        foreach ($this->ky_thuat as $ky_thuat) {
            $ky_thuat = KyThuatRang::getTagByName($ky_thuat);
            if ($ky_thuat) {
                $kythuats[] = $ky_thuat;
            }
        }
        $this->linkAll('kyThuatHasMany', $kythuats);
        parent::afterSave($insert, $changedAttributes);
    }

    public function getKyThuatHasMany()
    {
        return $this->hasMany(KyThuatRang::class, ['id' => 'ky_thuat'])
            //->via('postToTag');
            ->viaTable('ky_thuat_tinh_trang_rang_hasmany', ['tinh_trang_rang' => 'id']);
    }

    public static function getListTinhTrangRang()
    {
        return self::find()
            ->select([
                self::tableName() . '.id',
                self::tableName() . '.name',
                self::tableName() . '.image',
                self::tableName() . '.js_bac_si',
                '(SELECT COUNT(*) FROM dichvu_tinhtrangrang_hasmany WHERE dichvu_tinhtrangrang_hasmany.tinh_trang_rang=' . self::tableName() . '.id) AS count'
            ])
            ->published()->all();
    }

    public static function getTagByName($name)
    {
        return self::find()->where(['name' => $name])->one();
    }

    public static function checkHasChild($tinh_trang = null)
    {
        if ($tinh_trang == null) {
            return false;
        }
        $getTinhTrang = self::find()->select([
            '(SELECT COUNT(*) FROM dichvu_tinhtrangrang_hasmany WHERE dichvu_tinhtrangrang_hasmany.tinh_trang_rang=' . self::tableName() . '.id) AS count'
        ])->where([self::tableName() . '.id' => $tinh_trang])->published()->one();
        if ($getTinhTrang == null) {
            return false;
        }
        return $getTinhTrang->count > 0;
    }

    public static function getListTinhTrangRangAPI()
    {
        $data = TinhTrangRang::find()->andWhere(['status' => TinhTrangRang::STATUS_PUBLISHED])
            ->orderBy(['id' => SORT_DESC])->all();
        return $data;
    }
}
