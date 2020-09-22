<?php

use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
use \common\models\User;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\modules\chi\models\NhomChi;
use \backend\modules\chi\models\KhoanChi;
use backend\modules\chi\models\DeXuatChi;
use backend\modules\chi\models\DanhMucChi;
use backend\modules\user\models\UserSubRole;
use unclead\multipleinput\MultipleInput;
use \backend\modules\chi\models\DeXuatChiModel;
use dosamigos\datetimepicker\DateTimePicker;
use backend\modules\chi\models\Comment;
use backend\widgets\ChatWidget;

/* @var $this yii\web\View */
/* @var $model backend\modules\chi\models\DeXuatChi */
/* @var $modelDeadline backend\modules\chi\models\Deadline */
/* @var $form yii\widgets\ActiveForm */
/* @var $formHoSo \backend\modules\chi\models\form\FormHoSo */
/* @var $listHoSo array */
/* @var $listComment array */
/* @var $formComment \backend\modules\chi\models\Comment */

if ( $model != null && $model->thoi_gian_bat_dau != null ) {
	$model->thoi_gian_bat_dau = date( 'd-m-Y h:i', $model->thoi_gian_bat_dau );
}
if ( $model != null && $model->thoi_gian_ket_thuc != null ) {
	$model->thoi_gian_ket_thuc = date( 'd-m-Y h:i', $model->thoi_gian_ket_thuc );
}
if ( $model->thoi_han_thanh_toan != null ) {
	$model->thoi_han_thanh_toan = date( 'd-m-Y h:i', $model->thoi_han_thanh_toan );
}
$users = \backend\modules\user\models\User::getListUserIdProfile();

$user_truongphong = UserSubRole::getListTruongPhong();


$danhMucChi = KhoanChi::getDanhMucChiByKhoanChi( isset( $model->khoan_chi ) ? $model->khoan_chi : null );
if ( $danhMucChi != null ) {
	$model->danh_muc_chi = $danhMucChi->primaryKey;
} else {

}
$listDanhMucChi = ArrayHelper::map( DanhMucChi::getDanhMucChi(), 'id', 'name' );

$nhomChi = NhomChi::getNhomChiByKhoanChi( $model->khoan_chi );
if ( $nhomChi != null ) {
	$model->nhom_chi = $nhomChi->primaryKey;
}

$listNhomChi = [];
if ( $model->danh_muc_chi != null ) {
	$listNhomChi = ArrayHelper::map( NhomChi::getNhomChiByDanhMuc( $model->danh_muc_chi ), 'id', 'name' );
}

$listKhoanChi = [];
if ( $model->nhom_chi != null ) {
	$listKhoanChi = ArrayHelper::map( KhoanChi::getListKhoanChiByNhomChi( $model->nhom_chi ), 'id', 'name' );
}

if ( $model->khoanchiHasOne != null ) {
	$model->khoan_chi = $model->khoanchiHasOne->code;
}

/*
 * có thể edit
 * - action create
 * - quyền trưởng phòng hoặc dev hoac ketoan
 * */
$can_edit_tieu_chi = Yii::$app->controller->action->id == 'create' ||
                     $model->status == DeXuatChi::STATUS_DANG_DOI_DUYET ||
                     UserSubRole::is_current_user_is_truongphong() || UserSubRole::is_current_user_is_ketoan() ||
                     Yii::$app->user->can( User::USER_DEVELOP );

\backend\modules\chi\assets\DexuatchiAssets::register( $this );

?>
    <div class="row">
        <div class="col-sm-<?= $model->primaryKey != null ? 8 : 12 ?>">
            <div class="de-xuat-chi-form">

				<?php $form = ActiveForm::begin( [
					'id'                   => 'form-de-xuat-chi',
					'enableAjaxValidation' => true,
					'validationUrl'        => Url::toRoute( [ 'validate-de-xuat-chi', 'id' => $model->primaryKey ] ),
					'action'               => Url::toRoute( [ 'submit-de-xuat-chi', 'id' => $model->primaryKey ] )
				] ); ?>


                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-6">
							<?php echo $form->field( $model, 'type_dexuat' )->dropDownList( DeXuatChiModel::TYPE_DEXUAT,
								( $can_edit_tieu_chi ? [] : [
									'disabled' => 'disabled'
								] ) )->label( 'Loại đề xuất' ); ?>
                        </div>

                        <div class="col-md-6">
							<?php echo $form->field( $model, 'title' )->textInput( ( $can_edit_tieu_chi ? [] : [
								'readonly' => true
							] ) )->label( 'Tiêu Đề' ); ?>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6">
							<?= $form->field( $model, 'nguoi_trien_khai', [
								'template' => '<div class="input-group"><div class="w-100">{label}</div>{input}' . '</div>{error}'
							] )->dropDownList( ArrayHelper::map( $users, 'id', 'fullname' ), array_merge( [
								'prompt' => $model->getAttributeLabel( 'nguoi_trien_khai' ),
							], ( Yii::$app->controller->action->id == 'create' || $model->status == 0 ? [
								'class' => 'form-control',
							] : [
								'class'    => 'form-control',
								'disabled' => 'disabled'
							] ) ) ); ?>
                        </div>
                        <div class="col-md-6">
							<?= $form->field( $model, 'chosen_one', [
								'template' => '<div class="input-group"><div class="w-100">{label}</div>{input}' .
								              '<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span>' . '</div>{error}'
							] )->dropDownList( ArrayHelper::map( $user_truongphong, 'user_id', 'fullname' ), array_merge( [
								'prompt' => $model->getAttributeLabel( 'chosen_one' ),
							], ( Yii::$app->controller->action->id == 'create' || $model->status == 0 ? [
								'class' => 'form-control ui dropdown search',
							] : [
								'class'    => 'form-control',
								'disabled' => 'disabled'
							] ) ) ); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">

							<?= $form->field( $model, 'so_tien_chi' )->textInput( array_merge( [
								'class' => 'form-control on-keyup',
							], ( ! $can_edit_tieu_chi ? [
								'readonly' => true
							] : [] ) ) ) ?>
                        </div>
						<?php
						if ( ! $can_edit_tieu_chi ) {
							?>
                            <div class="col-md-6">
								<?php
								echo $form->field( $model, 'thoi_han_thanh_toan' )->textInput( [
									'readonly' => true
								] );
								?>
                            </div>
							<?php
						} else {
							?>
                            <div class="col-md-6">

								<?php
								echo $form->field( $model, 'thoi_han_thanh_toan', [
									'template' => '{label}{input}{error}'
								] )->widget( DateTimePicker::class, [
									'template'      => '{input}<span class="input-group-addon1 clear-value"><span class="fa fa-times"></span></span>{addon}',
									'clientOptions' => [
										'format'         => 'dd-mm-yyyy hh:ii',
										'autoclose'      => true,
										'todayHighlight' => true,
										'todayBtn'       => true,
									],
									'clientEvents'  => [
									],
									'options'       => [
										'class' => 'form-control'
									]
								] );
								?>
                            </div>
							<?php
						}
						?>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
							<?php
							/*= $form->field( $model, 'coso', [
								'template' => '<div class="input-group"><div class="w-100">{label}</div>{input}' . '</div>{error}'
							] )->dropDownList( \backend\modules\chi\controllers\DeXuatChiController::getCoSo(), array_merge( [
								'prompt' => $model->getAttributeLabel( 'coso' ),
							], ( Yii::$app->controller->action->id == 'create' || $model->status == 0 ? [
								'class' => 'form-control',
							] : [
								'class'    => 'form-control',
								'disabled' => 'disabled'
							] ) ) ); */
							?>
							<?= $form->field( $model, 'coso', [
								'template' => '<div class="input-group"><div class="w-100">{label}</div>{input}' . '</div>{error}'
							] )->dropDownList( \backend\modules\setting\models\Dep365CoSo::getCoSoArrayBy( 'slug', \backend\modules\setting\models\Dep365CoSo::HEAD_OFFICE ), [ 'prompt'   => $model->getAttributeLabel( 'coso' ),
							                                                                                                                                                    'multiple' => true
							] ); ?>


                        </div>
						<?php if ( UserSubRole::is_current_user_is_ketoan() || UserSubRole::is_current_user_is_truongphong() ): ?>

                            <div class="col-md-6">
								<?= $form->field( $model, 'danh_muc_chi', [
									'template' => '<div class="input-group"><div class="w-100">{label}</div>{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>{error}'
								] )->dropDownList( $listDanhMucChi, [
									'class'  => 'form-control ui dropdown search select-dropdown',
									'prompt' => $model->getAttributeLabel( 'danh_muc_chi' ),
								] ) ?>
								<?= $form->field( $model, 'nhom_chi', [
									'template' => '<div class="input-group"><div class="w-100">{label}</div>{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>{error}'
								] )->dropDownList( $listNhomChi, [
									'class'  => 'form-control ui dropdown search select-dropdown',
									'prompt' => $model->getAttributeLabel( 'nhom_chi' ),
								] ) ?>
								<?= $form->field( $model, 'khoan_chi', [
									'template' => '<div class="input-group"><div class="w-100">{label}</div>{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>{error}'
								] )->dropDownList( $listKhoanChi, [
									'class'  => 'form-control ui dropdown search select-dropdown',
									'prompt' => $model->getAttributeLabel( 'khoan_chi' ),
								] ); ?>
                            </div>
						<?php endif; ?>

                    </div>


                    <div class="row">
                        <div class="col-md-6">
							<?php echo $form->field( $model, 'method_payment' )->dropDownList( $model->payment_method, (
							$can_edit_tieu_chi ? [] : [
								'disabled' => 'disabled',
							] ) )->label( 'Phương Thức Thanh Toán' ); ?>
                        </div>
                    </div>
                    <div class="row chuyen-khoan <?php
					if ( empty( $model->method_payment ) || ! in_array( $model->method_payment, [
							\backend\modules\chi\models\DeXuatChiModel::SCENARIO_CHUYENKHOAN,
							\backend\modules\chi\models\DeXuatChiModel::SCENARIO_CHUYENKHOAN_QUY
						] ) ) {
						echo 'hide';
					} else {
						echo 'show';
					}
					?>">
                        <!--<div class="col-md-6">
                            <?php /*echo $form->field($model, 'method_payment')->textInput(($can_edit_tieu_chi ? [] : [
                                'readonly' => true
                            ]))->label('Phương Thức Thanh Toán'); */ ?>
                        </div>-->

                        <div class="col-md-6">
							<?php echo $form->field( $model, 'receiver' )->textInput( ( $can_edit_tieu_chi ? [] : [
								'readonly' => true
							] ) )->label( 'Người Nhận' ); ?>
                        </div>
                        <div class="col-md-6">
							<?php echo $form->field( $model, 'receiver_phone' )->textInput( ( $can_edit_tieu_chi ? [] : [
								'readonly' => true
							] ) )->label( 'Số điện thoại người nhận' ); ?>
                        </div>

                        <div class="col-md-6">
							<?php echo $form->field( $model, 'owner_credit_name' )->textInput( ( $can_edit_tieu_chi ? [] : [
								'readonly' => true
							] ) )->label( 'Tên chủ thẻ' ); ?>
                        </div>
                        <div class="col-md-6">
							<?php echo $form->field( $model, 'credit_number' )->textInput( array_merge( [ 'type' => 'text' ],
								( $can_edit_tieu_chi ? [] : [
									'readonly' => true
								] ) ) )->label( 'Số Thẻ' ); ?>
                        </div>
                        <div class="col-md-6">
							<?php echo $form->field( $model, 'banking_name' )->textInput( ( $can_edit_tieu_chi ? [] : [
								'readonly' => true
							] ) )->label( 'Tên Ngân Hàng' ); ?>
                        </div>
                    </div>

					<?php echo 'Tiêu Chí Đánh Giá';
					?>
					<?= $form->field( $model, 'tieu_chi_group', [
						'template' => '{input}'
					] )->widget( MultipleInput::class, [
						'min'              => 1, // should be at least 2 rows
						'rendererClass'    => \unclead\multipleinput\renderers\ListRenderer::class,
						'prepend'          => false,
						'layoutConfig'     => [
							'offsetClass'       => 'col-md-offset-2',
							'labelClass'        => 'col-lg-2 col-md-4',
							'wrapperClass'      => 'col-md-8',
							'errorClass'        => '',
							'buttonActionClass' => 'col-md-offset-1 col-md-2',
						],
						'showGeneralError' => true,
						'allowEmptyList'   => false,
						'enableGuessTitle' => true,
						'cloneButton'      => false,
						'rowOptions'       => function () use ( $can_edit_tieu_chi ) {
							if ( ! $can_edit_tieu_chi ) {
								return [
									'class' => 'disabled',
								];
							}

							return [];
						},
						'columns'          => [
							[
								'name'        => 'tieu_chi',
								'title'       => 'Tiêu Chí',
								'enableError' => true,
								'options'     => function ( $data ) use ( $can_edit_tieu_chi ) {
									if ( ! $can_edit_tieu_chi ) {
										return [
											'readonly' => true,
										];
									}
									$options = [];
									if ( ! isset( $data['status'] ) ) {
										$data['status'] = '';
									}
									switch ( $data['status'] ) {
										case '':
											$options['class'] = 'null';
											break;
										case 0:
											$options['class'] = 'false';
											break;
										case 1:
											$options['class'] = 'true';
											break;
									}

									return $options;
								}
							],
							[
								'name'    => 'thoi_gian_bat_dau',
								'title'   => 'Thời gian bắt đầu',
								'value'   => function ( $data ) {
									if ( ! empty( $data['thoi_gian_bat_dau'] ) ) {
										return Yii::$app->formatter->asDatetime( $data['thoi_gian_bat_dau'] );
									} else {
										return null;
									}
								},
								'options' => function ( $data ) use ( $can_edit_tieu_chi ) {
									if ( ! $can_edit_tieu_chi ) {
										return [
											'readonly' => true,
										];
									}
									$options = [];
									if ( ! isset( $data['status'] ) ) {
										$data['status'] = '';
									}
									switch ( $data['status'] ) {
										case '':
											$options['class'] = 'thoigian_tieuchi null';
											break;
										case 0:
											$options['class'] = 'thoigian_tieuchi false';
											break;
										case 1:
											$options['class'] = 'thoigian_tieuchi true';
											break;
									}

									return $options;
								}
							],
							[
								'name'    => 'thoi_gian_ket_thuc',
								'title'   => 'Thời gian kết thúc',
								'value'   => function ( $model ) {
									if ( ! empty( $model['thoi_gian_ket_thuc'] ) ) {
										return Yii::$app->formatter->asDatetime( $model['thoi_gian_ket_thuc'] );
									} else {
										return null;
									}
								},
								'options' => function ( $data ) use ( $can_edit_tieu_chi ) {
									if ( ! $can_edit_tieu_chi ) {
										return [
											'readonly' => true
										];
									}
									$options = [];
									if ( ! isset( $data['status'] ) ) {
										$data['status'] = '';
									}
									switch ( $data['status'] ) {
										case '':
											$options['class'] = 'thoigian_tieuchi null';
											break;
										case 0:
											$options['class'] = 'thoigian_tieuchi false';
											break;
										case 1:
											$options['class'] = 'thoigian_tieuchi true';
											break;
									}

									return $options;
								}
							],
							//TODO:: làm thành three state. Default là 0. Lock là null. Accept là 1. Kế thừa CheckboxX
							[
								'name'    => 'status',
								'type'    => 'checkbox',
								'title'   => 'status',
								'value'   => function ( $model ) {
									return isset( $model['status'] ) ? $model['status'] : '';
								},
								'options' => function () {
									if ( ! UserSubRole::is_current_user_is_truongphong() || ! Yii::$app->user->can( User::USER_DEVELOP ) ) {
										return [
											'disabled' => true,
										];
									}

									return [];
								}
							],
							[
								'name'    => 'nd_hoan_thanh',
								'title'   => 'Nội dung hoàn thành',
								'type'    => 'textarea',
								'options' => function ( $data ) use ( $can_edit_tieu_chi ) {
									if ( $data == null ) {
										return [];
									}
									if ( ( ! $can_edit_tieu_chi &&
									       $data['status'] != DeXuatChi::STATUS_DANG_DOI_DUYET )
									     || UserSubRole::is_current_user_is_ketoan()
									) {
										return [
											'readonly' => true,
											'rows'     => 5,
											'style'    => 'resize: none'
										];
									}
									$options         = [];
									$options['rows'] = '6';
									if ( ! isset( $data['status'] ) ) {
										$data['status'] = '';
									}
									switch ( $data['status'] ) {
										case 1:
											$options['class'] = 'true';
											break;
										default:
											$options['class'] = 'false';
											if ( UserSubRole::is_current_user_is_ketoan() ) {
												$options['class'] = 'true';
											}
											break;
									}

									return $options;
								}
							],
							[
								'name'         => 'id_de_xuat_chi',
								'defaultValue' => $model->id,
								'options'      => [
									'class' => 'hidden'
								],
							],
							[
								'name'    => 'id',
								'options' => [ 'class' => 'hidden' ],
							]
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
					] )->label( 'Tiêu Chí Đánh Giá' );
					?>

                </div>
				<?php
				if ( ( UserSubRole::is_current_user_is_truongphong() || UserSubRole::is_current_user_is_ketoan() || $model->inspectioner == Yii::$app->user->id ) && $model->status == 3 ) {

					?>
                    <div class="cc-block cc-single">
                        <div class="ccb-header">
                            <div class="ccbh-title"><?php echo 'Trạng thái nghiệm thu'; ?>
                            </div>
                        </div>
                        <div class="ccb-content">
							<?php

							if ( ( UserSubRole::is_current_user_is_truongphong() && $model->chosen_one == Yii::$app->user->id ) || $model->inspectioner == Yii::$app->user->id ) {

								echo $form->field( $model, 'tp_status' )->radioList(
									[
										0 => DeXuatChi::TP_STATUS[0],
										1 => DeXuatChi::TP_STATUS[1],
										2 => DeXuatChi::TP_STATUS[2]
									]
								);
							} else {
								echo DeXuatChi::TP_STATUS[ $model->tp_status ];
							}


							?>
                        </div>
                    </div>
					<?php

				} ?>
                <div class="d-none">
                    <div class="btn-upload btn-upload-single tmp">
                        <div class="g-file">
							<?= $form->field( $formHoSo, 'files', [
								'template' => '{label}{input}{error}<span class="review"><img src=""></span><span class="upload"><i class="fa fa-upload"></i></span>'
							] )->fileInput( [] ) ?>
                            <div class="tmp">
								<?= $form->field( $formHoSo, 'i' )->textInput( [] ) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="cc-block cc-single">
                    <div class="ccb-header">
                        <div class="ccbh-title">Hồ sơ liên quan</div>
                    </div>
                    <div class="ccb-content">
                        <div class="g-list-file">
							<?php
							if ( is_array( $listHoSo ) ) {
								foreach ( $listHoSo as $hoSo ) {
									?>
                                    <div class="g-file">
                                        <div class="form-group has-image">
                                        <span class="review">
                                            <?php
                                            $explode = explode( '.', $hoSo->file );

                                            if ( in_array( $explode[ count( $explode ) - 1 ], [
	                                            'jpg',
	                                            'jpeg',
	                                            'png'
                                            ] ) ):
	                                            ?>
                                                <a class="grouped_images_view" rel="group1"
                                                   href="<?= '/uploads/ho-so/' . $hoSo->file ?>"><img class="hoso-image"
                                                                                                      src="<?= '/uploads/ho-so/' . $hoSo->file ?>"></a>
                                            <?php else: ?>
                                                <a target="_blank" href="<?= '/uploads/ho-so/' . $hoSo->file ?>">
                                                    <img class="hoso-file"
                                                         src="<?= \backend\modules\chi\models\form\FormHoSo::getUrlIconByExt( $explode[ count( $explode ) - 1 ] ) ?>">
                                                    <?= $hoSo->file ?>
                                                </a>
                                            <?php endif; ?>
                                        </span>
                                        </div>
										<?php if ( ( Yii::$app->user->id == $hoSo->created_by && $model->status == DeXuatChi::STATUS_DANG_DOI_DUYET ) || UserSubRole::is_current_user_is_ketoan() ) { ?>
                                            <span class="delete-file" data-hoso="<?= $hoSo->primaryKey ?>">
                                                    <i class="fa fa-times"></i>
                                                </span>
										<?php } ?>
                                    </div>
									<?php
								}
							}
							?>
                            <div class="g-btn-upload">
                                <div class="btn-upload-tmp"></div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions">
				<?= Html::resetButton( '<i class="ft-x"></i> Cancel', [
					'class' =>
						'btn btn-warning mr-1'
				] ) ?>
				<?= Html::submitButton(
					'<i class="fa fa-check-square-o"></i> Save',
					[ 'class' => 'btn btn-primary' ]
				) ?>
                <div class="footer-right">
					<?php
					if ( UserSubRole::is_current_user_is_ketoan() && $model->status == 3 && $model->tp_status != 0 ) {
						?>
                        <a class="approve btn btn-success"
                           href="<?php echo Url::toRoute( [ 'status-change' ] ) ?>"
                           data-id="<?php echo $model->id ?>"
                           data-status="<?php echo 'success'; ?>">Hoàn Thành</a>
						<?php
					}
					?>
					<?php
					if ( $model->primaryKey != null ) :
						if ( $model->status == 0 || ( UserSubRole::is_current_user_is_ketoan() && $model->status < 4 && $model->tp_status != 0 ) ) :
							?> <a class="approve btn btn-danger"
                                  href="<?php echo Url::toRoute( [ 'status-change' ] ) ?>"
                                  data-id="<?php echo $model->id ?>"
                                  data-status="<?php echo 'deny'; ?>">Hủy Đề Xuất</a>
						<?php
						endif;
					endif;
					?>

                </div>
            </div>

			<?php ActiveForm::end(); ?>
        </div>
		<?php if ( $model->primaryKey != null ) { ?>
            <div class="col-sm-4">
				<?= ChatWidget::widget( [
					'listComment'  => $listComment,
					'model'        => $model,
					'formComment'  => $formComment,
					'commentTable' => Comment::COMMENT_TABLE_DE_XUAT_CHI
				] ) ?>
            </div>
		<?php } ?>

    </div>
<?php
$alertSuccess         = Yii::t( 'backend', ( $model->primaryKey == null ? 'Tạo mới' : 'Cập nhật' ) . ' thành công' );
$urlLoadNhomChi       = Url::toRoute( [ '/chi/nhom-chi/load-nhom-chi-by-danh-muc' ] );
$urlLoadKhoanChi      = Url::toRoute( [ '/chi/khoan-chi/load-khoan-chi-by-nhom-chi' ] );
$urlDeleteFile        = Url::toRoute( [ '/chi/ho-so/delete' ] );
$urlDeleteTieuChi     = Url::toRoute( [ '/chi/de-xuat-chi/delete-tieu-chi' ] );
$urlListIdProfileUser = Url::toRoute( [ '/chi/de-xuat-chi/list-user-id-profile' ] );

$id_nguoi_trien_khai = $model->getAttribute( 'nguoi_trien_khai' );
$id_de_xuat          = $model->primaryKey;

$this->registerCssFile( '/vendors/plugins/fancybox/dist/jquery.fancybox.css',
	[ 'depends' => [ \yii\web\JqueryAsset::class ] ] );
$this->registerJsFile( '/vendors/plugins/fancybox/dist/jquery.fancybox.js',
	[ 'depends' => [ \yii\web\JqueryAsset::class ] ] );
$urlReloadPjax = Url::toRoute( [ 'view', 'id' => $model->id ] );


$tit = Yii::t( 'backend', 'Notification' );

$resultSuccess = Yii::$app->params['update-success'];
$resultDanger  = Yii::$app->params['update-danger'];

$deleteSuccess = Yii::$app->params['delete-success'];
$deleteDanger  = Yii::$app->params['delete-danger'];

$data_title = Yii::t( 'backend', 'Are you sure?' );
$data_text  = Yii::t( 'backend', '' );
$script     = <<< JS
var alertSuccess = '$alertSuccess'
var urlLoadNhomChi = '$urlLoadNhomChi'
var urlLoadKhoanChi = '$urlLoadKhoanChi'
var urlDeleteFile ='$urlDeleteFile'
var urlDeleteTieuChi='$urlDeleteTieuChi'
var urlListIdProfileUser='$urlListIdProfileUser'
var id_de_xuat = '$id_de_xuat'
var id_nguoi_trien_khai = '$id_nguoi_trien_khai'
var tit = '$tit'

var resultSuccess ='$resultSuccess' 
var resultDanger='$resultDanger' 

var deleteSuccess= '$deleteSuccess' 
var deleteDanger='$deleteDanger'

var data_title= '$data_title' 
var data_text = '$data_text'

var urlReloadPjax = '$urlReloadPjax';
JS;

$this->registerJs( $script, View::POS_HEAD );


