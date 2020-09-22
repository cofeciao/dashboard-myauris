<?php

$this->registerCss('
body, body * {font-family: Time News Roman!important}

.row {
  display: flex;
}

/* Create two equal columns that sits next to each other */
.column {
  flex: 50%;
  padding: 10px;
}
');
$user = new \backend\modules\user\models\User();
$auth = Yii::$app->authManager;
$empty_string = '........................';
$moneytotext = new \common\models\MoneyToTextModel();
/*
try {
    \Yii::$app->commandBus->handle(new \common\commands\SendEmailCommand([
        'subject' => '[Đề xuất chi] Có đề xuất chi mới',
        'view' => 'dexuatchi/noti_new_dexuat',
        'to' => $model->chosenHasOne->email,
        'cc' => ['dev.thang@myauris.vn'],
        'params' => [
            'dexuat' => $model,
//                                'tieuchi' => $tieuchi,
        ]
    ]));
} catch (\Exception $exception) {
    throw(new \Exception('Không thể gửi mail'));
}*/
/*
try {
    \Yii::$app->commandBus->handle(new \common\commands\SendEmailCommand([
        'subject' => '[Đề xuất chi] Có đề xuất chi mới',
        'view' => 'dexuatchi/noti_new_dexuat',
        'to' => 'giang@356dep.vn',
        'cc' => ['dev.thang@myauris.vn'],
        'params' => [
            'dexuat' => $model,
//                                'tieuchi' => $tieuchi,
        ]
    ]));
} catch (\Exception $exception) {
    throw(new \Exception('Không thể gửi mail'));
}
try {
    \Yii::$app->commandBus->handle(new \common\commands\SendEmailCommand([
        'subject' => '[Đề xuất chi] Có đề xuất chi mới',
        'view' => 'dexuatchi/noti_new_dexuat',
        'to' => 'hieu@myauris.vn',
        'cc' => ['dev.thang@myauris.vn'],
        'params' => [
            'dexuat' => $model,
//                                'tieuchi' => $tieuchi,
        ]
    ]));
} catch (\Exception $exception) {
    throw(new \Exception('Không thể gửi mail'));
}*/
?>
<div id="deposit-template">
    <div class="deposit-wrap">
        <h1 class="text-center my-2">Giấy Đề Nghị <?php if (!empty($model->type_dexuat)) {
                echo \backend\modules\chi\models\DeXuatChiModel::TYPE_DEXUAT[$model->type_dexuat];
            } ?></h1>
        <p>Mã số: <?php echo $model->id ?></p>
        <div id="deposit-customer-details">
            <ul class="list-unstyled">
                <li>Đề Xuất Chi: <?php echo !empty($model->title) ? $model->title : $empty_string ?></li>
                <li>Người đề xuất: <?php
                    $username = (\backend\modules\user\models\User::getUserInfo($model->created_by))->fullname;
                    echo !empty($username) ? $username : $empty_string ?>
                    <div style="float:right">Chức
                        vụ: <?php $role = ($auth->getRole($user->getRoleName($model->created_by)))->description;
                        echo !empty($role) ? $role : $empty_string;
                        ?>
                    </div>
                </li>
                <li>Người triển khai: <?php
                    $username = (\backend\modules\user\models\User::getUserInfo($model->nguoi_trien_khai))->fullname;
                    echo !empty($username) ? $username : $empty_string ?>
                    <div style="float:right">Chức
                        vụ: <?php $role = ($auth->getRole($user->getRoleName($model->nguoi_trien_khai)))->description;
                        echo !empty($role) ? $role : $empty_string;
                        ?></div>
                </li>
                <li>Số tiền chi: <?php
                    setlocale(LC_MONETARY, 'vi-VN');
                    echo !empty($model->so_tien_chi) ? number_format($model->so_tien_chi, 0, '', '.') . 'VND' : ''; ?></li>
                <li>Viết bằng
                    chữ: <?php
                    echo !empty($model->so_tien_chi) ? $moneytotext->getTextNumber($model->so_tien_chi) . ' VND' : '';
	                ?></li>
                <li>Khoản
                    chi: <?php
                    $khoanchi = \backend\modules\chi\models\KhoanChi::findOne(['id' => $model->khoan_chi]);
                    echo !empty($khoanchi->name) ? $khoanchi->name : $empty_string ?></li>
                <li>Thời hạn phải thanh
                    toán: <?php echo !empty($model->thoi_han_thanh_toan) ? Yii::$app->formatter->asDatetime($model->thoi_han_thanh_toan, 'php:d/m/Y') : $empty_string ?></li>

            </ul>
            <ul class="list-unstyled">
                <li>
                    Hình thức thanh
                    toán: <?php echo !empty($model->method_payment) ? $model->payment_method[$model->method_payment] : $empty_string ?>
                </li>
            </ul>
            <?php if (in_array($model->method_payment, [\backend\modules\chi\models\DeXuatChiModel::SCENARIO_CHUYENKHOAN, \backend\modules\chi\models\DeXuatChiModel::SCENARIO_CHUYENKHOAN_QUY])) { ?>
                <div class="row">
                    <div class="column">
                        <ul>
                            <li>Thanh toán
                                cho: <?php echo !empty($model->receiver) ? $model->receiver : $empty_string ?>
                            </li>
                            <li>Số điện
                                thoại: <?php
                                echo isset($model->receiver_phone) ? $model->receiver_phone : $empty_string ?>
                            </li>
                        </ul>
                    </div>
                    <div class="column">
                        <ul>

                            <li>
                                Tên chủ TK
                                NH: <?php echo !empty($model->owner_credit_name) ? $model->owner_credit_name : $empty_string ?>
                            </li>
                            <li>
                                Tại ngân
                                hàng: <?php echo !empty($model->banking_name) ? $model->banking_name : $empty_string; ?>
                            </li>
                            <li>
                                Số TK ngân
                                hàng: <?php echo !empty($model->credit_number) ? $model->credit_number : $empty_string; ?>
                            </li>
                        </ul>
                    </div>
                </div>
            <?php } ?>
            <hr>
            <?php
            print_r($this->context->renderTieuChiView($model->tieu_chi_group, 'style_2'));
            ?>
        </div>
        <div id="deposit-footer">
            <p><strong class="font-weight-bold"><em>Ghi chú:</em></strong></p>
            <ul class="list-note list-unstyled">
                <li>
                    <pre>

.................................................................................................................................................

.................................................................................................................................................
                    </pre>
                </li>

                <li>
                </li>
            </ul>
            <div class="row">
                <div class="col-7">

                </div>
                <div class="col-5">
                    <div class="text-right">
                        Ngày <?= date('d') ?> tháng <?= date('m') ?> năm <?= date('Y') ?>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-4">
                    <div class="text-center">
                        Trưởng Phòng
                        <br><br><br><br><br><br><br>
                        <?php

                        if ($model->leaderHasOne == null) {
                            echo '';
                        } else {
                            echo $model->leaderHasOne->userProfile->fullname;
                        }
                        ?>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-center">Kế Toán
                        <br><br><br><br><br><br><br>
                        <?php
                        if ($model->accountantHasOne == null) {
                            echo '';
                        } else {
                            echo $model->accountantHasOne->userProfile->fullname;
                        }

                        ?>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-center">Người Đề Xuất<br><br><br><br><br><br><br>
                        <?php
                        if ($model->nguoidexuatHasOne == null) {
                            echo '--';
                        } else {
                            echo $model->nguoidexuatHasOne->fullname;
                        }
                        ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
