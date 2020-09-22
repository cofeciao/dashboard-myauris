<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 11-Dec-18
 * Time: 9:19 AM
 */

namespace backend\modules\clinic\models\form;

use Yii;
use yii\base\Model;

class FormChupFinal extends Model
{
    public $fileImage;
    public $id;

    public function rules()
    {
        return [
            [['fileImage'], 'file', 'extensions' => ['png', 'jpg', 'jpeg'], 'maxSize' => 1024 * 1024 * 1, 'maxFiles' => 100, 'wrongExtension'=>'Chỉ chấp nhận file có định dạng: {extensions}'],
            [['id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'fileImage' => Yii::t('frontend', 'Hình chụp kết thúc của khách hàng'),
        ];
    }
}
