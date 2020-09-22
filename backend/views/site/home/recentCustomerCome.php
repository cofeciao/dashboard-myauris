<?php
use yii\helpers\StringHelper;

$key = '';
$action = '';
if ($data_report) {
    foreach ($data_report as $customer) {
        if ($customer->statusCustomerHasOne != null) {
            $key = '<span class="badge badge-danger">Khách hàng mới</span> 
                    <code><i class="icon-call-end"> </i>xxx<b>' . substr($customer->phone, -3) . '</b> </code> 
                   <code> <i class="icon-pointer"> </i>' . $customer->provinceHasOne->name . '</code>';
            $action = '<span class="badge bg-primary float-right p-1 white">' . $customer->statusCustomerHasOne->name . '</span>';
        }
        if ($customer->statusDatHenHasOne != null) {
            $key = '<span class="badge badge-warning">Đặt hẹn</span> 
                    <code><i class="icon-call-end"> </i>xxx<b>' . substr($customer->phone, -3) . '</b> </code> 
                    <code><i class="icon-pointer"> </i>' . $customer->provinceHasOne->name . '</code>';
            $action = '<span class="badge badge-success float-right">' . $customer->statusDatHenHasOne->name . '</span>';
        }
        if ($customer->statusCustomerGotoAurisHasOne != null) {
            $key = '<span class="badge badge-danger">Thăm khám</span>
                    <code><i class="icon-call-end"> </i>xxx<b>' . substr($customer->phone, -3) . '</b> </code> 
                    <code><i class="icon-pointer"> </i>' . $customer->provinceHasOne->name . '</code>';
            $action = '<span class="badge badge-primary float-right ">' . $customer->statusCustomerGotoAurisHasOne->name . '</span>';
        } ?>
        <span class="media border-0">
            <div class="media-left pr-1">
              <span class="avatar avatar-md avatar-online">
                 <img class="media-object rounded-circle"
                      src="<?= $customer->avatar == null || !file_exists('/uploads/avatar/70x70/' . $customer->avatar) ? '/local/default/avatar-default.png' : '/uploads/avatar/70x70/' . $customer->avatar; ?>"
                      alt=""></span>
            </div>
            <div class="media-body w-100">
                <h6 class="list-group-item-heading">
                    <?php
                    $fullname = $customer->full_name;
        echo $fullname == null ? StringHelper::truncate($customer->name, 25, '...') : StringHelper::truncate($fullname, 25, '...'); ?>
                    <?= $action; ?>
                </h6>
                <code class="float-right"><?= $customer->customer_come == null ? '' : '<i class="icon-clock"> </i>'.date('H:i d-m', $customer->customer_come); ?></code>
                <p class="list-group-item-text mb-0"><?= $key; ?></p>
            </div>
        </span>
        <?php
    }
}
?>

