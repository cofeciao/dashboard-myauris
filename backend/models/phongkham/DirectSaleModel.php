<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 23-Mar-19
 * Time: 4:12 PM
 */

namespace backend\models\phongkham;

use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use Yii;

class DirectSaleModel extends \backend\models\CustomerModel
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

            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'customer_code',
                ],
                'value' => function () {
                    if ($this->customer_code == null) {
                        if (strlen(Yii::$app->user->identity->permission_coso) == 1) {
                            $coso = '0' . Yii::$app->user->identity->permission_coso;
                        } else {
                            $coso = Yii::$app->user->identity->permission_coso;
                        }
                        return 'AUR' . $coso . '-' . $this->primaryKey;
                    } else {
                        return $this->customer_code;
                    }
                },
            ],
        ];
    }

    public function rules()
    {
        return [
            [['birthday', 'full_name'], 'string', 'max' => 255],
            [['customer_come_time_to'], 'integer'],
            [['customer_come'], 'safe'],
            [['customer_come'], 'required'],
            [['note_direct', 'customer_mongmuon', 'customer_huong_dieu_tri'], 'string']
        ];
    }
}
