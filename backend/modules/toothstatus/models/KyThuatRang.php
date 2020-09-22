<?php

namespace backend\modules\toothstatus\models;

use backend\modules\toothstatus\models\query\KyThuatRangQuery;
use phpDocumentor\Reflection\Types\Self_;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;

/**
 * This is the model class for table "ky_thuat_rang".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $link_video
 * @property string $description
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class KyThuatRang extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ky_thuat_rang';
    }

    public static function find()
    {
        return new KyThuatRangQuery(get_called_class());
    }

    public function behaviors()
    {
        return [
            'slug' => [
                'class' => SluggableBehavior::class,
                'attribute' => 'name',
                'slugAttribute' => 'slug',
                'immutable' => true, //only create 1
                'ensureUnique' => true, //
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
            [['name', 'slug', 'link_video'], 'required'],
            [['name'], 'unique'],
            [['description'], 'string'],
            [['link_video'], 'checkVideo'],
//            [['link_video'], 'match', 'pattern' => '#^(?:https?://)?(?:www\.)?(?:m\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x'],
            [['status'], 'integer'],
            [['name', 'slug', 'link_video'], 'string', 'max' => 255],
        ];
    }

    public function checkVideo()
    {
        if (!$this->hasErrors()) {
            if (!strpos($this->link_video, 'youtu.be') && !strpos($this->link_video, 'youtube.com') && !strpos($this->link_video, '.mp4')) {
                $this->addError('link_video', Yii::t('backend', 'Đường dẫn không hợp lệ'));
            }
        }
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
            'link_video' => Yii::t('backend', 'Link Video'),
            'description' => Yii::t('backend', 'Description'),
            'status' => Yii::t('backend', 'Status'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
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

    public static function getTagByName($name)
    {
        return self::find()->where(['name' => $name])->one();
    }

    public static function getListKyThuatRang()
    {
        return self::find()->published()->all();
    }

    public static function getListByTinhTrang($tinhtrang = null)
    {
        if ($tinhtrang == null) {
            return null;
        }
        $query = self::find()
            ->select([self::tableName() . '.name', self::tableName() . '.id', self::tableName() . '.link_video'])
            ->published()->join(
                'LEFT JOIN',
                'ky_thuat_tinh_trang_rang_hasmany',
                self::tableName() . '.id=ky_thuat_tinh_trang_rang_hasmany.ky_thuat'
            )->where(['ky_thuat_tinh_trang_rang_hasmany.tinh_trang_rang' => $tinhtrang]);
        return $query->all();
    }
}
