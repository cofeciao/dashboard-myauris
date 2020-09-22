<?php

namespace backend\modules\general\models;

use backend\modules\general\models\query\Dep365NotificationQuery;
use backend\modules\user\models\User;
use backend\modules\user\models\UserSubRole;
use cornernote\linkall\LinkAllBehavior;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;
use yii\db\Exception;

/**
 * This is the model class for table "dep365_notification".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $icon
 * @property string $description
 * @property string $content
 * @property int $is_new
 * @property int $is_bg
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class Dep365Notification extends \yii\db\ActiveRecord
{
    const IS_NEW = 1;
    const IS_NOT_NEW = 0;

    const FOR_EVERYONE = 'everyone';
    const FOR = [
        self::FOR_EVERYONE => 'Mọi người',
        UserSubRole::ROLE_TRUONG_PHONG => 'Trưởng phòng',
        UserSubRole::ROLE_KE_TOAN => 'Kế Toán',
        UserSubRole::ROLE_TEAM_LEAD => 'Team Lead',
        UserSubRole::ROLE_ONLINE => 'Nhân viên Online'
    ];

    public $users;
    public $seen;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dep365_notification';
    }

    public function behaviors()
    {
        return [
            'slug' => [
                'class' => SluggableBehavior::class,
                'attribute' => 'name',
                'slugAttribute' => 'slug',
                'immutable' => false, //only create 1
                'ensureUnique' => true, //
            ],
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
//            [
//                'class' => AttributeBehavior::class,
//                'attributes' => [
//                    ActiveRecord::EVENT_BEFORE_INSERT => ['for_who']
//                ],
//                'value' => function () {
//                    if ($this->for_who == null) {
//                        return self::FOR_EVERYONE;
//                    }
//                    return $this->for_who;
//                }
//            ],
            LinkAllBehavior::class
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'slug'], 'required'],
            [['description', 'content'], 'string'],
            [['is_new', 'is_bg', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name', 'slug'], 'string', 'max' => 255],
            [['icon'], 'string', 'max' => 25],
            [['for_who'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'name' => Yii::t('backend', 'Thông báo'),
            'slug' => Yii::t('backend', 'Slug'),
            'icon' => Yii::t('backend', 'Icon'),
            'description' => Yii::t('backend', 'Description'),
            'content' => Yii::t('backend', 'Content'),
            'is_new' => Yii::t('backend', 'Is New'),
            'is_bg' => Yii::t('backend', 'Is Bg'),
            'status' => Yii::t('backend', 'Status'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
        ];
    }

    public static function find()
    {
        return new Dep365NotificationQuery(get_called_class());
    }

    public function afterSave($insert, $changedAttributes)
    {
        $users = [];
        if (is_array($this->users)) {
            $modelUser = new User();
            foreach ($this->users as $user_id) {
                $user = $modelUser->getCoso($user_id);
                if ($user) {
                    $users[] = $user;
                }
            }
        }
        $this->linkAll('notificationSeenHasMany', $users);
        $cache = \Yii::$app->cache;
        $keys = [
            'get-user-info-noti-' . Yii::$app->user->id,
            'get-total-notif-not-seen-' . Yii::$app->user->id,
            'get-5-notif-final-'.\Yii::$app->user->id
        ];
        $cache->set('time-change-notification', time());
        foreach ($keys as $key) {
            $cache->delete($key);
        }
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    public function afterDelete()
    {
        $cache = \Yii::$app->cache;
        $keys = [
            'get-user-info-noti-' . Yii::$app->user->id,
            'get-total-notif-not-seen-' . Yii::$app->user->id,
            'get-5-notif-final-'.\Yii::$app->user->id
        ];
        foreach ($keys as $key) {
            $cache->delete($key);
        }
        parent::afterDelete(); // TODO: Change the autogenerated stub
    }

    public static function quickCreate($data = [])
    {
        if (!is_array($data)) {
            return false;
        }
        $notif = new self();
        $notif->setAttributes($data);
        if (!$notif->save()) {
            return false;
        }
        return $notif->primaryKey;
    }

    public function getUserCreatedBy($id)
    {
        if ($id == null) {
            return false;
        }
        $user = UserProfile::find()->where(['user_id' => $id])->one();
        return $user;
    }

    public function getUserUpdatedBy($id)
    {
        if ($id == null) {
            return false;
        }
        $user = UserProfile::find()->where(['user_id' => $id])->one();
        return $user;
    }

    public function getNotificationSeenHasMany()
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])
            ->viaTable('dep365_notification_seen', ['notification_id' => 'id']);
    }
}
