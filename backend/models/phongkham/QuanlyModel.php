<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 23-Mar-19
 * Time: 4:12 PM
 */

namespace backend\models\phongkham;

use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use Yii;

class QuanlyModel extends \backend\models\CustomerModel
{
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::class,
                'updatedByAttribute' => 'updated_by',
            ],
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => time(),
            ],
        ];
    }

    public function rules()
    {
        return [
        ];
    }
}
