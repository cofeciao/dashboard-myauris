<?php

namespace backend\modules\labo\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;
use backend\modules\labo\models\query\LaboGiaiDoanImageQuery;
use yii\web\UploadedFile;

/**
 * This is the model class for table "labo_giai_doan_image".
 *
 * @property int $id
 * @property int $labo_giai_doan_id
 * @property string $image
 * @property string $google_id
 * @property int $status
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $updated_at
 */
class LaboGiaiDoanImage extends \yii\db\ActiveRecord
{
    const STATUS_DISABLED = 0;
    const STATUS_PUBLISHED = 1;

    /**
     * @var UploadedFile
     */
    public $imageFile;
    public $another_page;

    public static function tableName()
    {
        return 'labo_giai_doan_image';
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
        return new LaboGiaiDoanImageQuery(get_called_class());
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['labo_giai_doan_id', 'status', 'created_at', 'created_by', 'updated_by', 'updated_at', 'another_page'], 'integer'],
            [['image', 'google_id'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
            [['another_page'],'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'labo_giai_doan_id' => 'Labo Giai Doan ID',
            'image' => 'Image',
            'google_id' => 'Google ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Người Tạo',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
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

    public function upload($fileName)
    {
        if ($this->validate()) {
            $this->imageFile->saveAs(Yii::getAlias('@backend/web') . '/uploads/tmp/' . $fileName, false);
            return true;
        } else {
            return false;
        }
    }

    public function deleteFile($fileName){
        if(file_exists(Yii::getAlias('@backend/web') . '/uploads/tmp/' . $fileName)){
            unlink(Yii::getAlias('@backend/web') . '/uploads/tmp/' . $fileName);
        }
    }

}
