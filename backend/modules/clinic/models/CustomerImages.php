<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tran
 * Date: 12-04-2019
 * Time: 09:36 AM
 */

namespace backend\modules\clinic\models;

use backend\components\GapiComponent;
use backend\modules\clinic\controllers\TkncController;
use backend\modules\customer\models\Dep365CustomerOnline;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use Yii;

class CustomerImages extends ActiveRecord
{
    const IMAGE_NORMAL = 0;
    const IMAGE_BEFORE = 1;
    const IMAGE_AFTER = 2;
    const IMAGE_TYPE = [
        'normal' => self::IMAGE_NORMAL,
        'before' => self::IMAGE_BEFORE,
        'after' => self::IMAGE_AFTER,
    ];

    public static function tableName()
    {
        return 'dep365_customer_images';
    }

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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at']
                ],
                'value' => time(),
            ],
        ];
    }

    public function rules()
    {
        return [
            [['customer_id', 'catagory_id'], 'required'],
            [['customer_id', 'catagory_id', 'status'], 'integer'],
            [['image'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'customer_id' => \Yii::t('backend', 'Khách hàng'),
            'catagory_id' => \Yii::t('backend', 'Loại'),
            'image' => \Yii::t('backend', 'Hình'),
            'status' => \Yii::t('backend', 'Trạng thái'),
        ];
    }

    public static function getListFilesByCustomer($customer_id, $catagory_id)
    {
        return self::find()->where(['customer_id' => $customer_id, 'catagory_id' => $catagory_id])->all();
    }

    public static function getImageBeforeAfter($customer_id)
    {
        $customer = Clinic::find()->where(['id' => $customer_id])->one();
        $imageBefore = null;
        $imageAfter = null;
        if ($customer != null) {
            $before = self::find()->where(['customer_id' => $customer_id, 'catagory_id' => Yii::$app->params['chup-hinh-catagory'][TkncController::FOLDER], 'type' => self::IMAGE_BEFORE])->one();
            $after = self::find()->where(['customer_id' => $customer_id, 'catagory_id' => Yii::$app->params['chup-hinh-catagory'][TkncController::FOLDER], 'type' => self::IMAGE_AFTER])->one();

            $gapi_service = null;
            if ($before != null) {
                if (file_exists(Url::to('@backend/web') . '/uploads/customer/' . $customer->slug . '-' . $customer->id . '/' . TkncController::FOLDER . '/' . $before->image)) {
                    $imageBefore = '/uploads/customer/' . $customer->slug . '-' . $customer->id . '/' . TkncController::FOLDER . '/' . $before->image;
                } else {
                    $gapi_service = GapiComponent::getClient();
                    $before = GapiComponent::getFile($gapi_service, $before->google_id);

                    if ($before != null) {
                        $imageBefore = $before['webContentLink'];
                    }
                }
            }

            if ($after != null) {
                if (file_exists(Url::to('@backend/web') . '/uploads/customer/' . $customer->slug . '-' . $customer->id . '/' . TkncController::FOLDER . '/' . $after->image)) {
                    $imageAfter = '/uploads/customer/' . $customer->slug . '-' . $customer->id . '/' . TkncController::FOLDER . '/' . $after->image;
                } else {
                    if ($gapi_service == null) $gapi_service = GapiComponent::getClient();
                    $after = GapiComponent::getFile($gapi_service, $after->google_id);

                    if ($after != null) {
                        $imageBefore = $after['webContentLink'];
                    }
                }
            }
        }
        return [
            'before' => $imageBefore,
            'after' => $imageAfter
        ];
    }

    public static function getFilesByCustomerByDetail($customer_id, $catagory_id, $google_id)
    {
        return self::find()->where(['customer_id' => $customer_id, 'catagory_id' => $catagory_id, 'google_id' => $google_id])->one();
    }

    public function getCustomerHasOne()
    {
        return $this->hasOne(Dep365CustomerOnline::class, ['id' => 'customer_id']);
    }
}
