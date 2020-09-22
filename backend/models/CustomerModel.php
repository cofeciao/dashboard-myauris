<?php

/**
 * Created by PhpStorm.
 * User: USER
 * Date: 15-Jan-19
 * Time: 2:48 PM
 */

namespace backend\models;

use backend\components\GapiComponent;
use backend\components\MyModel;
use backend\models\doanhthu\ThanhToanModel;
use backend\modules\bacsi\models\DentalTagModel;
use backend\modules\clinic\models\CustomerImages;
use backend\modules\clinic\models\PhongKhamDonHang;
use backend\modules\clinic\models\PhongKhamImageBeforeAfter;
use backend\modules\clinic\models\PhongKhamLichDieuTri;
use backend\modules\cskh\models\CskhQuanLy;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\customer\models\Dep365CustomerOnlineCome;
use backend\modules\customer\models\Dep365CustomerOnlineDichVu;
use backend\modules\customer\models\Dep365CustomerOnlineDathenStatus;
use backend\modules\customer\models\Dep365CustomerOnlineDathenTime;
use backend\modules\customer\models\Dep365CustomerOnlineFailDathen;
use backend\modules\customer\models\Dep365CustomerOnlineGenitive;
use backend\modules\customer\models\Dep365CustomerOnlineStatus;
use backend\modules\location\models\District;
use backend\modules\user\models\User;
use Yii;
use backend\modules\customer\models\Dep365CustomerOnlineFailStatus;
use backend\modules\customer\models\Dep365CustomerOnlineNguon;
use backend\modules\location\models\Province;
use backend\modules\setting\models\Dep365CoSo;
use common\models\UserProfile;
use yii\db\ActiveRecord;
use yii\elasticsearch\Connection;
use yii\helpers\Url;

class CustomerModel extends MyModel
{
    const SEX_MAN = 1;
    const SEX_WOMAN = 0;

    const STATUS_DH = 1;
    const STATUS_FAIL = 2;
    const STATUS_KBM = 3;
    const STATUS_AO = 4;

    const DA_DEN = 1;

    const STATUS_NGUON_KHACH_VANG_LAI = 8;

    const IS_CUSTOMER_TECH = 3;
    const IS_CUSTOMER_LETAN = 2;
    const IS_CUSTOMER_TV_ONLINE = 1;

    const IS_AGENCY_365 = 1;

    const PHONE_CREATE = 'phone_create';

    public $TOTALKH;
    public $SDT;

    public $danh_gia;
    public $user;
    public $directSale;
    public $remind_id;
    public $remind_call_time;
    public $note_remind_call;

    public static function tableName()
    {
        return 'dep365_customer_online';
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'customer_code' => Yii::t('backend', 'Mã khách hàng'),
            'forename' => Yii::t('backend', 'Tên'),
            'full_name' => 'Họ và tên',
            'name' => Yii::t('backend', 'Nick Name'),
            'avatar' => Yii::t('backend', 'Ảnh đại diện'),
            'slug' => Yii::t('backend', 'Slug'),
            'phone' => Yii::t('backend', 'Phone'),
            'sex' => Yii::t('backend', 'Sex'),
            'birthday' => Yii::t('backend', 'Ngày sinh'),
            'status' => Yii::t('backend', 'Status Customer'),
            'nguon_online' => Yii::t('backend', 'Nguồn trực tuyến'),
            'province' => Yii::t('backend', 'Province'),
            'district' => Yii::t('backend', 'District'),
            'face_fanpage' => Yii::t('backend', 'Page Facebook'),
            'face_post_id' => Yii::t('backend', 'ID Post Facebook'),
            'note' => Yii::t('backend', 'Nội Dung Online Tư Vấn'), // Note
            'tt_kh' => Yii::t('backend', 'Tình trạng răng khách hàng'),
            'ngaythang' => Yii::t('backend', 'Ngày đăng ký'),
            'time_lichhen' => Yii::t('backend', 'Ngày giờ lịch hẹn'),
            'co_so' => Yii::t('backend', 'Cơ sở'),
            'permission_user' => Yii::t('backend', 'Nhân Viên'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
            'phoneConfirm' => 'Xác nhận trùng số điện thoại',
            'status_fail' => 'Lý do fail',
            'dat_hen_fail' => 'Lý do không đến',
            'dat_hen' => 'Đặt hẹn',
            'face_customer' => 'Link FB khách hàng',
            'agency_id' => 'Agency',
            'note_direct' => 'Ghi chú Direct',
            'updateCustomer' => 'Chỉnh sửa khách',
            'customer_come' => 'Khách đến',
            'customer_gen' => 'Tính cách khách hàng',
            'address' => 'Địa chỉ',
            'customer_thamkham' => 'Kết quả thăm khám',
            'customer_mongmuon' => 'Mong muốn khách hàng',
            'customer_come_time_to' => 'Trạng thái khách',
            'ly_do_khong_lam' => 'Lý do khách không làm',
            'is_customer_who' => 'Bộ phận tạo khách hàng',
            'is_affiliate_created' => 'Đã khởi tạo Affiliate',
            'directSale' => 'Direct sale',
            'directsale' => 'Direct sale',
            'customer_huong_dieu_tri' => 'Hướng điều trị',
            'customer_ghichu_bacsi' => 'Bác sĩ ghi chú',
            'customer_direct_sale_checkthammy' => 'Thẩm mỹ',
            'customer_bacsi_check_final' => 'Hoàn thành',
            'danh_gia' => 'Đánh giá',
            'id_dich_vu' => 'Dịch vụ',
            'affiliate_id' => 'ID Affiliate',
            'nguoi_gioi_thieu' => 'Người giới thiệu',
            "note_tinh_trang_kh" => "Tình Trạng Khách Hàng", //13-1-2019
            "note_mong_muon_kh" => " Mong Muốn Khách Hàng",
            "note_direct_sale_ho_tro" => "Nội Dung Online Gửi Direct Sale Hổ Trợ",
            'ngay_dong_y_lam' => 'Ngày đồng ý làm'
        ];
    }

    public function afterDelete()
    {
        $phone = $this->getAttribute('phone');
        $cache = Yii::$app->cache;
        $key = 'get-customers-by-phone-one-' . $phone;
        $cache->delete($key);
        parent::afterDelete(); // TODO: Change the autogenerated stub
    }

    public function afterSave($insert, $changedAttributes)
    {
        $phone = $this->getAttribute('phone');
        $cache = Yii::$app->cache;
        $key = 'get-customers-by-phone-one-' . $phone;
        $cache->delete($key);

        $key1 = 'load-user-timeline-site';
        $cache->delete($key1);

        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    public function getThanhToanHasMany()
    {
        return $this->hasMany(ThanhToanModel::class, ['customer_id' => 'id']);
    }

    public function getLastDieuTriHasMany()
    {
        return $this->hasMany(PhongKhamLichDieuTri::class, ['customer_id' => 'id'])->andWhere([PhongKhamLichDieuTri::tableName() . '.last_dieu_tri' => PhongKhamLichDieuTri::STATUS_PUBLISHED]);
    }

    //Khách do online tạo hay lễ tân tạo
    public static function getCustomerForWho()
    {
        return [
            self::IS_CUSTOMER_TV_ONLINE => 'Khách của online tạo',
            self::IS_CUSTOMER_LETAN => 'Khách của lễ tân tạo',
            self::IS_CUSTOMER_TECH => 'Khách hàng được nhập tự động',
        ];
    }

    public static function getNguonCustomerOnline()
    {
        $result = [];
        $data = Dep365CustomerOnlineNguon::getNguonCustomerOnline();
        foreach ($data as $item) {
            $result[$item->id] = $item->name;
        }
        return $result;
    }

    public static function getStatusCustomerGoToAuris($isLetan = null)
    {
        return Dep365CustomerOnlineCome::getCustomerOnlineCome($isLetan);
    }

    public function getDichVuOnlineHasOne()
    {
        return $this->hasOne(Dep365CustomerOnlineDichVu::class, ['id' => 'id_dich_vu']);
    }

    public static function getDichVuOnline()
    {
        $data = Dep365CustomerOnlineDichVu::find()->published()->all();
        $result = [];
        foreach ($data as $item) {
            $result[$item->id] = $item->name;
        }
        return $result;
    }

    /*
     * Lấy direct sale
     */

    public function getDirectSaleHasOne()
    {
        return $this->hasOne(UserProfile::class, ['user_id' => 'directsale']);
    }

    public function getPhongKhamLichDieuTriHasOne()
    {
        return $this->hasOne(PhongKhamLichDieuTri::class, ['customer_id' => 'id']);
    }

    public function getCustomerOnlineComeHasOne()
    {
        return $this->hasOne(Dep365CustomerOnlineCome::class, ['id' => 'customer_come_time_to']);
    }

    public function getImageBeforeAfterHasOne()
    {
        return $this->hasOne(PhongKhamImageBeforeAfter::class, ['customer_id' => 'id']);
    }

    /*
     * Lấy đơn hàng
     */
    public function getOrderHasOne()
    {
        return $this->hasOne(PhongKhamDonHang::class, ['customer_id' => 'id']);
    }

    public function getDonHangs()
    {
        return $this->hasMany(PhongKhamDonHang::class, ['customer_id' => 'id']);
    }

    /*
     * Trả về khách hàng có id tương ứng
     */
    public function getOneCustomer($idCustomer)
    {
        $customer = self::findOne($idCustomer);
        if ($customer) {
            return $customer->full_name == null ? $customer->forename : $customer->full_name;
        }
        return null;
    }

    public static function getNhanVienTuVanFilter()
    {
        $cache = Yii::$app->cache;
        $key = 'resdis-get-nhanvien-tu-van-filter';

        $result = $cache->get($key);
        if ($result === false) {
            $userProfile = User::getNhanVienIsActive();
            $result = [];
            foreach ($userProfile as $item) {
                if ($item->userProfile != null) {
                    if ($item->userProfile->fullname == '') {
                        $result[$item->id] = '-';
                    } else {
                        $result[$item->id] = $item->userProfile->fullname;
                    }
                }
            }
            $cache->set($key, $result);
        }

        return $result;
    }

    public static function getCustomersByPhone($phone = null)
    {
        if ($phone == null)
            return null;
        $cache = Yii::$app->cache;
        $key = 'get-customers-by-phone-' . $phone;

        $data = $cache->get($key);

        if ($data == false) {
            $data = self::find()->where(['phone' => $phone])->all();
            $cache->set($key, $data, 86400 * 30);
        }

        return $data;
    }

    public static function getCustomerByPhoneOne($phone)
    {
        if ($phone == null)
            return null;
        $cache = Yii::$app->cache;
        $key = 'get-customers-by-phone-one-' . $phone;

        $data = $cache->get($key);

        if ($data == false) {
            $data = self::find()->where(['phone' => $phone])->orderBy(['id' => SORT_DESC])->limit(1)->one();
            $cache->set($key, $data, 86400 * 30);
        }

        return $data;
    }

    public static function getNhanVienOnlineNLeTanFilter()
    {
        $cache = Yii::$app->cache;
        $key = 'resdis-get-nhanvien-oneline-letan';

        $result = $cache->get($key);
        if ($result === false) {
            $userProfile = User::getNhanVienOnlineNLeTan();
            $result = [];
            foreach ($userProfile as $item) {
                if ($item->userProfile->fullname == '') {
                    $result[$item->id] = '-';
                } else {
                    $result[$item->id] = $item->userProfile->fullname;
                }
            }
            $cache->set($key, $result);
        }

        return $result;
    }

    public function getDep365CustomerOnlineDathenTimeHasOne()
    {
        return $this->hasOne(Dep365CustomerOnlineDathenTime::class, ['customer_online_id' => 'id']);
    }

    public function getStatusCustomerGotoAurisHasOne()
    {
        return $this->hasOne(Dep365CustomerOnlineCome::class, ['id' => 'customer_come_time_to']);
    }

    public function getDistrictHasOne()
    {
        return $this->hasOne(District::class, ['id' => 'district']);
    }

    public function getNhanVienTuVan($id)
    {
        $userProfile = UserProfile::getUserCreatedOrUpdateBy($id);
        return $userProfile == null ? null : $userProfile->fullname;
    }

    public function getStatusDatHenHasOne()
    {
        return $this->hasOne(Dep365CustomerOnlineDathenStatus::class, ['id' => 'dat_hen']);
    }

    public function getStatusCustomerHasOne()
    {
        return $this->hasOne(Dep365CustomerOnlineStatus::class, ['id' => 'status']);
    }

    public static function getStatusDatHen()
    {
        $data = Dep365CustomerOnlineDathenStatus::getDatHenStatus();
        $result = [];
        foreach ($data as $item) {
            $result[$item->id] = $item->name;
        }
        return $result;
    }

    public static function getGenitive()
    {
        return Dep365CustomerOnlineGenitive::getGenitive();
    }

    public function getGenitiveHasOne()
    {
        return $this->hasOne(Dep365CustomerOnlineGenitive::class, ['id' => 'customer_gen']);
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public static function getSex()
    {
        return [
            self::SEX_MAN => 'Nam Giới',
            self::SEX_WOMAN => 'Nữ Giới',
        ];
    }

    public static function getProvince()
    {
        $result = [];
        $data = Province::getProvince();
        foreach ($data as $item) {
            $result[$item->id] = $item->name;
        }
        return $result;
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

    public function getFailDatHenCustomerOnlineHasOne()
    {
        return $this->hasOne(Dep365CustomerOnlineFailDathen::class, ['id' => 'dat_hen_fail']);
    }

    public function getCoSoHasOne()
    {
        return $this->hasOne(Dep365CoSo::class, ['id' => 'co_so']);
    }

    public function getDentalTagHasOne()
    {
        return $this->hasOne(DentalTagModel::class, ['customer_id' => 'id']);
    }


    public static function getUserCreatedBy($id)
    {
        $user = UserProfile::getUserCreatedOrUpdateBy($id);
        if ($user === null) {
            return false;
        }
        return $user;
    }

    public function getUserUpdatedBy($id)
    {
        $user = UserProfile::getUserCreatedOrUpdateBy($id) ?: '1';
        if ($user === null) {
            return false;
        }
        return $user;
    }

    public function getById($id)
    {
        if ($id == null) {
            return null;
        }
        return self::find()->where(['id' => $id])->one();
    }

    public function getStatusCustomerOnlineHasOne()
    {
        return $this->hasOne(Dep365CustomerOnlineStatus::class, ['id' => 'status']);
    }

    public function getDirectSaleName()
    {
        $directsale = $this->directSaleHasOne;
        return $directsale == null ? null : $directsale->fullname;
    }

    /**
     * Show list image customer by type, folder
     * author: Pham Thanh Nghia
     */
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
                    'webContentLink' => Url::to('@web/uploads/customer/' . $slug . '-' . $customer_id . '/' . $folder . '/' . $image->image,true),
                    'thumbnailLink' => Url::to('@web/uploads/customer/' . $slug . '-' . $customer_id . '/' . $folder . '/thumb/' . $image->image,true),
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

    /**
     *  Get CskhQuanLy by id
     *  @Author: Pham Thanh Nghia
     */
    public function getCskhQuanLyHasOne()
    {
        return $this->hasOne(CskhQuanLy::class, ['customer_id' => 'id']);
    }
}
