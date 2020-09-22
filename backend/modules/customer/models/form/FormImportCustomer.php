<?php

namespace backend\modules\customer\models\form;

class FormImportCustomer extends \yii\base\Model
{
    public $name;
    public $phone;
    public $avatar;
    public $fileChupHinh;
    public $fileChupBanhMoi;
    public $fileChupCui;
    public $fileChupFinal;
    public $fileTknc;

    public function rules()
    {
        return [
            [['name', 'phone'], 'required'],
            [['name'], 'string', 'max' => 255],
            ['phone', 'telnumvn', 'exceptTelco' => ['landLine']],
            [['avatar'], 'file', 'extensions' => ['png', 'jpg'], 'maxSize' => 1024 * 1024 * 5, 'wrongExtension' => 'Chỉ chấp nhận file có định dạng: {extensions}'],
            [['fileChupHinh', 'fileChupBanhMoi', 'fileChupCui', 'fileChupFinal', 'fileTknc'], 'file', 'extensions' => ['png', 'jpg'], 'maxSize' => 1024 * 1024 * 100, 'maxFiles' => 100, 'wrongExtension' => 'Chỉ chấp nhận file có định dạng: {extensions}'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => \Yii::t('backend', 'Họ tên'),
            'phone' => \Yii::t('backend', 'Điện thoại'),
            'avatar' => \Yii::t('backend', 'Hình đại diện'),
            'fileChupHinh' => \Yii::t('backend', 'Hình chụp hình'),
            'fileChupBanhMoi' => \Yii::t('backend', 'Hình chụp banh môi'),
            'fileChupCui' => \Yii::t('backend', 'Hình chụp cùi'),
            'fileChupFinal' => \Yii::t('backend', 'Hình chụp kết thúc'),
            'fileTknc' => \Yii::t('backend', 'Hình thiết kế nụ cười'),
        ];
    }
}
