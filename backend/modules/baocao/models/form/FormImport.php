<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 11-Dec-18
 * Time: 9:19 AM
 */

namespace backend\modules\baocao\models\form;

use Yii;
use yii\base\Model;

class FormImport extends Model
{
    public $fileExcel;

    public function rules()
    {
        return [
            [['fileExcel'], 'required'],
            [['fileExcel'], 'file', 'extensions' => 'xls, xlsx, csv', 'maxSize' => 1024 * 1024 * 100],
        ];
    }

    public function attributeLabels()
    {
        return [
            'fileExcel' => Yii::t('frontend', 'File Excel'),
        ];
    }
}
