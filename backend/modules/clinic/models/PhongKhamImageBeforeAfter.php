<?php

namespace backend\modules\clinic\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;

/**
 * This is the model class for table "phong_kham_image_before_after".
 *
 * @property int $id
 * @property int $customer_id
 * @property string $image_before
 * @property string $image_after
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class PhongKhamImageBeforeAfter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'phong_kham_image_before_after';
    }
    public function behaviors()
    {
        return [
//            'slug' => [
//                'class' => SluggableBehavior::class,
//                'attribute' => 'name',
//                'slugAttribute' => 'slug',
//                'immutable' => true, //only create 1
//                'ensureUnique' => true, //
//            ],
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
            [['customer_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['image_before'], 'file', 'extensions' => ['png', 'jpg', 'jpeg'], 'maxSize' => 1024 * 1024 * 100, 'maxFiles' => 100, 'wrongExtension'=>'Chỉ chấp nhận file có định dạng: {extensions}'],
            [['image_after'], 'file', 'extensions' => ['png', 'jpg', 'jpeg'], 'maxSize' => 1024 * 1024 * 100, 'maxFiles' => 100, 'wrongExtension'=>'Chỉ chấp nhận file có định dạng: {extensions}'],
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
            'image_before' => Yii::t('backend', 'Hình chụp khách hàng trước khi điều trị'),
            'image_after' => Yii::t('backend', 'Hình chụp khách hàng sau khi điều trị'),
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
}
