<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 27-Apr-19
 * Time: 1:43 PM
 */

namespace backend\modules\customer\components;

use backend\models\CustomerModel;
use backend\models\doanhthu\DonHangModel;
use backend\models\doanhthu\ThanhToanModel;
use backend\modules\api\modules\v1\models\ThanhToan;
use backend\modules\baocao\models\BaocaoLocation;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\customer\models\Dep365CustomerOnlineCome;
use backend\modules\customer\models\Dep365CustomerOnlineDathenTime;
use backend\modules\customer\models\Dep365CustomerOnlineDichVu;
use backend\modules\customer\models\Dep365CustomerOnlineFanpage;
use backend\modules\customer\models\Pancake;
use backend\modules\customer\models\RemindCall\CustomerOnlineModel;
use backend\modules\user\models\User;
use yii\base\Component;

class CustomerComponents extends Component
{
    /*
     * - Chia theo từng loại để lấy ra theo định dạng
     *      + $type = 1 : tính tổng số điện thoại return int
     *      + $type = 2 : Lấy ra số điện thoại theo từng ngày của khoảng ngày $from và $to
     */
    public $type = 1;

    /*
     * Chia tương tác ra từng loại để trả về dữ liệu tương ứng
     *  - $typeTuongtac = 1: Đếm tổng số tương tác theo khoảng thời gian
     *  - $typeTuongtac = 2: Trả về obj tổng tương tác group theo ngày tạo.
     */
    public $typeTuongtac = 1;

    /*
     *  - $typeCustomerOld = 1: Đếm tổng số đặt hẹn cũ theo khoảng thời gian
     *  - $typeCustomerOld = 2: Trả về obj tổng đặt hẹn cũ group theo ngày tạo.
     */
    public $typeCustomerOld = 1;

    /*
     * Danh sách khu vực cần query
     */
    public $loc = null;

    /*
     * Theo fanpage của facebook
     */
    public $pageonline = null;

    /*
     * Lấy theo nhân viên tạo
     */
    public $user = null;

    /*
    * Lấy dữ liệu theo list user đang làm việc
    */
    public $listUser = null;
    /*
     * Lấy khách hàng theo sản phẩm
     * Dùng để phân loại khách hàng theo sản phẩm chạy ads
     *
     */
    public $sanPham = null;

    /*
     * Khách hàng của ai tạo, nhân viên online hoặc lễ tân
     */
    public $isWho;

    /*
     * Tinh khach chot cho direct sale trong ngay hoac trong thang.
     */
    public static function getKhachChotTheoNhanVien($from, $to, $type = null, $listDirectSale = null)
    {
        $customerDoneAccept = self::getKhachLamdichVu();

        $query = CustomerModel::find()
            ->select('count(phone) as SDT, directsale')
            ->andWhere(['in', 'customer_come_time_to', array_keys($customerDoneAccept)])
            ->andWhere(['between', 'customer_come_date', $from, $to])->andWhere('customer_come_time_to is not null');
        if ($listDirectSale != null && $listDirectSale != '') {
            $query->andWhere(['in', 'directsale', $listDirectSale]);
        }

        switch ($type) {
            case 1:
                return $query->count();
                break;
            case 2:
                return $query->groupBy(['directsale'])->indexBy('directsale')->all();
                break;
            default:
                return $query->count();
                break;
        }
    }

    /*
     * Tinh khach tu van cho direct sale trong ngay hoac trong thang.
     */
    public static function getKhachTuVanTheoNhanVien($from, $to, $type = null, $listDirectSale = null)
    {
        $query = CustomerModel::find()
            ->select('count(phone) as SDT, directsale')
            ->where(['between', 'customer_come_date', $from, $to])->andWhere('customer_come_time_to is not null');
        if ($listDirectSale != null && $listDirectSale != '') {
            $query->andWhere(['in', 'directsale', $listDirectSale]);
        }

        switch ($type) {
            case 1:
                return $query->count();
                break;
            case 2:
                return $query->groupBy(['directsale'])->indexBy('directsale')->all();
                break;
            default:
                return $query->count();
                break;
        }
    }

    /*
     * Lấy ra doanh thu theo từng dịch vụ theo thời gian
     */
    public static function getInComeTheoDichVu($from, $to)
    {
        $query = ThanhToanModel::find()->select('sum(tien_thanh_toan) as tien, id_dich_vu')->where(['between', ThanhToanModel::tableName() . '.ngay_tao', $from, $to]);
        $query->joinWith(['customerHasOne']);
        return $query->groupBy('id_dich_vu')->indexBy('id_dich_vu')->all();
    }

    /*
     * Lấy ra doanh thu theo từng cơ sở theo thời gian
     */
    public static function getInCome($from, $to)
    {
        $query = ThanhToanModel::find()->select('sum(tien_thanh_toan) as tien, co_so')->where(['<>', 'tam_ung', ThanhToanModel::HOAN_COC])->andWhere(['between', 'ngay_tao', $from, $to]);

        return $query->groupBy('co_so')->indexBy('co_so')->all();
    }

    /*
     * Lấy ra toàn bộ khách hàng đã làm dịch vụ bên My Auris theo thời gian của online
     */
    public static function getCustomerDone($from, $to, $type = null)
    {
        $customerDoneAccept = self::getKhachLamdichVu();
        $query = CustomerModel::find()->select('count(phone) as SDT, co_so')
            ->andWhere(['in', 'customer_come_time_to', array_keys($customerDoneAccept)]);
        if ($type == null) {
            $query->andWhere(['is_customer_who' => CustomerModel::IS_CUSTOMER_TV_ONLINE]);
        }
        $query->andWhere(['between', 'date_lichhen', $from, $to]);
        $query->groupBy(['co_so']);
//        echo $query->createCommand()->getRawSql();die;
        return $query->indexBy('co_so')->all();
    }

    /*
     * Lấy khách hàng theo sản phẩm
     */
    public static function getKhachHangTheoSanPham($from, $to, $co_so = null)
    {
        $query = CustomerModel::find()
            ->select('count(phone) as SDT, dep365_customer_online_dich_vu.name, id_dich_vu')
            ->where(['between', 'ngay_tao', $from, $to])
            ->andWhere('id_dich_vu is not null');
        $query->andWhere([CustomerModel::tableName() . '.status' => CustomerModel::STATUS_DH]);

        if ($co_so != null) {
            $query->andWhere([CustomerModel::tableName() . '.co_so' => $co_so]);
        }

        $query->joinWith(['dichVuOnlineHasOne']);
        $query->groupBy(['id_dich_vu', 'dep365_customer_online_dich_vu.name'])->indexBy('id_dich_vu');
        return $query->all();
    }

    /*
     * Lấy ra tương tác của nhân viên với khách hàng theo page và theo nhân viên online.
     * Định nghĩa dữ liệu trả về.
     * $typeTuongtac = 3: Groupby theo danh sách user
     */
    public static function getTuongTacKhachHang($from, $to, $typeTuongtac, $pageonline, $user, $listUser = null)
    {
        $query = Pancake::find()->where(['between', 'date_import', $from, $to]);

        if ($pageonline != null && $pageonline != '') {
            $query->andWhere(['page_facebook' => $pageonline]);
        }

        if ($user != null && $user != '') {
            $query->andWhere(['user_id' => $user]);
        }
        switch ($typeTuongtac) {
            case 1:
                $query->select('date_import, user_id, sum(number_pancake) as NUM');
                if ($listUser != null && $listUser !== '') {
                    $query->andWhere(['in', 'user_id', $listUser]);
                }
                return $query->sum('number_pancake');
                break;
            case 2:
                $query->select('date_import, sum(number_pancake) as NUM');
                if ($listUser != null && $listUser !== '') {
                    $query->andWhere(['in', 'user_id', $listUser]);
                }
                $query->groupBy(['date_import'])->indexBy('date_import');
                return $query->all();
                break;
            case 3:
                $query->select('user_id, sum(number_pancake) as NUM');
                $query->andWhere(['in', 'user_id', $listUser]);
                return $query->groupBy(['user_id'])->all();
                break;
            case 4:
                $query->select('date_import, user_id, sum(number_pancake) as NUM');
                $query->andWhere(['in', 'user_id', $listUser]);
                return $query->sum('number_pancake');
                break;
            default:
                $query->select('date_import, user_id, sum(number_pancake) as NUM');
                return $query->sum('number_pancake');
                break;
        }
    }
    /*
     * Tính tổng tương tác của các bạn nhân viên
     */

    /*
     * Lấy ra tổng số điện thoại theo ngày, khu vực, theo page
     * $from Thời gian bắt đầu: int
     * $to - Thời gian kết thúc: int
     * $type - Chia theo từng loại để lấy ra theo định dạng
     *      + $type = 1 : tính tổng số điện thoại return int
     *      + $type = 2 : Lấy ra số điện thoại theo từng ngày của khoảng ngày $from và $to
     *      + $type = 3 : Group by theo lish user
     * $loc - Lấy theo khu vực
     * $pageonline - Lấy theo fanpage Facebook
     * $user - Lấy theo nhân viên online
     *
     */
    public static function getPhoneCustomerWithDay($from, $to, $type, $loc, $pageonline, $user, $listUser = null, $sanPham = null)
    {
        $query = CustomerModel::find()
            ->where(['between', 'ngay_tao', $from, $to]);

        if ($loc != null && $loc != '') {
            $location = self::getLoc($loc);

            if ($location === null) {
                $listProvince = [];
            } else {
                $listProvince = json_decode($location->list_province);
            }
            $query->andWhere(['in', 'province', $listProvince]);
        }

        if ($pageonline != null && $pageonline != '') {
            $query->andWhere(['face_fanpage' => $pageonline]);
        }

        if ($user != null && $user != '') {
            $query->andWhere(['permission_user' => $user]);
        }

        if ($sanPham != null && $sanPham != '') {
            $query->andWhere(['id_dich_vu' => $sanPham]);
        }

        $query->andWhere(['is_customer_who' => CustomerModel::IS_CUSTOMER_TV_ONLINE]);
        switch ($type) {
            case 1:
                $query->select('permission_user, created_by, ngay_tao, count(phone) as SDT');
                if ($listUser != null && $listUser != '') {
                    $query->andWhere(['in', 'permission_user', $listUser]);
                }
                return $query->count();
                break;
            case 2:
                $query->select(CustomerModel::tableName() . '.ngay_tao, count(phone) as SDT');
                if ($listUser != null && $listUser != '') {
                    $query->andWhere(['in', 'permission_user', $listUser]);
                }
                $query->groupBy(['ngay_tao'])->indexBy('ngay_tao');
                return $query->all();
                break;
            case 3:
                $query->select('permission_user, count(phone) as SDT');
                $query->andWhere(['in', 'permission_user', $listUser]);
                return $query->groupBy(['permission_user'])->all();
                break;
            case 4:
                $query->select('permission_user, created_by, ngay_tao, count(phone) as SDT');
                $query->andWhere(['in', 'permission_user', $listUser]);
                return $query->count();
                break;
            default:
                $query->select('permission_user, created_by, ngay_tao, count(phone) as SDT');
                return $query->count();
                break;
        }
    }

    /*
     * Tổng số điện thoại gọi được trong ngày hoặc group theo từng ngày
     */
    public static function getPhoneCallSuccessCustomerWithDay($from, $to, $type, $loc, $pageonline, $user, $listUser = null, $sanPham = null)
    {
        $query = CustomerModel::find()
            ->where(['between', 'ngay_tao', $from, $to]);
        $query->andWhere(['in', 'status', [CustomerModel::STATUS_DH, CustomerModel::STATUS_FAIL]]);
        $query->andWhere(['is_customer_who' => CustomerModel::IS_CUSTOMER_TV_ONLINE]);

        if ($pageonline != null && $pageonline != '') {
            $query->andWhere(['face_fanpage' => $pageonline]);
        }

        if ($loc != null && $loc != '') {
            $location = self::getLoc($loc);

            if ($location === null) {
                $listProvince = [];
            } else {
                $listProvince = json_decode($location->list_province);
            }
            $query->andWhere(['in', 'province', $listProvince]);
        }

        if ($user != null && $user != '') {
            $query->andWhere(['permission_user' => $user]);
        }

        if ($sanPham != null && $sanPham != '') {
            $query->andWhere(['id_dich_vu' => $sanPham]);
        }

        switch ($type) {
            case 1:
                $query->select('permission_user, created_by, ngay_tao, count(phone) as SDT');
                if ($listUser != null && $listUser != '') {
                    $query->andWhere(['in', 'permission_user', $listUser]);
                }
                return $query->count();
                break;
            case 2:
                $query->select('ngay_tao, count(phone) as SDT');
                if ($listUser != null && $listUser != '') {
                    $query->andWhere(['in', 'permission_user', $listUser]);
                }
                $query->groupBy(['ngay_tao'])->indexBy('ngay_tao');
                return $query->all();
                break;
            case 3:
                $query->select('permission_user, count(phone) as SDT');
                $query->andWhere(['in', 'permission_user', $listUser]);
                return $query->groupBy(['permission_user'])->all();
                break;
            case 4:
                $query->select('permission_user, created_by, ngay_tao, count(phone) as SDT');
                $query->andWhere(['in', 'permission_user', $listUser]);
                return $query->count();
                break;
            default:
                $query->select('permission_user, created_by, ngay_tao, count(phone) as SDT');
                return $query->count();
                break;
        }
    }

    /*
     * Tính khách hàng đặt hẹn mới theo ngày hoặc khoảng thời gian.
     * Tính theo lịch thực tế
     */
    public static function getCalendarNewOfCustomer($from, $to, $type)
    {
        $query = CustomerModel::find()->select('dep365_customer_online.co_so, count(phone) as SDT')->where(['between', 'ngay_tao', $from, $to]);
        $query->andWhere(['in', 'status', [CustomerModel::STATUS_DH]]);
        $query->andWhere(['is_customer_who' => CustomerModel::IS_CUSTOMER_TV_ONLINE]);

        switch ($type) {
            case 1:
                return $query->count();
                break;
            case 2:
                return $query->groupBy(['dep365_customer_online.co_so'])->indexBy('co_so')->all();
                break;
            default:
                return $query->count();
                break;
        }
    }

    /*
     * Tinh khách đặt hẹn là khách đặt hẹn, không giới hạn số lần đặt hẹn
     */
    public static function getKhachAllDatHen($from, $to, $typeCustomerOld, $loc, $pageonline, $user, $listUser = null, $sanPham = null)
    {
        $query = Dep365CustomerOnlineDathenTime::find();
        $query->innerJoinWith(['customerHasOne']);
        $query->innerJoinWith(['tableUserHasOne']);
        $query->andWhere(['between', 'dep365_customer_online_dathen_time.date_change', $from, $to]);
        $query->andWhere(['dep365_customer_online.status' => Dep365CustomerOnline::STATUS_DH]);

        if ($loc != null && $loc != '') {
            $location = self::getLoc($loc);

            if ($location === null) {
                $listProvince = [];
            } else {
                $listProvince = json_decode($location->list_province);
            }
            $query->andWhere(['in', 'dep365_customer_online.province', $listProvince]);
        }

        if ($pageonline != null && $pageonline != '') {
            $query->andWhere(['dep365_customer_online.face_fanpage' => $pageonline]);
        }

        if ($user != null && $user != '') {
            $query->andWhere(['dep365_customer_online_dathen_time.user_id' => $user]);
        }

        if ($sanPham != null && $sanPham != '') {
            $query->andWhere(['id_dich_vu' => $sanPham]);
        }

        $query->andWhere(['dep365_customer_online.is_customer_who' => CustomerModel::IS_CUSTOMER_TV_ONLINE]);

        switch ($typeCustomerOld) {
            case 1:
                $query->select(
                    User::tableName() . '.team, ' .
                    Dep365CustomerOnlineDathenTime::tableName() . '.customer_online_id, ' .
                    Dep365CustomerOnlineDathenTime::tableName() . '.user_id, ' .
                    Dep365CustomerOnlineDathenTime::tableName() . '.date_change, ' .
                    'count(*) as user'
                );
                if ($listUser != null && $listUser != '') {
                    $query->andWhere(['in', 'dep365_customer_online_dathen_time.user_id', $listUser]);
                }
                return $query->count();
                break;
            case 2:
                $query->select(
                    Dep365CustomerOnlineDathenTime::tableName() . '.date_change, ' .
                    'count(*) as user'
                );
                if ($listUser != null && $listUser != '') {
                    $query->andWhere(['in', 'dep365_customer_online_dathen_time.user_id', $listUser]);
                }
                $query->groupBy('dep365_customer_online_dathen_time.date_change');
//                var_dump($query->indexBy('date_change')->createCommand()->getRawSql());die;
                return $query->indexBy('date_change')->all();
                break;
            case 3:
                $query->select(
                    Dep365CustomerOnlineDathenTime::tableName() . '.user_id, ' .
                    'count(*) as user'
                );
                $query->andWhere(['in', 'dep365_customer_online_dathen_time.user_id', $listUser]);
                $query->groupBy('dep365_customer_online_dathen_time.user_id');
                return $query->all();
                break;
            case 4:
                $query->select(
                    User::tableName() . '.team, ' .
                    Dep365CustomerOnlineDathenTime::tableName() . '.customer_online_id, ' .
                    Dep365CustomerOnlineDathenTime::tableName() . '.user_id, ' .
                    Dep365CustomerOnlineDathenTime::tableName() . '.date_change, ' .
                    'count(*) as user'
                );
                $query->andWhere(['in', 'dep365_customer_online_dathen_time.user_id', $listUser]);
                return $query->count();
                break;
            case 5:
                $query->select(
                    User::tableName() . '.team, ' .
                    Dep365CustomerOnlineDathenTime::tableName() . '.customer_online_id, ' .
                    Dep365CustomerOnlineDathenTime::tableName() . '.user_id, ' .
                    Dep365CustomerOnlineDathenTime::tableName() . '.date_change, ' .
                    'count(*) as user'
                );
                $query->andWhere(['in', 'dep365_customer_online_dathen_time.user_id', $listUser]);
                $query->groupBy(
                    Dep365CustomerOnlineDathenTime::tableName() . '.customer_online_id, ' .
                    User::tableName() . '.team, ' .
                    Dep365CustomerOnlineDathenTime::tableName() . '.user_id, ' .
                    Dep365CustomerOnlineDathenTime::tableName() . '.date_change'
                );
                return $query->count();
                break;
            case 6:
                $query->select(
                    User::tableName() . '.team, ' .
                    'count(*) as user'
                );
                //Tinh lich hen theo Team trong table user
                $query->andWhere(['in', 'dep365_customer_online_dathen_time.user_id', $listUser]);
                $query->groupBy('user.team')->indexBy('team');
                return $query->all();
                break;
            default:
                $query->select(
                    User::tableName() . '.team, ' .
                    Dep365CustomerOnlineDathenTime::tableName() . '.customer_online_id, ' .
                    Dep365CustomerOnlineDathenTime::tableName() . '.user_id, ' .
                    Dep365CustomerOnlineDathenTime::tableName() . '.date_change, ' .
                    'count(*) as user'
                );
                return $query->count();
                break;
        }
    }

    /*
     * Tính tổng lịch hẹn theo ngày hoặc khoảng thời gian
     * Dựa trên lịch thực tế
     *
     */
    public static function getTotalLichHenWithDay($from, $to, $type)
    {
        $query = CustomerModel::find()->select('dep365_customer_online.co_so, count(phone) as SDT')->where(['between', 'date_lichhen', $from, $to]);

        $query->andWhere(['status' => CustomerModel::STATUS_DH]);
        $query->andWhere(['is_customer_who' => CustomerModel::IS_CUSTOMER_TV_ONLINE]);



        switch ($type) {
            case 1:
                return $query->count();
                break;
            case 2:
                //group by theo tung co so
//                echo $query->groupBy(['dep365_customer_online.co_so'])->createCommand()->getRawSql();
                return $query->groupBy(['dep365_customer_online.co_so'])->indexBy('co_so')->all();
                break;
            default:
                return $query->count();
                break;
        }
    }

    public static function getKhachAllLichHen($from, $to, $typeCustomerAll, $loc, $pageonline, $user, $listUser = null, $coso = null, $isWho = null)
    {
        $query = Dep365CustomerOnlineDathenTime::find();
        $query->innerJoinWith(['customerHasOne']);
        $query->andWhere(['between', 'dep365_customer_online_dathen_time.date_lichhen_new', $from, $to]);
        $query->andWhere(['dep365_customer_online.status' => Dep365CustomerOnline::STATUS_DH]);

        if ($coso != null && $coso != '') {
            $query->andWhere(['dep365_customer_online.co_so' => $coso]);
        }

        if ($loc != null && $loc != '') {
            $location = self::getLoc($loc);

            if ($location === null) {
                $listProvince = [];
            } else {
                $listProvince = json_decode($location->list_province);
            }
            $query->andWhere(['in', 'dep365_customer_online.province', $listProvince]);
        }

        if ($pageonline != null && $pageonline != '') {
            $query->andWhere(['dep365_customer_online.face_fanpage' => $pageonline]);
        }

        if ($user != null && $user != '') {
            $query->andWhere(['dep365_customer_online_dathen_time.user_id' => $user]);
        }

        if ($isWho == null) {
            $query->andWhere(['dep365_customer_online.is_customer_who' => CustomerModel::IS_CUSTOMER_TV_ONLINE]);
        }

        switch ($typeCustomerAll) {
            case 1:
                $query->select('dep365_customer_online.co_so, date_lichhen_new, user_id, date_change, count(*) as user');
                if ($listUser != null && $listUser != '') {
                    $query->andWhere(['in', 'dep365_customer_online_dathen_time.user_id', $listUser]);
                }
                return $query->count();
                break;
            case 2:
                $query->select('user_id, count(*) as user');
                if ($listUser != null && $listUser != '') {
                    $query->andWhere(['in', 'dep365_customer_online_dathen_time.user_id', $listUser]);
                }
                $query->groupBy('dep365_customer_online_dathen_time.user_id');
                return $query->all();
                break;
            case 3:
                $query->select('date_lichhen_new, count(*) as user');
                if ($listUser != null && $listUser != '') {
                    $query->andWhere(['in', 'dep365_customer_online_dathen_time.user_id', $listUser]);
                }
                $query->groupBy(['date_lichhen_new'])->indexBy('date_lichhen_new');
                return $query->all();
                break;
            case 4:
                $query->select('dep365_customer_online.co_so, date_lichhen_new, user_id, date_change, count(*) as user');
                if ($listUser != null && $listUser != '') {
                    $query->andWhere(['in', 'dep365_customer_online_dathen_time.user_id', $listUser]);
                }
                return $query->count();
                break;
            case 5:
                $query->select('dep365_customer_online.co_so, count(*) as user');
                //group by theo tung co so
                if ($listUser != null && $listUser != '') {
                    $query->andWhere(['in', 'dep365_customer_online_dathen_time.user_id', $listUser]);
                }
                $query->groupBy(['dep365_customer_online.co_so'])->indexBy('co_so');
                return $query->all();
                break;

            default:
                $query->select('dep365_customer_online.co_so, date_lichhen_new, user_id, date_change, count(*) as user');
                return $query->count();
                break;
        }
    }



    /*
     * Tính tổng khách  đến theo ngày hoặc theo khoảng thời gian
     */
    public static function getTotalCustomerGotoAuris($from, $to, $type, $loc, $pageonline, $user, $listUser = null, $sanPham = null, $isWho = null)
    {
        $query = CustomerModel::find()
            ->where(['between', 'customer_come_date', $from, $to]);
        $query->andWhere(['in', 'status', [CustomerModel::STATUS_DH]]);
        $query->andWhere(['in', 'dat_hen', [CustomerModel::DA_DEN]]);

        if ($isWho == 1) {
            $query->andWhere(['is_customer_who' => CustomerModel::IS_CUSTOMER_TV_ONLINE]);
        } elseif ($isWho == 2) {
            $query->andWhere(['is_customer_who' => CustomerModel::IS_CUSTOMER_LETAN]);
        }

        if ($pageonline != null && $pageonline != '') {
            $query->andWhere(['face_fanpage' => $pageonline]);
        }

        if ($loc != null && $loc != '') {
            $location = self::getLoc($loc);

            if ($location === null) {
                $listProvince = [];
            } else {
                $listProvince = json_decode($location->list_province);
            }
            $query->andWhere(['in', 'province', $listProvince]);
        }

        if ($user != null && $user != '') {
            $query->andWhere(['permission_user' => $user]);
        }

        if ($sanPham != null && $sanPham != '') {
            $query->andWhere(['id_dich_vu' => $sanPham]);
        }

        switch ($type) {
            case 1:
                $query->select('permission_user, created_by, customer_come_date, count(phone) as SDT, co_so');
                if ($listUser != null && $listUser != '') {
                    $query->andWhere(['in', 'permission_user', $listUser]);
                }
                return $query->count();
                break;
            case 2:
                $query->select('customer_come_date, count(phone) as SDT');
                if ($listUser != null && $listUser != '') {
                    $query->andWhere(['in', 'permission_user', $listUser]);
                }
                $query->groupBy(['customer_come_date']);
                $query->indexBy('customer_come_date');
                return $query->all();
                break;
            case 3:
                $query->select('permission_user, count(phone) as SDT');
                $query->andWhere(['in', 'permission_user', $listUser]);
                return $query->groupBy(['permission_user'])->all();
                break;
            case 4:
                $query->select('permission_user, created_by, customer_come_date, count(phone) as SDT, co_so');
                $query->andWhere(['in', 'permission_user', $listUser]);
                return $query->count();
                break;
            case 5:
                $query->select('count(phone) as SDT, co_so');
                //group by theo tung co so
                if ($listUser != null && $listUser != '') {
                    $query->andWhere(['in', 'permission_user', $listUser]);
                }
                $query->groupBy(['dep365_customer_online.co_so'])->indexBy('co_so');
                return $query->all();
                break;
            default:
                $query->select('permission_user, created_by, customer_come_date, count(phone) as SDT, co_so');
                return $query->count();
                break;
        }
    }



    /*
     * Tam's code
     */
    /**
     * Tính tổng doanh thu tháng hiện tại theo từng cơ sở
     * @param $from
     * @param $to
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getRevenue($from, $to)
    {
        $query = ThanhToanModel::find();
        $query->select([
            ThanhToanModel::tableName() . '.co_so',
            'SUM(' . ThanhToan::tableName() . '.tien_thanh_toan) AS tien'
        ]);
        $query->leftJoin(DonHangModel::tableName(), DonHangModel::tableName() . '.id=' . ThanhToanModel::tableName() . '.phong_kham_don_hang_id');
        $query->where(['<>', ThanhToanModel::tableName() . '.tam_ung', ThanhToanModel::HOAN_COC]);
        $query->andWhere(['BETWEEN', ThanhToanModel::tableName() . '.ngay_tao', $from, $to]);

        return $query->groupBy('co_so')->indexBy('co_so')->all();
    }

    /**
     * Tính tổng doanh thu từng dịch vụ
     * @param $from
     * @param $to
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getServiceRevenue($from, $to)
    {
        $query = ThanhToanModel::find();
        $query->select([
            Dep365CustomerOnline::tableName() . '.id_dich_vu',
            'SUM(' . ThanhToanModel::tableName() . '.tien_thanh_toan) AS tien'
        ]);
        $query->joinWith(['customerHasOne']);
        $query->andWhere(['BETWEEN', ThanhToanModel::tableName() . '.ngay_tao', $from, $to]);

        return $query->groupBy('id_dich_vu')->indexBy('id_dich_vu')->all();
    }

    /**
     * Tính tổng lịch hẹn theo fanpage facebook
     * @param $form
     * @param $to
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getTotalLichHenFanpage($form, $to)
    {
        $query = CustomerOnlineModel::find()->select('COUNT(face_fanpage) AS total, face_fanpage')
            ->where('`face_fanpage` IS NOT NULL')
            ->andWhere(['BETWEEN', 'date_lichhen', $form, $to])
            ->andWhere(['status' => CustomerModel::STATUS_DH])
            ->andWhere(['IN', 'dat_hen', [Dep365CustomerOnline::DAT_HEN_KHONG_DEN, Dep365CustomerOnline::DAT_HEN_DEN]])
            ->groupBy('face_fanpage')
            ->indexBy('face_fanpage');

        return $query->all();
    }

    /**
     * Tính tổng tương tác từng dịch vụ
     * @param $from
     * @param $to
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getTuongTacTungDichVu($from, $to)
    {
        $query = Dep365CustomerOnlineDichVu::find();
        $query->select([
            Dep365CustomerOnlineDichVu::tableName() . '.id',
            'SUM(' . Pancake::tableName() . '.number_pancake) as total_tuongTac'
        ])
            ->innerJoin(Dep365CustomerOnlineFanpage::tableName(), Dep365CustomerOnlineDichVu::tableName() . '.id=' . Dep365CustomerOnlineFanpage::tableName() . '.id_dich_vu')
            ->leftJoin(Pancake::tableName(), Dep365CustomerOnlineFanpage::tableName() . '.id=' . Pancake::tableName() . '.page_facebook')
            ->where(['BETWEEN', Pancake::tableName() . '.date_import', $from, $to + 86399])
            ->groupBy(Dep365CustomerOnlineDichVu::tableName() . '.id')
            ->indexBy('id');
//        return $query->createCommand()->getRawSql();
        return $query->all();
    }

    /**
     * Tính tổng lịch mới từng dịch vụ
     * @param $from
     * @param $to
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getLichMoiTungDichVu($from, $to)
    {
        $query = Dep365CustomerOnlineDichVu::find();
        $query->select([
            Dep365CustomerOnlineDichVu::tableName() . '.id',
            'COUNT(' . Dep365CustomerOnline::tableName() . '.id) as total_lichMoi'
        ])
            ->leftJoin(Dep365CustomerOnline::tableName(), Dep365CustomerOnlineDichVu::tableName() . '.id=' . Dep365CustomerOnline::tableName() . '.id_dich_vu')
//            ->leftJoin(Dep365CustomerOnlineDathenTime::tableName(), Dep365CustomerOnline::tableName() . '.id=' . Dep365CustomerOnlineDathenTime::tableName() . '.customer_online_id')
//            ->where(['BETWEEN', Dep365CustomerOnlineDathenTime::tableName() . '.date_change', $from, $to + 86399])
            ->where(['BETWEEN', Dep365CustomerOnline::tableName() . '.ngay_tao', $from, $to + 86399])
//            ->andWhere([Dep365CustomerOnline::tableName() . '.is_customer_who' => Dep365CustomerOnline::IS_CUSTOMER_TV_ONLINE])
            ->andWhere([Dep365CustomerOnline::tableName() . '.status' => Dep365CustomerOnline::STATUS_DH])
            ->groupBy(Dep365CustomerOnlineDichVu::tableName() . '.id')
            ->indexBy('id');
//        return $query->createCommand()->getRawSql();
        return $query->all();
    }

    /**
     * Tính tổng lịch hẹn từng dịch vụ
     * @param $from
     * @param $to
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getLichHenTungDichVu($from, $to)
    {
        $query = Dep365CustomerOnlineDichVu::find();
        $query->select([
            Dep365CustomerOnlineDichVu::tableName() . '.id',
            'COUNT(' . Dep365CustomerOnline::tableName() . '.id) as total_lichHen'
        ])
            ->leftJoin(Dep365CustomerOnline::tableName(), Dep365CustomerOnlineDichVu::tableName() . '.id=' . Dep365CustomerOnline::tableName() . '.id_dich_vu')
//            ->leftJoin(Dep365CustomerOnlineDathenTime::tableName(), Dep365CustomerOnline::tableName() . '.id=' . Dep365CustomerOnlineDathenTime::tableName() . '.customer_online_id')
//            ->where(['BETWEEN', Dep365CustomerOnlineDathenTime::tableName() . '.time_lichhen_new', $from, $to + 86399])
            ->where(['BETWEEN', Dep365CustomerOnline::tableName() . '.date_lichhen', $from, $to + 86399])
            ->andWhere([Dep365CustomerOnline::tableName() . '.status' => Dep365CustomerOnline::STATUS_DH])
            ->groupBy(Dep365CustomerOnlineDichVu::tableName() . '.id')
            ->indexBy('id');
//        return $query->createCommand()->getRawSql();
        return $query->all();
    }

    /**
     * Tính tổng khách đến từng dịch vụ
     * @param $from
     * @param $to
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getTotalKhachDenTungDichVu($from, $to)
    {
        $query = Dep365CustomerOnlineDichVu::find();
        $query->select([
            Dep365CustomerOnlineDichVu::tableName() . '.id',
            'COUNT(' . CustomerModel::tableName() . '.id) AS total_khachDen'
        ])
            ->leftJoin(CustomerModel::tableName(), Dep365CustomerOnlineDichVu::tableName() . '.id=' . CustomerModel::tableName() . '.id_dich_vu')
            ->where(['BETWEEN', CustomerModel::tableName() . '.customer_come_date', $from, $to + 86399])
            ->andWhere(['IN', CustomerModel::tableName() . '.status', [CustomerModel::STATUS_DH]])
            ->andWhere(['IN', CustomerModel::tableName() . '.dat_hen', [CustomerModel::DA_DEN]])
            ->groupBy(Dep365CustomerOnlineDichVu::tableName() . '.id')
            ->indexBy('id');
//        return $query->createCommand()->getRawSql();
        return $query->all();
    }

    /**
     * Tính tổng khách hàng đã làm theo từng dịch vụ
     * @param $from
     * @param $to
     * @param null $type
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getTotalKhachLamTungDichVu($from, $to, $type = null)
    {
        $customerDoneAccept = self::getKhachLamdichVu();
        $query = Dep365CustomerOnlineDichVu::find();

        switch ($type) :
            case 1:
                $query->select([
                    Dep365CustomerOnlineDichVu::tableName() . '.id',
                    'COUNT(' . CustomerModel::tableName() . '.id) AS total_khachLam'
                ])
                    ->groupBy('id')
                    ->indexBy('id');
                break;
            case 2:
                $query->select([
                    Dep365CustomerOnlineDichVu::tableName() . '.id',
                    CustomerModel::tableName() . '.co_so',
                    'COUNT(' . CustomerModel::tableName() . '.id) AS total_khachLam'
                ])
                    ->groupBy([Dep365CustomerOnlineDichVu::tableName() . '.id', CustomerModel::tableName() . '.co_so']);
                break;
            default:
                $query->select([
                    Dep365CustomerOnlineDichVu::tableName() . '.id',
                    'COUNT(' . CustomerModel::tableName() . '.id) AS total_khachLam'
                ])
                    ->groupBy('id')
                    ->indexBy('id');
        endswitch;

        $query->leftJoin(CustomerModel::tableName(), Dep365CustomerOnlineDichVu::tableName() . '.id=' . CustomerModel::tableName() . '.id_dich_vu')
            ->where(['IN', CustomerModel::tableName() . '.customer_come_time_to', array_keys($customerDoneAccept)])
            ->andWhere(['BETWEEN', CustomerModel::tableName() . '.date_lichhen', $from, $to])
            ->andWhere([CustomerModel::tableName() . '.status' => CustomerModel::STATUS_DH])
            ->andWhere(['IN', CustomerModel::tableName() . '.dat_hen', [CustomerModel::DA_DEN]]);
//        return $query->createCommand()->getRawSql();
        return $query->all();
    }

    /**
     * @param $from
     * @param $to
     * @return \yii\db\ActiveQuery
     */
    public static function getQueryAppointmentByTime($from, $to){
        $query = CustomerModel::find()->select('dep365_customer_online.co_so, count(*) as user')->where(['between', 'time_lichhen', $from, $to]);

        $query->andWhere(['status' => CustomerModel::STATUS_DH]);
        $query->andWhere(['is_customer_who' => CustomerModel::IS_CUSTOMER_TV_ONLINE])->groupBy(['dep365_customer_online.co_so']);
//        echo $query->createCommand()->getRawSql();
        return $query;
    }

    /**
     * @param $from
     * @param $to
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getArrayAppointmentByTime($from, $to)
    {
        $query = self::getQueryAppointmentByTime($from, $to);
        return $query->indexBy('co_so')->all();
    }

    /*
     * Lấy ra danh sách các tỉnh thành
     */
    protected static function getLoc($loc)
    {
        return BaocaoLocation::find()->where(['id' => $loc])->one();
    }

    protected static function getKhachLamdichVu()
    {
        return Dep365CustomerOnlineCome::find()->select('id')->where(['accept' => Dep365CustomerOnlineCome::STATUS_ACCEPT])->asArray()->indexBy('id')->all();
    }
}
