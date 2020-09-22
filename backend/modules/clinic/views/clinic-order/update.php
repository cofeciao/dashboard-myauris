<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\clinic\models\PhongKhamDonHang */

$this->title                   = Yii::t( 'backend', 'Update' );
$this->params['breadcrumbs'][] = [ 'label' => 'Đơn hàng', 'url' => [ 'index' ] ];
$this->params['breadcrumbs'][] = [ 'label' => $model->id, 'url' => [ 'view', 'id' => $model->id ] ];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="modal-header bg-blue-grey bg-lighten-2 white">
    <h4 class="modal-title">
		<?php
		if ( isset( $customer ) ) {
			$nameCustomer = $customer->full_name == null ? $customer->name : $customer->full_name; ?>
            Khách hàng: <span class="card-title"><?= $nameCustomer; ?></span> -
                                                                              Mã: <span
                    class="card-title"><?= $customer->customer_code; ?></span>
			<?php
		} ?>
    </h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?= $this->render( '_form', [
	'model'         => $model,
	'customer'      => $customer,
	'orderData'     => $orderData,
	'listKhuyenMai' => $listKhuyenMai,
	'couponModel'   => $couponModel

] ) ?>
