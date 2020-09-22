<?php

namespace backend\modules\clinic\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;
// use backend\modules\clinic\models\query\CheckcodeBaoHanhQuery;

/**
 * This is the model class for table "checkcode_bao_hanh".
 *
 * @property int $id
 * @property int $customer_id
 * @property string $warranty_code
 * @property string $product_code
 * @property string $product_name
 * @property int $date_buy
 * @property int $warranty_time
 * @property int $co_so
 * @property string $co_so_name
 * @property int $status
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $updated_at
 */
class CheckcodeBaoHanh extends \yii\db\ActiveRecord
{
    const STATUS_DISABLED = 0;
    const STATUS_PUBLISHED = 1;

    public static function tableName()
    {
        return 'checkcode_bao_hanh';
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
            ]
        ];
    }

    // public static function find()
    // {
    //     return new CheckcodeBaoHanhQuery(get_called_class());
    // }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'date_buy', 'warranty_time', 'co_so', 'status', 'created_at', 'created_by', 'updated_by', 'updated_at', 'product_id', 'phong_kham_don_hang_w_order_id', 'phong_kham_don_hang_id'], 'integer'],
            [['warranty_code', 'product_code', 'product_name', 'co_so_name', 'customer_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => 'Customer ID',
            'warranty_code' => 'Mã Bảo Hành',
            'product_code' => 'Product Code',
            'product_id' => 'Product ID',
            'product_name' => 'Product Name',
            'date_buy' => 'Date Buy',
            'warranty_time' => 'Warranty Time',
            'co_so' => 'Co So',
            'co_so_name' => 'Co So Name',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
            'phong_kham_don_hang_w_order_id' => 'w order id',
            'phong_kham_don_hang_id' => 'don hang id',
            'customer_name' => 'Tên khách hàng',
        ];
    }

    public function getUserCreatedBy($id)
    {
        if ($id == null)
            return null;
        $user = UserProfile::find()->where(['user_id' => $id])->one();
        return $user;
    }

    public function getUserUpdatedBy($id)
    {
        if ($id == null)
            return null;
        $user = UserProfile::find()->where(['user_id' => $id])->one();
        return $user;
    }
}
