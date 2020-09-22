<?php

namespace backend\modules\directsale\models;

use backend\models\Dep365CustomerOnlineRemindCall;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "dep365_customer_online_remind_call".
 *
 * @property int $id
 * @property int $customer_id
 * @property int $status
 * @property int $status_fail
 * @property int $dat_hen 1 - Đã đến 2- Không đến
 * @property string $type
 * @property string $note
 * @property int $remind_call_time
 * @property int $permission_user Quyền thuộc về nhân viên nào
 * @property int $remind_call_status
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 */
class DirectSaleRemindCall extends Dep365CustomerOnlineRemindCall
{
    public $take_care;

    public static function tableName()
    {
        return 'dep365_customer_online_remind_call';
    }

    public function behaviors()
    {
        return array_merge([
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['type']
                ],
                'value' => function () {
                    return parent::TYPE_DIRECT_SALE;
                }
            ]
        ], parent::behaviors());
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge([
            [['customer_id', 'status', 'status_fail', 'dat_hen', 'remind_call_status', 'take_care'], 'integer'],
            [['note'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 50],
            [['remind_call_time'], 'safe'],
        ], parent::rules());
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge([
            'customer_id' => Yii::t('backend', 'Khách hàng'),
            'dat_hen' => Yii::t('backend', 'Trạng thái khách'),
        ], parent::attributeLabels());
    }

    public static function getTotalRemindToday()
    {
        $cache = \Yii::$app->cache;
        $key = 'get-total-remind-today-of-direct-sale-menu-left';

        $data = $cache->get($key);

        if ($data == false) {
            $data = self::find()
                ->where(['type' => parent::TYPE_DIRECT_SALE, 'remind_call_date' => strtotime(date('d-m-Y'))])
                ->published()->count();
            $cache->set($key, $data, 18 * 3600);
        }
        return $data;
    }
}
