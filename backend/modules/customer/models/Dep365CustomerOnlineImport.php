<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tran
 * Date: 03-05-2019
 * Time: 02:26 PM
 */

namespace backend\modules\customer\models;

use backend\models\CustomerModel;
use common\helpers\MyHelper;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use Yii;

class Dep365CustomerOnlineImport extends Dep365CustomerOnline
{
    const CUSTOMER_OLD = 1;

    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['full_name'],
                ],
                'value' => function () {
                    return $this->name;
                },
            ],
            'slug' => [
                'class' => SluggableBehavior::class,
                'immutable' => false, //only create 1
                'ensureUnique' => true, //
                'value' => function () {
                    return MyHelper::createAlias($this->name);
                }
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'is_customer_who',
                ],
                'value' => function () {
                    return self::IS_CUSTOMER_TECH;
                },
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'sex',
                ],
                'value' => function () {
                    return 0;
                },
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'customer_code',
                ],
                'value' => function () {
                    if ($this->customer_code == null) {
                        if (strlen($this->co_so) == 1) {
                            $coso = '0' . $this->co_so;
                        } else {
                            $coso = $this->co_so;
                        }
                        return 'AUR' . $coso . '-' . $this->primaryKey;
                    } else {
                        return $this->customer_code;
                    }
                },
            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['customer_old']
                ],
                'value' => self::CUSTOMER_OLD
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['status']
                ],
                'value' => CustomerModel::STATUS_DH
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['dat_hen']
                ],
                'value' => CustomerModel::DA_DEN
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'ngay_tao',
                ],
                'value' => function () {
                    $date = date('d-m-Y', $this->created_at);
                    return strtotime($date);
                },
            ],
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => time()
            ]
        ];
    }

    public function rules()
    {
        return [
            [['name', 'phone', 'co_so', 'province'], 'required'],
            [['co_so', 'province', 'ngay_tao'], 'integer'],
            [['name', 'face_customer', 'birthday'], 'string', 'max' => 255],
            ['phone', 'telnumvn', 'exceptTelco' => ['landLine'], 'when' => function () {
                return $this->province != 97;
            }, 'whenClient' => 'function(){
                return $("#dep365customeronlineimport-province").val() != 97;
            }'],
            [['avatar'], 'file', 'extensions' => ['png', 'jpg'], 'maxSize' => 1024 * 1024 * 5, 'wrongExtension' => 'Chỉ chấp nhận file có định dạng: {extensions}'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => \Yii::t('backend', 'Họ tên'),
            'phone' => \Yii::t('backend', 'Điện thoại'),
            'face_customer' => \Yii::t('backend', 'Facebook khách hàng'),
            'avatar' => \Yii::t('backend', 'Hình đại diện'),
            'birthday' => \Yii::t('backend', 'Ngày sinh'),
            'co_so' => \Yii::t('backend', 'Cơ sở'),
            'province' => \Yii::t('backend', 'Tỉnh thành'),
        ];
    }
}
