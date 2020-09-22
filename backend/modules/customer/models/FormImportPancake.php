<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 11-Dec-18
 * Time: 9:19 AM
 */

namespace backend\modules\customer\models;

use Yii;
use yii\base\Model;

class FormImportPancake extends Model
{
    public $fileExcel;
    public $pagefacebook;

    public function rules()
    {
        return [
            [['fileExcel', 'pagefacebook'], 'required'],
            [['fileExcel'], 'file', 'extensions' => 'xls, xlsx, csv', 'maxSize' => 1024 * 1024 * 100],
            [['pagefacebook'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'fileExcel' => Yii::t('frontend', 'File Excel Pancake'),
            'pagefacebook' => 'Page facebook',
        ];
    }
}
