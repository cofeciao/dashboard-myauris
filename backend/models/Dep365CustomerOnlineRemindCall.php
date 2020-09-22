<?php

namespace backend\models;

use backend\models\query\Dep365CustomerOnlineRemindCallQuery;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\customer\models\Dep365CustomerOnlineCome;
use backend\modules\customer\models\Dep365CustomerOnlineDathenStatus;
use backend\modules\customer\models\Dep365CustomerOnlineFailDathen;
use backend\modules\customer\models\Dep365CustomerOnlineFailStatus;
use backend\modules\customer\models\Dep365CustomerOnlineStatus;
use common\models\UserProfile;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use Yii;

class Dep365CustomerOnlineRemindCall extends ActiveRecord
{
    const STATUS_PUBLISHED = 1;
    const STATUS_DISABLED = 0;
    const TYPE_CUSTOMER_ONLINE = 'customer-online';
    const TYPE_DIRECT_SALE = 'direct-sale';
    const NAME_TYPE = [
        self::TYPE_CUSTOMER_ONLINE => 'Nhắc lịch online',
        self::TYPE_DIRECT_SALE => 'Nhắc lịch direct sale',
    ];

    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_by']
                ],
                'value' => function () {
                    if ($this->created_by != null) {
                        return $this->created_by;
                    }
                    return Yii::$app->user->getId();
                }
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_by']
                ],
                'value' => function () {
                    return Yii::$app->user->getId();
                }
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['permission_user']
                ],
                'value' => function () {
                    if ($this->permission_user != null) {
                        return $this->permission_user;
                    }
                    return Yii::$app->user->getId();
                }
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['remind_call_status']
                ],
                'value' => function () {
                    return $this->remind_call_status === null ? self::STATUS_PUBLISHED : $this->remind_call_status;
                }
            ],
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at']
                ],
                'value' => time()
            ],
        ];
    }

    public static function tableName()
    {
        return "{{%dep365_customer_online_remind_call}}";
    }

    public static function find()
    {
        return new Dep365CustomerOnlineRemindCallQuery(get_called_class());
    }

    public function rules()
    {
        return [];
    }

    public function attributeLabels()
    {
        return [
            'note' => Yii::t('backend', 'Ghi chú nhắc lịch'),
            'remind_call_time' => Yii::t('backend', 'Khi nào nên gọi lại'),
            'type' => \Yii::t('backend', 'Loại'),
        ];
    }

    public function getCustomerHasOne()
    {
        return $this->hasOne(Dep365CustomerOnline::class, ['id' => 'customer_id']);
    }

    public function getCustomerOnlineDatHenStatus()
    {
        return $this->hasOne(Dep365CustomerOnlineDathenStatus::class, ['id' => 'dat_hen']);
    }

    public function getCustomerOnlineStatus()
    {
        return $this->hasOne(Dep365CustomerOnlineStatus::class, ['id' => 'status']);
    }

    public function getCustomerOnlineFailStatus()
    {
        return $this->hasOne(Dep365CustomerOnlineFailStatus::class, ['id' => 'status_fail']);
    }

    public function getCustomerOnlineFailDatHen()
    {
        return $this->hasOne(Dep365CustomerOnlineFailDathen::class, ['id' => 'dat_hen_fail']);
    }

    public function getCustomerOnlineCome()
    {
        return $this->hasOne(Dep365CustomerOnlineCome::class, ['id' => 'customer_come_time_to']);
    }

    public function getCustomerOnlineHasOne()
    {
        return $this->hasOne(Dep365CustomerOnline::class, ['id' => 'customer_id']);
    }

    public function getUserCreatedBy($id)
    {
        return UserProfile::find()->where(['user_id' => $id])->one();
    }
}
