<?php

namespace app\backend\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;

/**
 * This is the model class for table "note".
 *
 * @property int $id
 * @property string $note_type
 * @property int $id_user
 * @property int $id_customer
 * @property string $content
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Dep365CustomerOnline $customer
 */
class Note extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'note';
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
        ];
    }




    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'id_customer', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'string'],
            [['created_at', 'updated_at'], 'required'],
            [['note_type'], 'string', 'max' => 255],
            [['id_customer'], 'exist', 'skipOnError' => true, 'targetClass' => Dep365CustomerOnline::class, 'targetAttribute' => ['id_customer' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'note_type' => 'Note Type',
            'id_user' => 'Id User',
            'id_customer' => 'Id Customer',
            'content' => 'Content',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Dep365CustomerOnline::class, ['id' => 'id_customer']);
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
