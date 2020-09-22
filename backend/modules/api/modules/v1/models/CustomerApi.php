<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tran
 * Date: 23-04-2019
 * Time: 04:44 PM
 */

namespace backend\modules\api\models;

use backend\modules\booking\models\UserRegister;
use common\helpers\MyHelper;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;

class CustomerApi extends UserRegister
{
    public function behaviors()
    {
        return [
            'slug' => [
                'class' => SluggableBehavior::class,
//                'attribute' => 'name',
//                'slugAttribute' => 'slug',
                'immutable' => false, //only create 1
                'ensureUnique' => true, //
                'value' => function () {
                    return MyHelper::createAlias($this->name);
                }
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

    public function rules()
    {
        return [
            [['name', 'phone'], 'required'],
            [['name'], 'string', 'max' => 255],
            ['phone', 'telnumvn', 'exceptTelco' => ['landLine']],
        ];
    }
}
