<?php
namespace backend\modules\clinic\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "phong_kham_uom_rang_2".
 *
 * @property int $id
 * @property int $customer_id
 * @property string $folder_id
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class PhongKhamUomRang2 extends \yii\db\ActiveRecord
{
    public $fileImage;

    public static function tableName()
    {
        return 'phong_kham_uom_rang_2';
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
                //'preserveNonEmptyValues' => true,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => time(),
            ],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'folder_id'], 'required'],
            [['customer_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['folder_id'], 'string', 'max' => 255],
            [['fileImage'], 'file', 'extensions' => ['png', 'jpg', 'jpeg'], 'maxSize' => 1024 * 1024 * 100, 'maxFiles' => 100, 'wrongExtension' => 'Chỉ chấp nhận file có định dạng: {extensions}'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'customer_id' => Yii::t('backend', 'Customer ID'),
            'folder_id' => Yii::t('backend', 'Folder ID'),
            'status' => Yii::t('backend', 'Status'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
        ];
    }
}
