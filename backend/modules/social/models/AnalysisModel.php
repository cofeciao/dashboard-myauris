<?php

namespace backend\modules\social\models;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_timeline".
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property int $created_at
 */
class AnalysisModel extends ActiveRecord
{
    public static function tableName()
    {
        return 'analysis_customer';
    }
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                'value' => time(),
            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'user_id',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['user_id'] // If usr_id is required
                ]
            ],
        ];
    }
    public function rules()
    {
        return [
            [['name'], 'safe']
        ];
    }
}
