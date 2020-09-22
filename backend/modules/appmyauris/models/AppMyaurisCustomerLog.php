<?php

namespace backend\modules\appmyauris\models;

use common\models\UserProfile;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Html;

/**
 * This is the model class for table "app_myauris_customer_log".
 *
 * @property int $id
 * @property int $customer_id
 * @property array $tu_van
 * @property array $don_hang
 * @property int $status
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $updated_at
 */
class AppMyaurisCustomerLog extends \yii\db\ActiveRecord
{
    const STATUS_DISABLED = 0;
    const STATUS_PUBLISHED = 1;

    public static function tableName()
    {
        return 'app_myauris_customer_log';
    }

    public static function showButtonViewDonHang($customer_id)
    {

        if (self::countDonHang($customer_id)) {
            return Html::a('<i class="p-icon relative ft-clipboard blue"><i class="fa fa-file-text-o"></i></i>', ['/appmyauris/app-myauris-customer-log/view', 'customer_id' => $customer_id], [
                'title' => 'Đơn nháp',
                'class' => 'btn btn-default',
                'data-pjax' => 0,
                'target' => "_blank",
            ]);
        }
        return "";
    }

    public static function countDonHang($customer_id)
    {
        return self::find()->where(['customer_id' => $customer_id])->count();
    }

    public static function getOneAppMyaurisCustomerLogByCustomerID($customer_id)
    {
        return self::find()->where(['customer_id' => $customer_id])->orderBy(['id' => SORT_DESC])->one();
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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'status', 'created_at', 'created_by', 'updated_by', 'updated_at'], 'integer'],
            [['tu_van', 'don_hang'], 'safe'],
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
            'tu_van' => 'Tu Van',
            'don_hang' => 'Don Hang',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }

    /*
     * Kiem tra thong tin don hang nhap cua Khach hang
     */

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
