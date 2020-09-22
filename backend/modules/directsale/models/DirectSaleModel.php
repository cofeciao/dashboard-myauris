<?php

namespace backend\modules\directsale\models;

use backend\modules\customer\models\Dep365CustomerOnlineCome;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class DirectSaleModel extends \backend\models\CustomerModel
{
    public $remind_call_time;
    public $remind_call_note;

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
            [['birthday', 'full_name', 'remind_call_note'], 'string', 'max' => 255],
            [['customer_come_time_to'], 'integer'],
            [['customer_come'], 'safe'],
            [['customer_come'], 'required'],
            [['note_direct', 'customer_mongmuon', 'customer_huong_dieu_tri'], 'string'],
            [['remind_call_time'], 'safe'],
            [['ly_do_khong_lam'], 'required', 'when' => function () {
                $listAccept = ArrayHelper::map(Dep365CustomerOnlineCome::find()->published()->andWhere(['accept' => Dep365CustomerOnlineCome::STATUS_ACCEPT])->all(), 'id', 'name');
                return !in_array($this->customer_come_time_to, $listAccept);
            }],
            [['ly_do_khong_lam'], 'integer', 'when' => function () {
                $listAccept = ArrayHelper::map(Dep365CustomerOnlineCome::find()->published()->andWhere(['accept' => Dep365CustomerOnlineCome::STATUS_ACCEPT])->all(), 'id', 'name');
                return !in_array($this->customer_come_time_to, $listAccept);
            }],
        ];
    }

    public function attributeLabels()
    {
        return array_merge([
            'remind_call_time' => Yii::t('backend', 'Khi nào nên gọi lại'),
            'remind_call_note' => Yii::t('backend', 'Lý do khách không làm'),
        ], parent::attributeLabels());
    }
}
