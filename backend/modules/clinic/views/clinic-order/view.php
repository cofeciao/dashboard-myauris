<?php


use yii\widgets\ActiveForm;
use dosamigos\datetimepicker\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model backend\modules\clinic\models\PhongKhamDonHang */

$this->title = $model->clinicHasOne->full_name == null ? $model->clinicHasOne->forename : $model->clinicHasOne->full_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Phòng khám'), 'url' => ['/clinic/clinic']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Đơn hàng'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="modal-header bg-blue-grey bg-lighten-2 white">
    <h4 class="modal-title">Khách hàng: <?= $this->title; ?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    'options' => [
        'data-pjax' => 1,
        'id' => 'form-search-order',
    ],
]); ?>
<div class="modal-body view-order-customer ">
    <div class="detail-customer">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Khách hàng:</label>
                    <div class="form-warp">
                        <label><?= $model->clinicHasOne->full_name == null ? $model->clinicHasOne->forename : $model->clinicHasOne->full_name; ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Số điện thoại:</label>
                    <div class="form-warp">
                        <label><?= $model->clinicHasOne == null ? ';' : $model->clinicHasOne->phone; ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Mã khách hàng:</label>
                    <div class="form-warp">
                        <label><?= $model->clinicHasOne->customer_code; ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Tên cơ sở:</label>
                    <div class="form-warp">
                        <label><?= $model->coSoHasOne == null ? ';' : $model->coSoHasOne->name; ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Mã hóa đơn:</label>
                    <div class="form-warp">
                        <label><?= $model->order_code; ?></label>
                    </div>
                </div>
            </div>
            <div class="col-md-6">

                <div class="form-group">
                    <label>Ngày tạo:</label>
                    <div class="form-warp">
                        <label><?= date('d-m-Y h:i', $model->created_at); ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Ngày cập nhật:</label>
                    <div class="form-warp">
                        <label><?= date('d-m-Y h:i', $model->updated_at); ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Người tạo:</label>
                    <div class="form-warp">
                        <label><?php
                            $user = new backend\modules\clinic\models\PhongKhamDonHang();
                            $userCreatedBy = $user->getUserCreatedBy($model->created_by);
                            if ($userCreatedBy == false) {
                                echo '-';
                            }
                            echo $userCreatedBy->fullname; ?>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Người cập nhật:</label>
                    <div class="form-warp">
                        <label><?php
                            $user = new backend\modules\clinic\models\PhongKhamDonHang();
                            $userCreatedBy = $user->getUserCreatedBy($model->updated_by);
                            if ($userCreatedBy == false) {
                                echo '-';
                            }
                            echo $userCreatedBy->fullname; ?>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-lg-12 ">
            <div class="form-group">
                <label class="font-weight-bold">Chi tiết đơn hàng:</label>
            </div>
            <div class="table table-bordered">
                <?= $this->render('_viewOderClinic', ['model' => $model]); ?>
            </div>
        </div>
        <div class="col-lg-12 ">
            <div class="form-group">
                <label class="font-weight-bold">Chi tiết thanh toán:</label>
            </div>
            <div class="table table-bordered">
                <?= $this->render('_viewThanhToanClinic', ['model' => $model]); ?>
            </div>
        </div>
        <div class="col-lg-12 ">
            <div class="detail-order customer-status">
                <aside class="txtR flr">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                            <tr>
                                <td colspan="3">Chiếc khấu:</td>
                                <td><span class="tong-cong">
                                <?php if ($model->chiet_khau == null || $model->chiet_khau == '') {
                                echo null;
                            }
                                echo number_format($model->chiet_khau, 0, '', '.'); ?>
                                </span>
                            </tr>
                            <tr>
                                <td colspan="3">Còn nợ:</td>
                                <td>
                            <span class="con-lai">
                                <?php
                                $dh_thanh_tien = $model->dh_thanh_tien == null || $model->dh_thanh_tien == '' ? 0 : $model->dh_thanh_tien;
                                $chiet_khau = $model->chiet_khau == null || $model->chiet_khau == '' ? 0 : $model->chiet_khau;
                                $dat_coc = $model->dat_coc == null || $model->dat_coc == '' ? 0 : $model->dat_coc;
                                $thanh_toan = $model->thanh_toan == null || $model->thanh_toan == '' ? 0 : $model->thanh_toan;
                                $total = number_format($dh_thanh_tien - ($chiet_khau + $dat_coc + $thanh_toan), 0, '', '.');
                                if ($total < 0) {
                                    $total = 0;
                                }
                                if ($total == 0) {
                                    echo '<span style="color: #0E7E12">' . $total . '</span>';
                                } else {
                                    echo '<span style="color: red">' . $total . '</span>';
                                }
                                ?>
                            </span>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </aside>
            </div>
        </div>

    </div>

</div>
<?php ActiveForm::end(); ?>
<div class="modal-footer p-0"></div>
</div>
