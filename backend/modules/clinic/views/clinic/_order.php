<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 19-Jan-19
 * Time: 10:03 AM
 */

use unclead\multipleinput\MultipleInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\modules\clinic\models\PhongKhamDichVu;
use backend\modules\clinic\models\PhongKhamSanPham;
use backend\modules\clinic\models\PhongKhamKhuyenMai;

$dayOfWeek = [
	0 => 'Chủ Nhật',
	1 => 'Thứ Hai',
	2 => 'Thứ Ba',
	3 => 'Thứ Tư',
	4 => 'Thứ Năm',
	5 => 'Thứ Sáu',
	6 => 'Thứ Bảy',
];

?>
<?php $form = ActiveForm::begin( [
	'id'                     => 'form-don-hang',
	'enableAjaxValidation'   => true,
	'enableClientValidation' => true,
	'validationUrl'          => [ 'validate-order', 'id' => $customer->primaryKey ],
	'action'                 => Url::toRoute( [ 'order-customer', 'id' => $customer->primaryKey ] ),
	'options'                => [
		'class'              => 'form form-horizontal',
		'redirect-on-submit' => Url::toRoute( [ '/clinic/clinic-order', 'customer_id' => $customer->primaryKey ] )
	]
] ); ?>
    <button type="button" class="close hide" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <div class="modal-body no-header">
        <div class="order-info row">
            <div class="customer-info col-md-6 col-sm-4 col-4">
                <div class="ci-row">Khách hàng: <span class="font-weight-bold"><?= $customer->name ?></span></div>
                <div class="ci-row">Mã khách hàng: <span class="font-weight-bold"><?= $customer->customer_code ?></span>
                </div>
            </div>
            <div class="date col-md-6 col-sm-8 col-8 text-right">
				<?= $dayOfWeek[ date( 'w' ) ] ?>, Ngày <?= date( 'd' ) ?> Tháng <?= date( 'm' ) ?>Năm <?= date( 'Y' ) ?>
            </div>
        </div>
        <div class="order-title text-center">Đơn hàng</div>
        <div class="order-content order-list customer-status">
			<?php
			if ( $customer ) {
				echo $form->field( $model, 'customer_id' )->hiddenInput( [ 'value' => $customer->id ] )->label( false );
			}
			?>

			<?= $form->field( $model, 'dich_vu', [
				'template' => '{input}',
			] )->hiddenInput()->label( false ); ?>

			<?php
			$model->customer_order = $orderData;

			$arrDV    = ArrayHelper::map( PhongKhamDichVu::getDichVu(), 'id', 'name' );
			$arrDV[0] = 'Chọn sản phẩm...';
			ksort( $arrDV );

			?>
			<?php echo $form->field( $model, 'customer_order', [
				'template' => '{input}'
			] )->widget( MultipleInput::class, [
				'max'              => 5,
				'min'              => 1,
				'allowEmptyList'   => false,
				'enableGuessTitle' => true,
				'cloneButton'      => false,
				'columns'          => [
					[
						'name'    => 'id',
						'type'    => 'hiddenInput',
						'value'   => function ( $data ) {
//                                                $data = json_decode(json_encode($data), true);
							if ( $data == null || ! is_array( $data ) ) {
								return null;
							}
							$result = $data['id'];

							return $result;
						},
						'options' => [
							'class' => 'id_order',
						]
					],
					[
						'name'          => 'dich_vu',
						'title'         => 'Dịch vụ',
						'defaultValue'  => 0,
						'value'         => function ( $data ) {
							return null;
						},
						'options'       => [
							'class'    => 'form-control dich-vu',
							'prompt'   => 'Chọn dịch vụ...',
							'readOnly' => true
						],
						'headerOptions' => [
							'width' => '180px',
						]
					],
					[
						'name'         => 'san_pham',
						'type'         => 'dropDownList',
						'title'        => 'Sản phẩm',
						'defaultValue' => 0,
						'value'        => function ( $data ) {
							return null;
						},
						'items'        => function ( $data ) {
							return ArrayHelper::map( PhongKhamSanPham::getArraySanPhamByDichVu(), 'id', 'name' );
						},
						'options'      => [
							'class'  => 'select2 text-left sl-sp san-pham-clinic',
							'prompt' => 'Chọn sản phẩm...'
						]
					],
					[
						'name'          => 'so_luong',
						'title'         => 'Số lượng',
						'defaultValue'  => 1,
						'value'         => function ( $data ) {
							if ( $data == null || ! is_array( $data ) ) {
								return 1;
							}
							$result = $data['so_luong'] == null ? 1 : $data['so_luong'];

							return $result;
						},
						'options'       => [
							'type'  => 'number',
							'class' => 'input-priority sl-sp so-luong-clinic',
						],
						'headerOptions' => [
							'width' => '70px',
						]
					],
					[
						'name'          => 'don_gia',
						'title'         => 'Đơn giá',
						'value'         => function () {
							return null;
						},
						'options'       => [
							'class'    => 'don-gia text-right',
							'readonly' => 'readonly',
						],
						'headerOptions' => [
							'width' => '150px',
						]
					],
					[
						'name'          => 'thanh_tien',
						'title'         => 'Thành tiền',
						'defaultValue'  => 0,
						'value'         => function ( $data ) {
							if ( $data == null || ! is_array( $data ) || $data['thanh_tien'] == '' ) {
								return null;
							}

							return number_format( $data['thanh_tien'], 0, '', '.' );
						},
						'options'       => [
							'class'    => 'thanh-tien text-right',
							'readonly' => 'readonly',
						],
						'headerOptions' => [
							'width' => '150px',
						]
					],
					[
						'name'          => 'chiet_khau_order',
						'title'         => 'Chiết khấu',
						'defaultValue'  => 0,
						'value'         => function ( $data ) {
							if ( $data == null || ! is_array( $data ) || $data['chiet_khau_order'] == '' ) {
								return null;
							}

							return number_format( $data['chiet_khau_order'], 0, '', '.' );
						},
						'options'       => function ( $data ) {
							return [
								'class'        => 'on-keyup chiet-khau-order',
								'data-content' => ( $data == null || ! is_array( $data ) ) ? '' : $data['ly_do_chiet_khau']
							];
						},
						'headerOptions' => [
							'width' => '150px',
						]
					],
					[
						'name'          => 'chiet_khau_theo_order',
						'type'          => 'dropDownList',
						'title'         => 'Chiết khấu theo',
						'defaultValue'  => null,
						'value'         => function ( $data ) {
							return null;
						},
						'items'         => function ( $data ) {
							return PhongKhamKhuyenMai::TYPE;
						},
						'options'       => [
							'class' => 'select2 text-left chiet-khau-theo-order',
						],
						'headerOptions' => [
							'width' => '70px',
						]
					],
				],
				'iconMap'          => [
					'myFt' => [
						'drag-handle' => 'ft-file-plus',
						'remove'      => 'ft-delete my-remove',
						'add'         => 'ft-plus',
						'clone'       => 'ft-file-plus my-clone',
					],
				],
				'iconSource'       => 'myFt',
			] )->label( false );
			?>
            <div class="order-payment">
                <div class="block-left">
                    <div class="form-group row coupon-row text-left">
                        <div class="col-4">Coupon Code:</div>
                        <div class="col-4 coupon-input-field">
                            <div class="input-coupon-button">
<!--								--><?php //echo Html::textInput( 'coupon_code', '', [
//									'class' => 'form-control  coupon-code',
//								] ) ?>
								<?php echo $form->field( $couponModel, 'coupon_code' )->textInput(['class'=>'form-control  coupon-code'])->label(false) ?>
                                <a class="btn btn-clear-coupon"></a>
                            </div>
                            <span class="coupon-info"></span>

                        </div>
                        <div class="col-4">
							<?php
							echo Html::a( 'Check Code', Url::toRoute( '/clinic/clinic-order/check-coupon-code' ), [ 'class' => 'btn-checkcode btn btn-success' ] );
							?>
                        </div>
                    </div>
                    <div class="info-coupon-general hide">
                        <div class="form-group row info-coupon text-left">
                            <div class="col-4 coupon-name">
                                Tên Coupon:
                                <div class="text"></div>
                            </div>
                            <div class="col-3 coupon-price">
                                Giá khuyến mãi:
                                <div class="text"></div>
								<?php echo Html::hiddenInput( 'coupon_price', '',
									[ 'class' => 'coupon-price-input' ] ); ?>
                            </div>
                            <div class="col-3 coupon-status">
                                Status:
                                <div class="text"></div>
                            </div>
                            <div class="col-2 coupon-payment-status">Thanh toán:
                                <div class="text"></div>
                            </div>

                        </div>
                        <div class="form-group row info-customer text-left">
                            <div class="col-3">Thông tin khách hàng:</div>
                            <div class="col-3 customer-name">Tên:
                                <div class="text"></div>

                            </div>
                            <div class="col-3 customer-phone">Số điện thoại:
                                <div class="text"></div>
                            </div>
                            <div class="col-3 customer-email">Email:
                                <div class="text"></div>

                            </div>
                        </div>
                    </div>

                </div>
                <div class="block-right">
                    <div class="form-group row order-row text-right">
                        <div class="col-8">Tổng:</div>
                        <div class="col-4">
                            <span id="tong">-</span>
                        </div>
                    </div>
                    <div class="form-group row order-row text-right">
                        <div class="col-8">Chiết khấu:</div>
                        <div class="col-4">
                            <span id="chiet-khau"><?= $model->chiet_khau != null ? number_format( $model->chiet_khau, 0, '', '.' ) : 0 ?></span>
                        </div>
                    </div>
                    <div class="form-group row order-row text-right">
                        <div class="col-8">Tạm tính:</div>
                        <div class="col-4">
                            <span id="tam-tinh">-</span>
                        </div>
                    </div>
					<?php if ( isset( $listKhuyenMai ) && is_array( $listKhuyenMai ) ) { ?>
                        <div class="form-group row order-row text-right">
                            <div class="col-8">Chương trình khuyến mãi:</div>
                            <div class="col-4 khuyen-mai-content">
								<?= $form->field( $model, 'khuyen_mai' )->dropDownList( ArrayHelper::map( $listKhuyenMai, 'id', 'name' ), [
									'prompt' => 'Chọn chương trình...',
									'class'  => 'form-control khuyen-mai'
								] )->label( false ) ?>
                                <span class="khuyen-mai-info"></span>
								<?= Html::hiddenInput( '', '', [ 'class' => 'khuyenmai_price' ] ) ?>
								<?= Html::hiddenInput( '', '', [ 'class' => 'khuyenmai_type' ] ) ?>
                            </div>
                        </div>
					<?php } ?>
                    <div class="form-group row">
                        <div class="col-8"></div>
                        <div class="col-4">
                            <hr class="m-0"/>
                        </div>
                    </div>
                    <div class="form-group row order-row text-right">
                        <div class="col-8">Thành tiền:</div>
                        <div class="col-4">
                            <span id="thanh-tien">-</span>
							<?= $form->field( $model, 'chiet_khau' )->hiddenInput( [ 'class' => 'on-keyup' ] )->label( false ) ?>
                        </div>
                    </div>
                </div>
            </div>
			<?= $form->errorSummary( $model ); ?>
			<?= Html::hiddenInput( '', 1, [ 'id' => 'button-handle' ] ) ?>
        </div>
    </div>
    <div class="modal-footer">
		<?= Html::resetButton( '<i class="ft-x"></i> Close', [
			'class' =>
				'btn btn-warning mr-1'
		] ) ?>
		<?= Html::submitButton( '<i class="fa fa-print"></i> Save & Print', [
			'class' => 'btn btn-success',
			'id'    => 'btn-print'
		] ) ?>
		<?= Html::submitButton(
			'<i class="fa fa-check-square-o"></i> Save',
			[ 'class' => 'btn btn-primary', 'id' => 'btn-submit' ]
		) ?>
    </div>
<?php ActiveForm::end(); ?>

<?php
$order = <<< JS
$('.vertical-scroll').perfectScrollbar({
    suppressScrollX : true,
    theme: 'dark',
    wheelPropagation: true
});
jQuery('.multiple-input').on('afterInit', function(){
    addLyDoChietKhau();
}).on('afterAddRow', function(e, row){
    row.find('.id_order').val('0');
    row.find('.select2').select2();
    addLyDoChietKhau();
    handleChietKhau();
}).on('afterDeleteRow', function(){
    addLyDoChietKhau();
    handleChietKhau();
});

$('.on-keyup').each(function() {
    var order_discount = $(this).val().replace(/\./g, '');
    $(this).val(addCommas(order_discount));
});
handleChietKhau();
$('#btn-submit').on('click', function(e) {
    $('#button-handle').val(1);
});
$('#btn-print').on('click', function(){
    $('#button-handle').val(2);
});
JS;
$this->registerJs( $order, \yii\web\View::POS_END );
$this->registerCss( '
.text-left .order-content{text-align:left!important}
.select2-container{width:100%!important}
#custom-modal .modal-dialog.modal-lg {max-width:1200px;}

' );
