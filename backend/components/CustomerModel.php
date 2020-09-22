<?php

/**
 * Created by PhpStorm.
 * User: USER
 * Date: 15-Jan-19
 * Time: 2:48 PM
 */

namespace backend\components;

use backend\modules\clinic\models\CustomerImages;
use backend\modules\customer\models\Dep365CustomerOnlineFailStatus;
use backend\modules\customer\models\Dep365CustomerOnlineNguon;
use backend\modules\location\models\Province;
use backend\modules\setting\models\Dep365CoSo;
use backend\modules\clinic\models\PhongKhamDonHang;
use common\models\UserProfile;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Url;

class CustomerModel extends MyModel
{
    const SEX_MAN = 1;
    const SEX_WOMAN = 0;

    const STATUS_DH = 1;
    const STATUS_FAIL = 2;
    const STATUS_KBM = 3;
    const STATUS_AO = 4;

    public static function tableName()
    {
        return 'dep365_customer_online';
    }

    public static function getSex()
    {
        return [
            self::SEX_MAN => 'Nam Giới',
            self::SEX_WOMAN => 'Nữ Giới',
        ];
    }

    public function getProvinceHasOne()
    {
        return $this->hasOne(Province::class, ['id' => 'province']);
    }

    public function getNguonCustomerOnlineHasOne()
    {
        return $this->hasOne(Dep365CustomerOnlineNguon::class, ['id' => 'nguon_online']);
    }

    public function getFailStatusCustomerOnlineHasOne()
    {
        return $this->hasOne(Dep365CustomerOnlineFailStatus::class, ['id' => 'status_fail']);
    }

    public function getCoSoHasOne()
    {
        return $this->hasOne(Dep365CoSo::class, ['id' => 'co_so']);
    }


    public function getUserCreatedBy($id)
    {
        $user = UserProfile::find()->where(['user_id' => $id])->one() ?: '1';
        return $user;
    }

    public function getUserUpdatedBy($id)
    {
        $user = UserProfile::find()->where(['user_id' => $id])->one() ?: '1';
        return $user;
    }

    public function getDonHangs()
    {
        return $this->hasMany(PhongKhamDonHang::class, ['customer_id' => 'id']);
    }

    public function showImageGoogleDrive($customer_id, $slug, $folder)
    {
        $service = GapiComponent::getService();
        $cutomerImages = CustomerImages::getListFilesByCustomer($customer_id, Yii::$app->params['chup-hinh-catagory'][$folder]);
        $aImage = [];
        foreach ($cutomerImages as $image) {

            if (file_exists(Url::to('@backend/web') . '/uploads/customer/' . $slug . '-' . $customer_id . '/' . $folder . '/' . $image->image)) {
                $aImage[] = [
                    'type' => 'local',
                    'id' => $image->id,
                    'name' => $image->image,
                    'webContentLink' => Url::to('@web/uploads') . '/customer/' . $slug . '-' . $customer_id . '/' . $folder . '/' . $image->image,
                    'thumbnailLink' => Url::to('@web/uploads') . '/customer/' . $slug . '-' . $customer_id . '/' . $folder . '/thumb/' . $image->image,
                    'imageType' => $image->type
                ];
            } else {
                $getFile = GapiComponent::getFile($service, $image->google_id);
                if (isset($getFile['webContentLink'])) {
                    // cat lay link hinh anh
                    $webContentLink = chop($getFile['webContentLink'], "export=download");
                    $aImage[] = [
                        'type' => 'local',
                        'id' => $image->id,
                        'name' => $image->image,
                        'webContentLink' => $webContentLink,
                        'thumbnailLink' => $webContentLink,
                        'imageType' => $image->type
                    ];
                }
            }
        }
        return $aImage;
    }
}
