<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tran
 * Date: 26-03-2019
 * Time: 11:58 AM
 */

namespace backend\modules\clinic\models\form;

use backend\modules\clinic\models\Clinic;
use yii\base\Model;
use Yii;

class FormAvatarCustomer extends Model
{
    public $id;
    public $fileImage;

    public function rules()
    {
        return [
            [['id'], 'required'],
            [['fileImage'], 'file', 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 1],
            [['id'], 'integer'],
            [['id'], 'checkCustomerExist'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'fileImage' => Yii::t('frontend', "Avatar khách hàng"),
        ];
    }

    public function checkCustomerExist()
    {
        $customer = Clinic::find()->where(['id' => $this->id])->one();
        if ($customer == null) {
            $this->addError('id', 'Không tìm thấy khách hàng!');
        }
    }
}
