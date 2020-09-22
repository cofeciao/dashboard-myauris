<?php

namespace backend\modules\appmyauris\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;
use yii\helpers\ArrayHelper;
use backend\modules\clinic\models\PhongKhamSanPham;

/**
 * This is the model class for table "app_myauris_group_san_pham".
 *
 * @property int $id
 * @property string $name
 * @property array $list
 * @property int $status
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 */
class AppMyaurisGroupSanPham extends \yii\db\ActiveRecord
{
    const STATUS_DISABLED = 0;
    const STATUS_PUBLISHED = 1;

    public static function tableName()
    {
        return 'app_myauris_group_san_pham';
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

    // public static function find()
    // {
    //     return new AppMyaurisGroupSanPhamQuery(get_called_class());
    // }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['list'], 'safe'],
            [['status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'list' => 'List',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    public function getUserCreatedBy($id)
    {
        if ($id == null)
            return null;
        $user = UserProfile::find()->where(['user_id' => $id])->one();
        return $user;
    }

    public function getUserUpdatedBy($id)
    {
        if ($id == null)
            return null;
        $user = UserProfile::find()->where(['user_id' => $id])->one();
        return $user;
    }
    public static function getListSanPham()
    {
        return ArrayHelper::map(PhongKhamSanPham::getSanPham(), 'id', 'name');
    }

    public function getSanPham()
    {
        $array = self::getListSanPham();
        $list = [];
        if (!empty($this->list) && is_array($this->list)) {
            foreach ($this->list as $item) {
                $list[] = $array[$item];
            }
        }
        return implode('<br>', $list);
    }

    public static function getListGroupSanPham()
    {
        $listModel = AppMyaurisGroupSanPham::find()->select(['id', 'name', 'list'])->where(['status' => 1])->all();
        return ArrayHelper::toArray($listModel);
    }
}
