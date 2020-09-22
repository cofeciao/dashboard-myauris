<?php

namespace backend\modules\api\modules\v2\controllers;

use backend\modules\setting\models\Dep365CoSo;
use yii\helpers\ArrayHelper;
use backend\modules\user\models\User;
use backend\models\CustomerModel;
use backend\modules\api\modules\v2\components\RestfullController;
use backend\modules\clinic\controllers\TkncController;
use backend\modules\clinic\models\Clinic;
use backend\modules\clinic\models\PhongKhamDonHang;
use backend\modules\clinic\models\PhongKhamLichDieuTri;
use backend\modules\clinic\models\search\ClinicSearch;
use backend\modules\cskh\models\LogCskh;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\customer\models\Dep365CustomerOnlineCome;
use common\models\UserProfile;
use Yii;

class AffiliateController extends RestfullController
{
    public $modelClass = 'backend\modules\api\modules\v1\models\UserApi';

    /* 
     * DS Khách hàng hoàn thành dịch vụ
     */
    public function actionCompleteCustomerService()
    {
        $searchModel = new ClinicSearch();
        $dataProvider = $searchModel->searchCompleteCustomerService(Yii::$app->request->queryParams);

        $page = isset($param['page']) ? $param['page'] : 0;
        $per_page = isset($param['per-page']) ? $param['per-page'] : 0;
        $totalCount = $dataProvider->getTotalCount();
        if ($page * $per_page > $totalCount) {
            return [];
        }
        $pageSize = $dataProvider->pagination->pageSize;
        $totalCount = $dataProvider->totalCount;
        $totalPage = (($totalCount + $pageSize - 1) / $pageSize);

        $aData = $dataProvider->getModels();
        $result = [];
        foreach ($aData as $model) {
            $result[] = $this->getCustomerDetail($model);
        }
        return [
            'data' => $result,
            'totalPage' => $totalPage,
            'totalCount' => $totalCount,
        ];
    }

    /**
     * Thong tin khach hang
     */
    public function actionGetCustomer($id)
    {
        $model = CustomerModel::findOne($id);

        if ($model !== null) {
            $result = $this->getCustomerDetail($model);
            return [
                'data' => $result
            ];
        }

        return [
            'data' => $model
        ];
    }

    public function getCustomerDetail($model)
    {
        $result = ArrayHelper::toArray($model);
        $donHang = $model->donHangs;
        $aDonHang = [];
        foreach ($donHang as $mDonHang) {
            $itemDonHang = ArrayHelper::toArray($mDonHang);
            $itemDonHang['chi_tiet'] = $this->getDonHangWOrder($mDonHang->phongKhamDonHangWOrderHasMany);
            $itemDonHang['thanh_toan'] = $mDonHang->phongKhamDonHangWThanhToanHasMany;
            $itemDonHang['lich_dieu_tri'] = $this->getDetailLichDieuTri($mDonHang->phongKhamLichDieuTriHasMany);
            $itemDonHang['co_so'] = ($mDonHang->coSoHasOne !== null) ? $mDonHang->coSoHasOne->name : "";
            $aDonHang[] = $itemDonHang;
        }
        $result['directsale'] = (isset($result['directsale']) && $result['directsale'] !== null) ? UserProfile::getFullNameDirectSale($result['directsale']) : "";
        $result['permission_user'] = (isset($result['permission_user']) && $result['permission_user'] !== null) ? UserProfile::getFullName($result['permission_user']) : "";
        $result['co_so'] = ($model->coSoHasOne !== null) ? $model->coSoHasOne->name : "";

        $result['don_hang'] = $aDonHang;
        $result['image'] = $model->showImageGoogleDrive($model->id,  $model->slug,  TkncController::FOLDER);
        $result['id_dich_vu'] = ($model->dichVuOnlineHasOne !== null) ? $model->dichVuOnlineHasOne->name : "";
        return $result;
    }

    public function getDonHangWOrder($aModel)
    {
        $result = [];
        if (is_array($aModel)) {
            foreach ($aModel as $mWOrder) {
                $aOrder = ArrayHelper::toArray($mWOrder);
                $aOrder['san_pham'] = ($mWOrder->sanPhamHasOne !== null) ? $mWOrder->sanPhamHasOne->name : "";
                $aOrder['dich_vu'] = ($mWOrder->dichVuHasOne !== null) ? $mWOrder->dichVuHasOne->name : "";
                $result[] = $aOrder;
            }
        }
        return $result;
    }

    public function getDetailLichDieuTri($aModel)
    {
        $result = [];
        if (is_array($aModel)) {
            foreach ($aModel as $mLichdieutri) {
                $ekipInfoHasOneName = ($mLichdieutri->ekipInfoHasOne !== null) ? $mLichdieutri->ekipInfoHasOne->fullname : "";
                $aLich = ArrayHelper::toArray($mLichdieutri);
                $aLich['ekip'] = $ekipInfoHasOneName;
                $aLich['tro_thu'] = $mLichdieutri->getThongTinTroThu();
                $aLich['room_id'] = ($mLichdieutri->roomHasOne !== null) ? $mLichdieutri->roomHasOne->fullname : "";
                $result[] = $aLich;
            }
        }
        return $result;
    }

    /*
     * Danh sach nhan vien theo role
     */
    public function actionListUsersByRole($role)
    {
        $aUser = User::getUsersByRoles([$role]);
        $result = [];
        foreach ($aUser as $item) {
            $result[] = [
                'id' => $item->id,
                'fullname' => $item->fullname,
            ];
        }
        return $result;
    }

    /*
     * Nhan vien theo ID
     */
    public function actionGetUser($id)
    {
        $aUser = User::findOne($id);
        $result = ArrayHelper::toArray($aUser);
        unset($result['auth_key']);
        unset($result['access_token']);
        unset($result['password_hash']);
        return $result;
    }

    /**
     * Danh sach thao tac lich dieu tri
     */

    public function actionListThaoTac()
    {
        return PhongKhamLichDieuTri::listThaoTac();
    }

    /**
     * Luu thong tin log cua cham soc khach hang
     */
    public function actionSaveLogCskh()
    {
        $post = \Yii::$app->request->post();

        if (!isset($post['customer_id'])) {
            return [
                'code' => 400,
                'message' => "Not enough data. Unknown file for 'customer_id'",
            ];
        }
        if (!isset($post['note'])) {
            return [
                'code' => 400,
                'message' => "Not enough data. Unknown file for 'customer_id'",
            ];
        }
        $logCskh = new LogCskh();
        $logCskh->customer_id = $post['customer_id'];
        $logCskh->note = $post['note'];
        if ($logCskh->save(false)) {
            return [
                'code' => 200,
                'data' => $logCskh->id,
            ];
        }
        return [
            'code' => 500,
            'message' => "Save false",
        ];
    }

    /**
     * Search dach danh khach hang
     * search ngay lich dieu tri
     */
    public function actionCustomer()
    {
        $searchModel = new ClinicSearch();
        $dataProvider = $searchModel->searchCustomerAffiliate(Yii::$app->request->queryParams);

        $page = isset($param['page']) ? $param['page'] : 0;
        $per_page = isset($param['per-page']) ? $param['per-page'] : 0;
        $totalCount = $dataProvider->getTotalCount();
        if ($page * $per_page > $totalCount) {
            return [];
        }
        $pageSize = $dataProvider->pagination->pageSize;
        $totalCount = $dataProvider->totalCount;
        $totalPage = (($totalCount + $pageSize - 1) / $pageSize);

        $aData = $dataProvider->getModels();
        $result = [];
        foreach ($aData as $model) {
            $result[] = $this->getCustomerDetail($model);
        }
        return [
            'data' => $result,
            'totalPage' => $totalPage,
            'totalCount' => $totalCount,
        ];
    }

    public function actionStatusDatHen()
    {
        return Dep365CustomerOnline::getStatusDatHen();
    }
    /**
     * Nhan vien le tan
     */
    public function actionNhanVienLeTan()
    {
        return Dep365CustomerOnline::getNhanVienOnlineNLeTanFilter();
    }
    /**
     * Trang thai khach den
     */
    public function actionStatusCustomerCome()
    {
        return ArrayHelper::map(Dep365CustomerOnlineCome::getCustomerOnlineCome(), 'id', 'name');
    }
    /**
     * Nhan vien direct sale 
     */
    public function actionNhanVienDirectSale()
    {
        return ArrayHelper::map(User::getNhanVienTuDirectSale(), 'id', 'fullname');
    }
    /**
     * Nguon Customer Online
     */
    public function actionNguonCustomerOnline()
    {
        return Clinic::getNguonCustomerOnline();
    }
    /**
     * Dich vu online
     */
    public function actionDichVuOnline()
    {
        return Dep365CustomerOnline::getDichVuOnline();
    }

    public function actionGetCoso()
    {
        return ArrayHelper::map(Dep365CoSo::getCoSo(), 'id','name');
    }
}
