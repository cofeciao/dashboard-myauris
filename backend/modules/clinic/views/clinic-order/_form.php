<?php

use backend\modules\clinic\models\PhongKhamDichVu;
use backend\modules\clinic\models\PhongKhamDirectSale;
use backend\modules\clinic\models\PhongKhamKhuyenMai;
use backend\modules\clinic\models\PhongKhamLoaiThanhToan;
use backend\modules\clinic\models\PhongKhamSanPham;
use unclead\multipleinput\MultipleInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$css = <<< CSS
#custom-modal .modal-dialog.modal-lg {max-width:1200px}
td.list-cell__san_pham .select2-container {width: 100% !important}
CSS;
$this->registerCss( $css );

$form = ActiveForm::begin( [
	'class'                  => 'form form-horizontal striped-rows',
	'id'                     => 'form-don-hang',
	'enableAjaxValidation'   => true,
	'enableClientValidation' => true,
	'validationUrl'          => [ 'validate-order', 'id' => $model->getAttribute( 'customer_id' ) ],
//   'action' => Url::toRoute(['order-customer', 'id' => $model->getAttribute('customer_id')]),
] ); ?>
    <div class="modal-body order-content order-list customer-status text-left">
		<?php
		if ( isset( $customer ) ) {
			echo $form->field( $model, 'customer_id' )->hiddenInput( [ 'value' => $customer->id ] )->label( false );
		}
		?>
		<?= $form->field( $model, 'dich_vu', [
			'template' => '{input}',
		] )->hiddenInput()->label( false ); ?>

		<?php
		$model->customer_order = $orderData;

		$arrDV = ArrayHelper::map( PhongKhamDichVu::getDichVu(), 'id', 'name' );
		//        $arrDV[0] = 'Chọn sản phẩm...';
		ksort( $arrDV );

		$arrThanhToan = ArrayHelper::map( PhongKhamLoaiThanhToan::getClinicLoaiThanhToan(), 'id', 'name' );
		?>
		<?= $form->field( $model, 'customer_order', [
			'template' => '{input}'
		] )->widget( MultipleInput::class, [
			'max'              => 10,
			'min'              => 1,
			'allowEmptyList'   => false,
			'enableGuessTitle' => true,
			'cloneButton'      => false,
			'columns'          => [
				[
					'name'    => 'id',
					'type'    => 'hiddenInput',
					'value'   => function ( $data ) {
						if ( $data == null || ! isset( $data['id'] ) ) {
							return null;
						}

						return $data['id'];
					},
					'options' => [
						'class' => 'id_order',
					]
				],
				[
					'name'         => 'dich_vu',
//                    'type' => 'dropDownList',
					'title'        => 'Dịch vụ',
					'defaultValue' => 0,
					'value'        => function ( $data ) {
//                        return $data['dich_vu'];
						if ( $data == null || ! isset( $data['dichVuHasOne'] ) ) {
							return null;
						}

						return $data['dichVuHasOne'] != null ? $data['dichVuHasOne']['name'] : null;
					},
					'options'      => [
						'class'    => 'dich-vu',
						'prompt'   => 'Chọn dịch vụ...',
						'readOnly' => true
					]
				],
				[
					'name'         => 'san_pham',
					'type'         => 'dropDownList',
					'title'        => 'Sản phẩm',
					'defaultValue' => 0,
					'value'        => function ( $data ) {
						if ( $data == null || ! isset( $data['san_pham'] ) ) {
							return null;
						}

						return $data['san_pham'];
					},
					'items'        => function ( $data ) {
						return ArrayHelper::map( PhongKhamSanPham::getArraySanPhamByDichVu(), 'id', 'name' );
					},
					'options'      => function ( $data ) {
						return [
							'class'         => 'sl-sp san-pham-clinic select2',
							'prompt'        => 'Chọn sản phẩm...',
							'data-selected' => ( $data == null || ! isset( $data['san_pham'] ) ) ? '' : $data['san_pham']
						];
					}
				],
				[
					'name'          => 'so_luong',
					'title'         => 'Số lượng',
					'defaultValue'  => 1,
					'value'         => function ( $data ) {
						if ( $data == null || ! isset( $data['so_luong'] ) ) {
							return 1;
						}
//                                                $data = json_decode(json_encode($data), true);
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
					'name'          => 'thanh_tien',
					'title'         => 'Thành tiền',
					'defaultValue'  => 0,
					'value'         => function ( $data ) {
//                                                $data = json_decode(json_encode($data), true);
						if ( $data == null || $data['thanh_tien'] == '' ) {
							return null;
						}

						return number_format( $data['thanh_tien'], 0, '', '.' );
					},
					'options'       => [
						'class'    => 'thanh-tien',
						'readonly' => true,
					],
					'headerOptions' => [
						'width' => '120px',
					]
				],
				[
					'name'          => 'chiet_khau_order',
					'title'         => 'Chiết khấu',
					'defaultValue'  => 0,
					'value'         => function ( $data ) {
						if ( $data == null || $data['chiet_khau_order'] == '' ) {
							return null;
						}

						return number_format( $data['chiet_khau_order'], 0, '', '.' );
					},
					'options'       => function ( $data ) {
						return [
							'class'        => 'on-keyup chiet-khau-order',
							'data-content' => ( $data == null || ! isset( $data['ly_do_chiet_khau'] ) ) ? null : $data['ly_do_chiet_khau']
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
						if ( $data == null || $data['chiet_khau_theo_order'] == '' ) {
							return null;
						}

						return $data['chiet_khau_theo_order'];
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
							<?php echo $form->field( $couponModel, 'coupon_code' )->textInput( [ 'class'    => 'form-control  coupon-code',
							                                                                     'disabled' => 'disabled'
							] )->label( false ) ?>
                        </div>
                        <span class="coupon-info"></span>

                    </div>
                    <div class="col-4">
						<?php
						echo Html::a( 'Check Code', \yii\helpers\Url::toRoute( '/clinic/clinic-order/check-coupon-code' ), [ 'class' => 'btn-checkcode btn btn-success' ] );
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
							<?php echo Html::hiddenInput( 'coupon_price', $couponModel->giaban,
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
							<?= Html::hiddenInput( '', ( array_key_exists( $model->khuyen_mai, $listKhuyenMai ) ? $listKhuyenMai[ $model->khuyen_mai ]['price'] : '' ), [ 'class' => 'khuyenmai_price' ] ) ?>
							<?= Html::hiddenInput( '', ( array_key_exists( $model->khuyen_mai, $listKhuyenMai ) ? $listKhuyenMai[ $model->khuyen_mai ]['type'] : '' ), [ 'class' => 'khuyenmai_type' ] ) ?>
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
    </div>
    <div class="modal-footer">
		<?= Html::resetButton( '<i class="ft-x"></i> Close', [
			'class' =>
				'btn btn-warning mr-1'
		] ) ?>
		<?= Html::submitButton(
			'<i class="fa fa-check-square-o"></i> Save',
			[ 'class' => 'btn btn-primary' ]
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
JS;
$this->registerJs( $order );
