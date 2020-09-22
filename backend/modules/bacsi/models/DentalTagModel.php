<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tran
 * Date: 08-04-2019
 * Time: 02:53 PM
 */

namespace backend\modules\bacsi\models;

use backend\modules\toothstatus\models\TinhTrangRang;
use cornernote\linkall\LinkAllBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class DentalTagModel extends ActiveRecord
{
    public $tinh_trang_rang;

    public static function tableName()
    {
        return 'dep365_customer_online_dental_tag';
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
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at']
                ],
                'value' => time()
            ],
            LinkAllBehavior::class
        ];
    }

    public function rules()
    {
        return [
            [['customer_id', 'tag', 'tinh_trang_rang'], 'required'],
            [['tinh_trang_rang'], 'required'/*, 'when' => function ($model) {
                return $model->ketqua_thamkham == null;
            }, 'whenClient' => "function(){
                return $('.ketqua-thamkham-" . $this->tag . "').val().trim() == '';
            }"*/],
            /*[['ketqua_thamkham'], 'required', 'when' => function ($model) {
                return $model->tinh_trang_rang == null || (is_array($model->tinh_trang_rang) && count($model->tinh_trang_rang) <= 0);
            }, 'whenClient' => "function(){
                return typeof $('.dropdown-tinh-trang-rang-" . $this->tag . " select').val() !== 'object' || $('.dropdown-tinh-trang-rang-" . $this->tag . " select').val().length <= 0;
            }"],*/
            [['customer_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['tag'], 'string', 'max' => 50],
            [['ketqua_thamkham'], 'string'],
            [['tinh_trang_rang'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'customer_id' => \Yii::t('backend', 'Khách hàng'),
            'tag' => \Yii::t('backend', 'Tag'),
            'ketqua_thamkham' => \Yii::t('backend', 'Ghi chú thăm khám'),
            'tinh_trang_rang' => \Yii::t('backend', 'Tình trạng răng'),
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        $tinh_trang_rangs = [];
        if (is_array($this->tinh_trang_rang)) {
            foreach ($this->tinh_trang_rang as $tinh_trang_rang) {
                $tinh_trang_rang = TinhTrangRang::getTagByName($tinh_trang_rang);
                if ($tinh_trang_rang) {
                    $tinh_trang_rangs[] = $tinh_trang_rang;
                }
            }
        }
        $this->linkAll('tinhTrangRangHasMany', $tinh_trang_rangs);
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    public function getTinhTrangRangHasMany()
    {
        return $this->hasMany(TinhTrangRang::class, ['id' => 'tinh_trang_rang'])
            ->viaTable('tinhtrangrang_tag_hasmany', ['tag' => 'id']);
    }
}