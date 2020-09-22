<?php

namespace backend\modules\support\models;

use backend\modules\support\models\query\SupportQuery;
use backend\modules\toothstatus\models\TinhTrangRang;
use backend\modules\user\models\User;
use common\helpers\MyHelper;
use cornernote\linkall\LinkAllBehavior;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;

class Support extends \yii\db\ActiveRecord
{
    const STATUS_PUBLISHED = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'support';
    }

    public static function find()
    {
        return new SupportQuery(get_called_class());
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
            [['catagory_id', 'name', 'slug'], 'required'],
            [['catagory_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['question', 'anwser'], 'string'],
            [['name', 'slug'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['time'], 'number']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'catagory_id' => Yii::t('backend', 'Nhóm / phòng ban'),
            'name' => Yii::t('backend', 'Tiêu đề câu hỏi'),
            'slug' => Yii::t('backend', 'Slug'),
            'question' => Yii::t('backend', 'Câu hỏi'),
            'anwser' => Yii::t('backend', 'Câu trả lời'),
            'status' => Yii::t('backend', 'Status'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
            'time' => Yii::t('backend', 'Thời gian đọc câu hỏi (phút)'),
            'users_view' => Yii::t('backend', 'Người xem'),
        ];
    }

    public static function getSupportByCatagory($catagory_id)
    {
        return self::find()->where(['catagory_id' => $catagory_id])->published();
    }

    public function getSupportCatagoryHasOne()
    {
        return $this->hasOne(SupportCatagory::class, ['id' => 'catagory_id']);
    }

    public static function getSupport($id)
    {
        return self::find()->where(['id' => $id])->one();
    }

    public static function searchSupport($value)
    {
        $query = self::find();

        $query->andWhere([
            'or',
            ['like', 'name', $value],
            ['like', 'slug', $value],
            ['like', 'question', $value],
            ['like', 'anwser', $value],
        ]);

//        echo $query->createCommand()->getRawSql();
//        die;

        $model = $query->published()->all();

        return $model;
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

    public static function getListUsersView($users_view)
    {
        if (!is_array($users_view) || count($users_view) <= 0) {
            return null;
        }
        $users = [];
        foreach ($users_view as $user_view) {
            $user = User::find()->joinWith(['userProfile'])->where(['id' => $user_view])->one();
            if ($user != null) {
                $users[] = $user;
            }
        }
        return $users;
    }
}
