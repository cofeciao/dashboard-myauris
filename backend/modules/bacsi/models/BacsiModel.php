<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tran
 * Date: 06-04-2019
 * Time: 11:02 AM
 */

namespace backend\modules\bacsi\models;

use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;

class BacsiModel extends \backend\models\CustomerModel
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
            [['birthday', 'full_name'], 'string', 'max' => 255],
            [['customer_come_time_to', 'customer_come'], 'integer'],
            [['customer_huong_dieu_tri', 'customer_ghichu_bacsi'], 'string', 'max' => 255],
        ];
    }
}
