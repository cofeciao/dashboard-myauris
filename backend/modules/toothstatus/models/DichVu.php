<?php

namespace backend\modules\toothstatus\models;

use backend\modules\toothstatus\models\query\DichVuQuery;
use common\helpers\MyHelper;
use cornernote\linkall\LinkAllBehavior;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;

/**
 * This is the model class for table "lua_chon_loai_dich_vu".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $price
 * @property string $description
 * @property int $sort
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 */
class DichVu extends \yii\db\ActiveRecord
{
    const STATUS_PUBLISHED = 1;
    const STATUS_DISABLED = 0;
    public $tinh_trang_rang;
    public $do_tuoi;
    public $lua_chon;

    public $image_0b;
    public $image_0a;

    public $image_1b;
    public $image_1a;

    public $image_2b;
    public $image_2a;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dich_vu';
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

    public static function find()
    {
        return new DichVuQuery(get_called_class());
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'price', 'tinh_trang_rang', 'do_tuoi', 'lua_chon'], 'required'],
            [['price'], 'match', 'pattern' => '/^[0-9.]+$/'],
            [['description', 'customer_image'], 'string'],
            [['name', 'slug'], 'string', 'max' => 255],
            [['image_0b', 'image_0a', 'image_1b', 'image_1a', 'image_2b', 'image_2a'], 'file', 'extensions' => ['png', 'jpg', 'jpeg'], 'wrongExtension' => 'Chỉ chấp nhận định dạng: {extensions}', 'maxSize' => 3 * 1024 * 1024],
            [['star'], 'number', 'min' => 0, 'max' => 5]
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
            'slug' => Yii::t('backend', 'Slug'),
            'tinh_trang_rang' => Yii::t('backend', 'Tình trạng răng'),
            'do_tuoi' => Yii::t('backend', 'Độ tuổi'),
            'lua_chon' => Yii::t('backend', 'Lựa chọn'),
            'price' => Yii::t('backend', 'Price'),
            'description' => Yii::t('backend', 'Description'),
            'customer_image' => Yii::t('backend', 'Hình khách hàng'),
            'status' => Yii::t('backend', 'Status'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
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
        $tinh_trang_rangs = [];
        if (is_array($this->tinh_trang_rang)) {
            foreach ($this->tinh_trang_rang as $tinh_trang_rang) {
                $tinh_trang_rang = TinhTrangRang::getTagByName($tinh_trang_rang);
                if ($tinh_trang_rang) {
                    $tinh_trang_rangs[] = $tinh_trang_rang;
                }
            }
        }
        $this->linkAll('tinhTrangRangHasMany', $tinh_trang_rangs);
        $do_tuois = [];
        if (is_array($this->do_tuoi)) {
            foreach ($this->do_tuoi as $do_tuoi) {
                $do_tuoi = DoTuoi::getTagByName($do_tuoi);
                if ($do_tuoi) {
                    $do_tuois[] = $do_tuoi;
                }
            }
        }
        $this->linkAll('doTuoiHasMany', $do_tuois);
        $lua_chons = [];
        if (is_array($this->lua_chon)) {
            foreach ($this->lua_chon as $lua_chon) {
                $lua_chon = LuaChon::getTagByName($lua_chon);
                if ($lua_chon) {
                    $lua_chons[] = $lua_chon;
                }
            }
        }
        $this->linkAll('luaChonHasMany', $lua_chons);
        parent::afterSave($insert, $changedAttributes);
    }

    public function getTinhTrangRangHasMany()
    {
        return $this->hasMany(TinhTrangRang::class, ['id' => 'tinh_trang_rang'])
            //->via('postToTag');
            ->viaTable('dichvu_tinhtrangrang_hasmany', ['dich_vu' => 'id']);
    }

    public function getDoTuoiHasMany()
    {
        return $this->hasMany(DoTuoi::class, ['id' => 'do_tuoi'])
            //->via('postToTag');
            ->viaTable('dichvu_dotuoi_hasmany', ['dich_vu' => 'id']);
    }

    public function getLuaChonHasMany()
    {
        return $this->hasMany(LuaChon::class, ['id' => 'lua_chon'])
            //->via('postToTag');
            ->viaTable('dichvu_luachon_hasmany', ['dich_vu' => 'id']);
    }

    public static function getListByVariable($tinh_trang = null, $do_tuoi = null, $lua_chon = null)
    {
        if ($tinh_trang == null || $do_tuoi == null || $lua_chon == null) {
            return null;
        }
        $query = self::find()
            ->where([
                'dichvu_tinhtrangrang_hasmany.tinh_trang_rang' => $tinh_trang,
                'dichvu_dotuoi_hasmany.do_tuoi' => $do_tuoi,
                'dichvu_luachon_hasmany.lua_chon' => $lua_chon,
            ])
            ->joinWith(['tinhTrangRangHasMany', 'doTuoiHasMany', 'luaChonHasMany'])
            ->published();
//        echo $query->createCommand()->rawSql;die;
        return $query->all();
    }
}
