<?php

namespace backend\modules\customer\models;

use backend\modules\setting\models\Setting;
use common\helpers\MyHelper;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;
use backend\modules\customer\models\query\CustomerTokenQuery;
use yii\db\Exception;

/**
 * This is the model class for table "customer_token".
 *
 * @property int $id
 * @property int $customer_id
 * @property string $token
 * @property int $type Loại đánh giá
 * @property int $status Trạng thái. 0 - chưa sử dụng, 1 - đã sử dụng
 * @property int $expired_at null = forever
 * @property int $created_at
 */
class CustomerToken extends \yii\db\ActiveRecord
{
    const STATUS_DISABLED = 0;
    const STATUS_PUBLISHED = 1;

    const TYPE_CUSTOMER_FEEDBACK = 1;
    const TYPE = [
        self::TYPE_CUSTOMER_FEEDBACK => 'Feedback khách hàng'
    ];

    public $time;

    public static function tableName()
    {
        return 'customer_token';
    }

    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['token']
                ],
                'value' => function () {
                    if ($this->token == null) {
                        return MyHelper::randomString(10);
                    }
                    return $this->token;
                }
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at']
                ],
                'value' => time()
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['expired_at']
                ],
                'value' => function () {
                    if ($this->time != null && is_numeric($this->time)) {
                        return time() + $this->time;
                    }
                    return null;
                }
            ]
        ];
    }

    public static function find()
    {
        return new CustomerTokenQuery(get_called_class());
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'type'], 'required'],
            [['customer_id', 'type', 'status', 'expired_at', 'time'], 'integer'],
            [['token'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'customer_id' => Yii::t('backend', 'Khách hàng'),
            'token' => Yii::t('backend', 'Token'),
            'type' => Yii::t('backend', 'Type'),
            'status' => Yii::t('backend', 'Status'),
            'expired_at' => Yii::t('backend', 'Ngày giờ hết hạn'),
            'created_at' => Yii::t('backend', 'Thời gian tạo'),
        ];
    }

    public function getCustomerHasOne()
    {
        return $this->hasOne(Dep365CustomerOnline::class, ['id' => 'customer_id']);
    }

    public static function quickCreate($customer_id, $token = null, $time = null, $type = self::TYPE_CUSTOMER_FEEDBACK)
    {
        $model = new self();
        $model->setAttributes([
            'customer_id' => $customer_id,
            'token' => $token,
            'time' => $time,
            'type' => $type
        ]);
        try {
            return $model->save();
        } catch (Exception $ex) {
            return false;
        }
    }

    public static function getByToken($token)
    {
        return self::find()->joinWith(['customerHasOne'])->where(['token' => $token])->one();
    }
}
