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

/**
 * This is the model class for table "table_temp".
 *
 * @property int $id
 * @property int $id_tinh_trang_rang
 * @property int $id_do_tuoi
 * @property string $image_before
 * @property string $image_after
 * @property int $status
 */
class TableTemp extends \yii\db\ActiveRecord
{
    const STATUS_DISABLED = 0;
    const STATUS_PUBLISHED = 1;

    public static function tableName()
    {
        return 'table_temp';
    }

    public function behaviors()
    {
        return [];
    }

    //    public static function find()
    //    {
    //        return new TableTempQuery(get_called_class());
    //    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_tinh_trang_rang', 'id_do_tuoi'], 'required'],
            [['id_tinh_trang_rang', 'id_do_tuoi', 'status', 'nguoi_noi_tieng'], 'integer'],
            [['image_before', 'image_after'], 'string', 'max' => 255],
            [['name'],'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_tinh_trang_rang' => 'Id Tinh Trang Rang',
            'id_do_tuoi' => 'Id Do Tuoi',
            'image_before' => 'Image Before',
            'image_after' => 'Image After',
            'status' => 'Status',
            'nguoi_noi_tieng' => 'Nguoi Noi Tieng',
            'name' => 'Name',
        ];
    }

    public function getTinhTrangRang()
    {
        $list = ArrayHelper::map(TinhTrangRang::getListTinhTrangRangAPI(), 'id', 'name');
        return ($list[$this->id_tinh_trang_rang]) ? $list[$this->id_tinh_trang_rang] : "";
    }

    public function getListDoTuoi()
    {
        $list = ArrayHelper::map(DoTuoi::getListDoTuoi(), 'id', 'name');
        return ($list[$this->id_do_tuoi]) ? $list[$this->id_do_tuoi] : "";
    }
}
