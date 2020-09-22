<?php

namespace backend\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;

/**
 * This is the model class for table "test".
 *
 * @property int $id
 * @property string $name
 * @property string $category
 * @property string $image
 * @property int $view_number
 * @property int $status
 * @property int $position
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $idTool
 */
class Test extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'test';
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
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'category', 'image'], 'required'],
            [['view_number', 'position', 'created_at', 'updated_at', 'created_by', 'updated_by', 'idTool'], 'integer'],
            [['name', 'category', 'image'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'category' => 'Category',
            'image' => 'Image',
            'view_number' => 'View Number',
            'status' => 'Status',
            'position' => 'Position',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'idTool' => 'Id Tool',
        ];
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
}
