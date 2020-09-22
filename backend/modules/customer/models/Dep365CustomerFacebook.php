<?php

namespace backend\modules\customer\models;

use common\helpers\MyHelper;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use Yii;

class Dep365CustomerFacebook extends ActiveRecord
{
    const CUSTOMER_WITH_ONLINE = 1;

    public static function tableName()
    {
        return 'dep365_customer_facebook';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::class,
                'immutable' => false,
                'ensureUnique' => true,
                'value' => function () {
                    return MyHelper::createAlias($this->name);
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
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['permission_user']
                ],
                'value' => Yii::$app->user->getId()
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['is_customer_who']
                ],
                'value' => self::CUSTOMER_WITH_ONLINE
            ]
        ];
    }

    public function rules()
    {
        return [
            [['name', 'face_customer'], 'required'],
            [['name', 'face_customer'], 'string', 'max' => 255],
            [['face_fanpage', 'face_post_id'], 'integer'],
            [['face_customer'], 'unique', 'filter' => function ($query) {
                $query->andWhere(['not', ['id' => $this->id]]);
            }]
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('backend', 'Họ tên'),
            'face_customer' => Yii::t('backend', 'Link facebook'),
            'face_fanpage' => Yii::t('backend', 'Fanpage'),
            'face_post_id' => Yii::t('backend', 'Face post ID'),
        ];
    }
}
