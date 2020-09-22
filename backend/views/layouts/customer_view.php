<?php

use backend\helpers\BackendHelpers;
use backend\modules\clinic\models\PhongKhamLoaiThanhToan;
use backend\modules\user\models\User;
use backend\modules\user\models\UserTimelineModel;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use backend\modules\customer\models\Dep365CustomerOnline;

/* @var $this yii\web\View */
/* @var $model backend\modules\customer\models\Dep365CustomerOnline */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Khách hàng trực tuyến', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

    <div class="modal-header bg-blue-grey bg-lighten-2 white">
        <h4 class="modal-title">Thông tin khách hàng: <?= $this->title; ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body view-order-customer">
        <div class="detail-customer">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Khách hàng:</label>
                        <div class="form-warp">
                            <label><?= $model->full_name == null ? $model->name : $model->full_name; ?></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại:</label>
                        <div class="form-warp">
                            <label><?= $model->phone == null ? ';' : Html::a($model->phone, 'javascript:void(0)', ['onclick' => 'mycall.makeCall(\'' . $model->phone . '\')']);; ?></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Giới tính:</label>
                        <div class="form-warp">
                            <label><?php
                                switch ($model->sex) {
                                    case 1:
                                        $result = 'Nam Giới';
                                        break;
                                    case 0:
                                        $result = 'Nữ Giới';
                                        break;
                                    case 2:
                                        $result = 'Chưa xác định';
                                        break;
                                    default:
                                        $result = 'Chưa xác định';
                                        break;
                                }
                                echo $result;
                                ?></label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Ngày sinh:</label>
                        <div class="form-warp">
                            <label><?= $model->birthday == null ? '-' : $model->birthday; ?></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Facebook:</label>
                        <div class="form-warp">
                            <label><?= $model->face_customer == null ? '-' : $model->face_customer; ?></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Địa Chỉ:</label>
                        <div class="form-warp">
                            <label>
                                <?= $model->address == null ? '' : $model->address . ' -'; ?>
                                <?= $model->districtHasOne == null ? '' : 'Quận ' . $model->districtHasOne->name . ' -'; ?>
                                <?= $model->provinceHasOne == null ? '' : $model->provinceHasOne->name; ?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-12">
                    <label class="form-group">Timeline khách hàng:</label>
                </div>
            </div>
            <div class="customer-timeline">
                <div id="timeline">
                    <div>
                        <?php
                        $user = new Dep365CustomerOnline();
                        $userCreatedBy = $user->getUserCreatedBy($model->created_by);
                        $data = $user->getUserTimelineByCustomerId($model->id);
                        const success = '<i class="ft-check-circle primary"></i>';
                        const deny = '<i class="ft-x-circle danger"></i>';
                        ?>
                        <?php
                        // Lập từng dòng thao tác của nhân viên từ user_timeline table
                        foreach ($data as $item) {
                            if ($item != null) {
                                if ($item->action != null) {
                                    $str = '';
                                    if (is_array($item->action)) {
                                        foreach ($item->action as $i) {
                                            $str .= UserTimelineModel::LIST[$i] . ' ';
                                        }
                                    } else {
                                        $str .= UserTimelineModel::LIST[$item->action] . ' ';
                                    }
                                    $permission = new User();
                                    $roleUser = $permission->getRoleName(Yii::$app->user->id);
                                    if ($roleUser != User::USER_NHANVIEN_ONLINE) {
                                        echo getSection(UserTimelineModel::ACTION_THANH_TOAN, $item, $str, $model);
                                        echo getSection(UserTimelineModel::ACTION_LICH_DIEU_TRI, $item, $str, $model);
                                        echo getSection(UserTimelineModel::ACTION_DON_HANG, $item, $str, $model);
                                        echo getSection(UserTimelineModel::ACTION_THAM_KHAM, $item, $str, $model);
                                    }
                                    echo getSection(UserTimelineModel::ACTION_DAT_HEN, $item, $str, $model);
                                }
                            }
                        }
                        ?>
                        <section class="year">
                            <h3><?= UserTimelineModel::LIST[UserTimelineModel::ACTION_TAO] ?></h3>
                            <section>
                                <h4><?= date('h:i d-m-Y', $model->created_at) ?></h4>
                                <ul>
                                    <li>
                                        <?= $model->statusCustomerOnlineHasOne->name; ?>
                                        <?= in_array($model->status, [1, 7]) ? ' ' . success : ' ' . deny; ?>
                                    </li>
                                    <?= getDetail(isset($model->failStatusCustomerOnlineHasOne) ? $model->failStatusCustomerOnlineHasOne->name : '', 'Lý do fail: ') ?>
                                    <?= getDetail($model->note, 'Ghi chú: ') ?>
                                    <?= getDetail($model->tt_kh, 'Thông tin KH: ') ?>
                                    <?= getDetail(isset($model->fanpageFacebookHasOne) ? $model->fanpageFacebookHasOne->name : '', 'Fanpage: ') ?>
                                    <?= getDetail(isset($model->agencyHasOne) ? $model->agencyHasOne->name : '', 'Agency: ') ?>
                                    <li>
                                        bởi <a href="#"><?= $userCreatedBy->fullname ?></a> với id <a
                                                href="#"><?= $model->id ?></a>
                                        từ <?= $model->nguonCustomerOnlineHasOne == null ? '-' : $model->nguonCustomerOnlineHasOne->name . ' ' . success; ?>
                                    </li>

                                </ul>
                            </section>
                        </section>
                        <?php
                        // Lấy từng dòng timeline khách hàng bằng lịch sử thao tác của nhân viên.
                        function getSection($act = null, $user, $str, $model)
                        {
                            if (is_array($user->action)) {
                                if (in_array($act, $user->action)) {
                                    ?>
                                    <section class="year">
                                        <h3><?= UserTimelineModel::LIST[$act] ?>
                                        </h3>
                                        <section>
                                            <h4><?= date('h:i d-m-Y', $user->created_at) ?></h4>
                                            <ul>
                                                <?php
                                                $dat_hen = \backend\modules\customer\models\Dep365CustomerOnlineDathenTime::find()
                                                    ->where(['time_change' => $user->created_at])->one();
                                    switch ($act) {
                                                    case UserTimelineModel::ACTION_DAT_HEN:
                                                        if (in_array(UserTimelineModel::ACTION_TAO, $user->action) && isset($dat_hen)) {
                                                            $check_dathen = UserTimelineModel::find()->where(['customer_id' => $model->id])
                                                                ->andWhere(['like', 'action', $act])->count();
                                                            if ($check_dathen <= 1) {
                                                                if (isset($model->statusDatHenHasOne)) {
                                                                    echo getDetail($model->dat_hen == 1 ?
                                                                        $model->statusDatHenHasOne->name . ' ' . success :
                                                                        $model->statusDatHenHasOne->name . ' ' . deny, 'Trạng thái: ');
                                                                }
                                                            } ?>
                                                            <li>
                                                                <?= $model->statusCustomerHasOne->name ?>
                                                                <?= date('h:i d-m-Y', $dat_hen->time_lichhen_new) ?>
                                                                tại cơ sở <?= $model->co_so . ' ' . success; ?>
                                                            </li>
                                                            <?php
                                                        } elseif (in_array(UserTimelineModel::ACTION_CAP_NHAT, $user->action) && isset($dat_hen)) {
                                                            if (isset($model->statusDatHenHasOne)) {
                                                                echo getDetail($model->dat_hen == 1 ?
                                                                    $model->statusDatHenHasOne->name . ' ' . success :
                                                                    $model->statusDatHenHasOne->name . ' ' . deny, 'Trạng thái: ');
                                                            } ?>
                                                            <li>
                                                                <?= $model->statusCustomerHasOne->name ?>
                                                                <?= date('h:i d-m-Y', $dat_hen->date_lichhen_new) ?>
                                                                tại cơ sở <?= $model->co_so . ' ' . success; ?>
                                                            </li>
                                                            <?php
                                                        }
                                                        break;
                                                    case UserTimelineModel::ACTION_THAM_KHAM:
                                                        if ($user->created_at != $model->updated_at) {
                                                            ?>
                                                            <?= getDetail($model->co_so . ' ' . deny, 'Khách chưa đến cơ sở ') ?>
                                                            <?php
                                                        } elseif ($user->created_at == $model->updated_at) {
                                                            ?>
                                                            <li>
                                                                <?= isset($model->statusCustomerGotoAurisHasOne) ? $model->statusCustomerGotoAurisHasOne->name : '' ?>
                                                                <?= !in_array($model->customer_come_time_to, [2, 4]) ? ' ' . success : ' ' . deny ?>
                                                            </li>
                                                            <?= getDetail($model->note_direct, 'Ghi chú của Direct sale: ') ?>
                                                            <?= getDetail($model->tt_kh, 'Tình trạng răng khách hàng: ') ?>
                                                            <?= getDetail($model->customer_gen, 'Tính cách khách hàng: ') ?>
                                                            <?= getDetail($model->customer_mongmuon, 'Mong muốn khách hàng: ') ?>
                                                            <?= getDetail($model->customer_thamkham, 'Kết quả thăm khám: ') ?>
                                                            <li>
                                                                <?= isset($model->statusDatHenHasOne) ? $model->statusDatHenHasOne->name : '' ?>
                                                                <?= isset($model->co_so) ? 'cơ sở ' . $model->co_so . ' ' . success : '' ?>
                                                            </li>
                                                            <?php
                                                        }
                                                        break;
                                                    case UserTimelineModel::ACTION_DON_HANG:
                                                        $order = \backend\modules\clinic\models\PhongKhamDonHangWOrder::find()
                                                            ->where(['created_at' => $user->created_at])->all();
                                                        if (isset($order)) {
                                                            $tongtien = 0;
                                                            foreach ($order as $item) {
                                                                $tongtien += $item->thanh_tien;
                                                            } ?>

                                                            <li>
                                                                <a href="<?= \yii\helpers\Url::toRoute(['/clinic/clinic-order', 'customer_id' => $user->customer_id]) ?>"
                                                                   target="_blank">
                                                                    Chi tiết đơn hàng với giá trị <span
                                                                            class="font-weight-bold"><?= number_format($tongtien, 0, '', '.') . ' ' . success; ?></span>
                                                                </a>
                                                            </li>
                                                            <?php
                                                        }
                                                        break;
                                                    case UserTimelineModel::ACTION_LICH_DIEU_TRI:
                                                        $lichdieutri = \backend\modules\clinic\models\PhongKhamLichDieuTri::find()
                                                            ->where(['created_at' => $user->created_at])->one();
                                                        if (isset($lichdieutri)) {
                                                            ?>
                                                            <?= getDetail(BackendHelpers::getRatings($lichdieutri['thai_do']), 'Thái độ: ') ?>
                                                            <?= getDetail(BackendHelpers::getRatings($lichdieutri['chuyen_mon']), 'Chuyên môn: ') ?>
                                                            <?= getDetail(BackendHelpers::getRatings($lichdieutri['tham_my']), 'Thẩm mỹ: ') ?>
                                                            <?= getDetail($lichdieutri['huong_dieu_tri'], 'Hướng điều trị: ') ?>
                                                            <li>Ekip bác sĩ:
                                                                <?php
                                                                $bacsi = new \common\models\UserProfile();
                                                            echo $bacsi->getFullNameBacSi($lichdieutri['ekip']) . ' ' . success; ?>
                                                            </li>
                                                            <li>Bắt đầu
                                                                <span class="font-weight-bold"><?= date('h:i d-m-Y', $lichdieutri['time_start']) ?></span>
                                                                kết
                                                                thúc
                                                                <span class="font-weight-bold"><?= date('h:i d-m-Y', $lichdieutri['time_end']) . ' ' . success; ?></span>
                                                            </li>
                                                            <?php
                                                        }
                                                        break;
                                                    case UserTimelineModel::ACTION_THANH_TOAN:
                                                        $thanhToan = \backend\modules\clinic\models\PhongKhamDonHangWThanhToan::find()
                                                            ->where(['created_at' => $user->created_at])->one();
                                                        if (isset($thanhToan)) {
                                                            ?>
                                                            <li class="text-lowercase">
                                                                <a href="<?= \yii\helpers\Url::toRoute(['/clinic/clinic-payment', 'order_id' => $thanhToan['phong_kham_don_hang_id']]) ?> "
                                                                   target="_blank">
                                                                    <?= \backend\models\doanhthu\ThanhToanModel::THANHTOAN_TYPE[$thanhToan['tam_ung']] ?>
                                                                    <?= PhongKhamLoaiThanhToan::getOneLTT($thanhToan['loai_thanh_toan'])->name ?>
                                                                    với giá trị
                                                                    <span class="font-weight-bold ">
                                                                    <?= number_format($thanhToan['tien_thanh_toan'], 0, '', '.') . ' ' . success; ?></span></a>
                                                            </li>
                                                            <?php
                                                        }
                                                        break;
                                                } ?>
                                                <li>
                                                    <?= $str ?> bởi <a
                                                            href="#"><?= ($user->nameUserHasOne != null ? $user->nameUserHasOne->fullname : '') . ' ' . success; ?></a>
                                                </li>
                                            </ul>
                                        </section>

                                    </section>
                                    <?php
                                }
                            } elseif ($user->action == $act) {
                                ?>
                                <section class="year">
                                    <h3><?= UserTimelineModel::LIST[$act] ?>
                                    </h3>
                                    <section>
                                        <h4><?= date('h:i d-m-Y', $user->created_at) ?></h4>
                                        <ul>
                                            <li>
                                                <?= $str ?> cập nhật bởi <a
                                                        href="#"><?= $user->nameUserHasOne->fullname . ' ' . success; ?></a>
                                            </li>
                                        </ul>
                                    </section>
                                </section>
                                <?php
                            }
                        }

                        // In ra từng dòng <li> nếu thuộc tín khách hàng đó tồn tại
                        function getDetail($value = null, $key)
                        {
                            if (!empty($value)) {
                                return '<li> ' . $key . $value . '</li>';
                            }
                            return '';
                        }

                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <?php
                $permission = new User();
                $roleUser = $permission->getRoleName(Yii::$app->user->id);
                if ($roleUser == User::USER_DEVELOP && !in_array($model->customer_come_time_to, [2, 4])) {
                    ?>
                    <button type="button" class="btn btn-primary pull-right"
                            id="create_affiliate" data-customer="<?= $model->primaryKey ?>"> <?= $model->is_affiliate_created == 0 ? 'Khởi tạo Affiliate' : 'Cập nhật Affiliate' ?>
                    </button>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <div class="modal-footer p-0"></div>
<?php
$url_post = yii\helpers\Url::toRoute('/customer/customer-online/create-affiliate');
$script = <<< JS
$('#create_affiliate').click( function() {
    var customer = $(this).attr('data-customer') || null;
    if(customer !== null) {
        $('#custom-modal .modal-body').myLoading({
            opacity: true
        });
        $.ajax({
            url: '$url_post',
            type: 'POST',
            dataType: 'json',
            data: {
                customer: customer
            },
            success: function(data) {
                if(data.status == 200){
                    toastr.success(data.mess, 'Affiliate thông báo');
                    $('#custom-modal .close').trigger('click');
                } else {
                    toastr.error(data.mess, 'Affiliate thông báo');
                    $('#custom-modal .modal-body').myUnloading();
                }
            }
        }).fail(function(f){
            toastr.error('Có lỗi khi cập nhật', 'Affiliate thông báo');
            $('#custom-modal .modal-body').myUnloading();
        });
    }
});
JS;
$this->registerJs($script);

?>