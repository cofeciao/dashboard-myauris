<?php

namespace backend\modules\customer\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;

class Pancake extends \yii\db\ActiveRecord
{
    const TUONG_TAC_AO = 1;

    public $NUM;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pancake';
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
            ],
        ];
    }
}
