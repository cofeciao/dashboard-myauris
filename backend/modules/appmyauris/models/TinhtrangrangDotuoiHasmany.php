<?php

namespace backend\modules\appmyauris\models;

use backend\modules\toothstatus\models\DoTuoi;
use backend\modules\toothstatus\models\TinhTrangRang;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;
use yii\helpers\ArrayHelper;

//use backend\modules\appmyauris\models\query\TinhtrangrangDotuoiHasmanyQuery;

/**
 * This is the model class for table "tinhtrangrang_dotuoi_hasmany".
 *
 * @property int $id
 * @property int $do_tuoi
 * @property int $tinh_trang
 * @property int $status
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $updated_at
 */
class TinhtrangrangDotuoiHasmany extends \yii\db\ActiveRecord
{
    const STATUS_DISABLED = 0;
    const STATUS_PUBLISHED = 1;

    public static function tableName()
    {
        return 'tinhtrangrang_dotuoi_hasmany';
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

//    public static function find()
//    {
//        return new TinhtrangrangDotuoiHasmanyQuery(get_called_class());
//    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['do_tuoi', 'tinh_trang', 'status', 'created_at', 'created_by', 'updated_by', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'do_tuoi' => 'Do Tuoi',
            'tinh_trang' => 'Tinh Trang',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
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

    public function getDoTuoi(){
        $list  = ArrayHelper::map( DoTuoi::getListDoTuoi(),'id','name');
        return ($list[$this->do_tuoi]) ? $list[$this->do_tuoi] : "";
    }

    public function getTinhTrangRang(){
        $list = ArrayHelper::map(TinhTrangRang::getListTinhTrangRang(),'id','name');
        return ($list[$this->tinh_trang]) ? $list[$this->tinh_trang] : "";
    }

}
