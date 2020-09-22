<?php

use yii\helpers\Url;
use yii\helpers\Html;
use common\grid\MyGridView;
use yii\grid\GridView;
use yii\widgets\Pjax;
use backend\modules\chi\models\DeXuatChi;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\chi\models\search\DeXuatChiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = Yii::t( 'backend', Yii::t( 'backend', 'De Xuat Chi' ) );
$this->params['breadcrumbs'][] = $this->title;
\backend\modules\chi\assets\DexuatchiAssets::register( $this );


//$user_test = \backend\modules\user\models\User::getUserOne( 224 )->phongBanHasMany;
$user_test = \backend\modules\user\models\User::getUserOne( 224 );

?>
<section id="dom">
    <div class="row">
        <div class="col-12">
			<?php
			if ( Yii::$app->session->hasFlash( 'alert' ) ) {
				?>
                <div class="alert <?= Yii::$app->session->getFlash( 'alert' )['class']; ?> alert-dismissible"
                     role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
					<?= Yii::$app->session->getFlash( 'alert' )['body']; ?>
                </div>
				<?php
			}
			?>
            <div class="card">
                <div class="card-content collapse show">
                    <div class="card-body card-dashboard">
                        <div class="btn-add-campaign clearfix" style="margin-top:0px;position:relative">
							<?= Html::a( '<i class="fa fa-plus"> Thêm mới</i>', [ 'create' ],
								[ 'title' => 'Thêm mới', 'data-pjax' => 0, 'class' => 'btn btn-default pull-left' ] ) ?>
                        </div>
						<?php // echo $this->render('_search', ['model' => $searchModel]);?>
						<?php Pjax::begin( [
							'id'              => 'custom-pjax',
							'timeout'         => false,
							'enablePushState' => true,
							'clientOptions'   => [ 'method' => 'GET' ]
						] ); ?>
                        <div style="margin-top:5px;border:1px solid #ccc;border-radius:3px">
							<?= MyGridView::widget( [
								'dataProvider'   => $dataProvider,
								'filterModel'    => $searchModel,
								'layout'         => '{errors} <div class="pane-single-table">{items}</div><div class="pager-wrap clearfix">{summary}' .
								                    Yii::$app->controller->renderPartial( '@backend/views/layouts/my-gridview/_goToPage',
									                    [
										                    'totalPage'   => $totalPage,
										                    'currentPage' => Yii::$app->request->get( $dataProvider->getPagination()->pageParam )
									                    ] ) .
								                    Yii::$app->controller->renderPartial( '@backend/views/layouts/my-gridview/_pageSize' ) .
								                    '{pager}</div>',
								'tableOptions'   => [
									'id'    => 'listCampaign',
									'class' => 'cp-grid cp-widget pane-hScroll',
								],
								'myOptions'      => [
									'class'      => 'grid-content my-content pane-vScroll',
									'data-minus' => '{"0":42,"1":".header-navbar","2":".btn-add-campaign","3":".pager-wrap","4":".footer"}'
								],
								'summaryOptions' => [
									'class' => 'summary pull-right',
								],
								'pager'          => [
									'firstPageLabel' => Html::tag( 'span', 'skip_previous',
										[ 'class' => 'material-icons' ] ),
									'lastPageLabel'  => Html::tag( 'span', 'skip_next', [ 'class' => 'material-icons' ] ),
									'prevPageLabel'  => Html::tag( 'span', 'play_arrow', [ 'class' => 'material-icons' ] ),
									'nextPageLabel'  => Html::tag( 'span', 'play_arrow', [ 'class' => 'material-icons' ] ),
									'maxButtonCount' => 5,

									'options'              => [
										'tag'   => 'ul',
										'class' => 'pagination pull-left',
									],

									// Customzing CSS class for pager link
									'linkOptions'          => [ 'class' => 'page-link' ],
									'activePageCssClass'   => 'active',
									'disabledPageCssClass' => 'disabled page-disabled',
									'pageCssClass'         => 'page-item',

									// Customzing CSS class for navigating link
									'prevPageCssClass'     => 'page-item prev',
									'nextPageCssClass'     => 'page-item next',
									'firstPageCssClass'    => 'page-item first',
									'lastPageCssClass'     => 'page-item last',
								],
								'rowOptions'     => function ( $model, $index, $widget, $grid ) {
									$return          = [];
									$return['class'] = '';
									switch ( $model->status ) {
										case 1:
											$return['class'] = 'table-success';
											break;
										case 2:

											break;
										case 3:
											$return['class'] = 'table-info';
											break;
										case 4:
											$return['class'] = 'table-danger';
											break;
										case 5:

											break;
										case 6:
											$return['class'] = 'table-dark';
											break;
									}
									$return['class'] .= ' table-key';

									return $return;
								},
//                                'filterModel' => $searchModel,
								'columns'        => [
//                                    'id',
									[
										'class'         => 'yii\grid\ActionColumn',
										'header'        => 'Actions',
										'template'      => '<div class="btn-group" role="group">{print-phieuthu} {view} {update} {delete} {print-dexuat}</div>',
										'buttons'       => [

											'view'   => function ( $url, $model ) {
												return Html::a(
													'<i class="ft-eye green"></i>',
													\yii\helpers\Url::toRoute( [
														'/chi/de-xuat-chi/view',
														'id' => $model->id
													] ),
													[
														'class' => 'btn btn-default',
													]
												);
											},
											'update' => function ( $url, $model ) {
												if ( $model->status == 6 || $model->status == 4 ) {
													return '';
												} else {
													return Html::a( '<i class="ft-edit blue"></i>', $url,
														[ 'data-pjax' => 0, 'class' => 'btn btn-default' ] );
												}
											},
											'delete' => function ( $url, $model ) {
												if ( Yii::$app->user->can( \common\models\User::USER_DEVELOP ) ) {
													return Html::a( '<i class="ft-trash-2 red confirm-color" data-id = "' . json_encode( [ $model->id ] ) . '" ></i>',
														'javascript:void(0)', [ 'class' => 'btn btn-default' ] );
												} else {
													return '';
												}
											},

											'print-dexuat'   => function ( $url, $model ) {
//                                                if (\backend\modules\user\models\UserSubRole::is_current_user_is_ketoan() || \backend\modules\user\models\UserSubRole::is_current_user_is_truongphong()) {
												return Html::a( '<i class="p-icon ft-printer blue"></i>',
													'javascript:void(0)', [
														'title'     => 'In đơn hàng',
														'class'     => 'btn btn-default print-dexuat',
														'data-id'   => $model->primaryKey,
														'data-href' => Url::toRoute( [
															'print-dexuat',
															'id'      => $model->primaryKey,
															'type'    => 'dexuat',
															'noprint' => 'yes'
														] ),
														'data-type' => 'dexuat'
													] );
//                                                } else {
//                                                    return '';
//                                                }

											},
											'print-phieuthu' => function ( $url, $model ) {
												if ( \backend\modules\user\models\UserSubRole::is_current_user_is_ketoan() || \backend\modules\user\models\UserSubRole::is_current_user_is_truongphong() ) {
													/*return Html::a('<i class="p-icon ft-printer red"></i>', 'javascript:void(0)', [
														'title' => 'In đơn hàng',
														'class' => 'btn btn-default print-phieuthu',
														'data-id' => $model->primaryKey,
														'data-href' => Url::toRoute(['print-phieuthu', 'id' => $model->primaryKey, 'type' => 'phieuthu']),
														'data-type' => 'phieuthu'
													]);*/
													return '';
												} else {
													return '';
												}
											},
										],
										'headerOptions' => [
											'width'   => 170,
											'rowspan' => 2
										],
										'filterOptions' => [
											'class' => 'd-none'
										],
									],
									[
										'attribute' => 'title',
										'format'    => 'raw',
										'value'     => function ( $model ) {
											$coso    = $model->cosoquery;
											$cosostr = '';
											if ( isset( $model->coso ) ) {
												if ( is_array( $model->coso ) ) {
													foreach ( $model->coso as $val ) {
														$cosostr .= '[' . $coso[ $val ] . ']';
													}
												} else {
													$cosostr = isset( $model->coso ) ? '[' . $coso[ $model->coso ] . ']' : '';
												}
											}

											switch ( $model->tp_status ) {
												case 1:
													$str = $cosostr . '<div class="col-id" data-toggle="tooltip" title="' . \backend\modules\chi\models\DeXuatChiModel::TP_STATUS[1] . '">' . $model->title . '<span style=\'font-size:21px;\'>&#9989;</span></div>';
													break;
												case 2:
													$str = $cosostr . '<div class="col-id" data-toggle="tooltip" title="' . \backend\modules\chi\models\DeXuatChiModel::TP_STATUS[2] . '">' . $model->title . '<span style=\'font-size:21px;\'>&#10060;</span></div>';
													break;
												default:
													$str = $cosostr . ' ' . $model->title;
											}

											return $str;
										}
									],
									[
										'attribute'     => 'created_by',
										'format'        => 'raw',
										'label'         => 'Người Đề Xuất',
										'value'         => function ( $model ) {

											if ( $model->nguoidexuatHasOne == null ) {
												return '--';
											}

											return $model->nguoidexuatHasOne->fullname;
										},
										'filterOptions' => [ 'class' => 'filter_select' ],
										'filter'        => \backend\modules\chi\models\DeXuatChiModel::getListUserCreatedBy(),
									],
									[
										'attribute'     => 'nguoi_trien_khai',
										'format'        => 'raw',
										'value'         => function ( $model ) {
											if ( $model->nguoitrienkhaiHasOne == null ) {
												return '--';
											}

											return $model->nguoitrienkhaiHasOne->fullname;
										},
										'filterOptions' => [ 'class' => 'filter_select', 'data-test' => 'testset' ],
										'filter'        => \backend\modules\chi\models\DeXuatChiModel::getListNguoiTrienKhai(),
									],
									[
										'class'         => 'backend\modules\chi\filter\AppendFilterColumn',
										'attribute'     => 'so_tien_chi',
										'format'        => 'html',
										'value'         => function ( $model ) {
											if ( $model->so_tien_chi == null || ! is_numeric( $model->so_tien_chi ) ) {
												return null;
											}

											return number_format( $model->so_tien_chi, 0 );
										},
										'filterOptions' => [
											'id'       => 'slider-money',
											'class'    => 'bootstrap-slider-money',
											'data-max' => \backend\modules\chi\models\DeXuatChiModel::getMaxMinAmountOfMoney( 'max' ),
											'data-min' => \backend\modules\chi\models\DeXuatChiModel::getMaxMinAmountOfMoney( 'min' ),
											'rawhtml'  => '<div class="popup-slider hide">
                                                                <div class="submit-range">
                                                                <a href="#" class="btn btn-success success-range">OK</a><a href="#" class="btn btn-danger cancel-range">Cancel</a>
                                                                </div>
                                                                <div class="span2 range-slider">
                                                                </div>
                                                            </div>'
										],
									],
									[
										'attribute'     => 'khoan_chi',
										'format'        => 'raw',
										'value'         => function ( $model ) {

											if ( $model->khoanchiHasOne == null ) {
												if ( \backend\modules\user\models\UserSubRole::is_current_user_is_ketoan() ) {
													return '<select class="select_khoanchi"></select>';
												}

												return '--';
											} else {
												if ( \backend\modules\user\models\UserSubRole::is_current_user_is_ketoan() ) {
													return '<select class="select_khoanchi"><option value="' . $model->khoanchiHasOne->id . '" selected="selected">' . $model->khoanchiHasOne->name . '</option></select>';
												}
											}

											return $model->khoanchiHasOne->name;
										},
										'filter'        => \yii\helpers\ArrayHelper::getColumn( \backend\modules\chi\models\KhoanChi::getListKhoanChi(), [ 'id' => 'name' ] ),
										'filterOptions' => [ 'class' => 'filter_select' ],

									],

//									[
//										'attribute' => 'thoi_han_thanh_toan',
//										'format'    => [ 'datetime', 'php:d-m-Y' ]
//									],

									[
										'attribute' => 'leader_accept',
										'format'    => 'raw',
										'value'     => function ( $model ) {
//                                            $curr_user = \common\models\User::find()->where(['id' => Yii::$app->user->id])->one();
											$curr_user = \common\models\User::find()->where( [ 'id' => Yii::$app->user->id ] )->one();

											if ( $curr_user->subroleHasOne == null ) {
												if ( $model->leaderHasOne == null ) {
													return '--';
												} else {
													return $model->leaderHasOne->userProfile->fullname;
												}
											} else {
												if ( \backend\modules\user\models\UserSubRole::is_current_user_is_truongphong() && Yii::$app->user->id == $model->chosen_one && $model->status < 2 ) {
													$result['type']          = 'select';
													$result['title']         = 'Người Nghiệm Thu';
													$result['dataChoose']    = '';
													$result['dataSelect']    = array_map( function ( $arr ) {
														if ( count( $arr->phongbanHasMany ) > 0 ) {
															$name = $arr->phongbanHasMany[0]->name;
															$name = \backend\helpers\BackendHelpers::acronysm_string( $name );
															$name = '[' . $name . '] ';
														} else {
															$name = '';
														}

														return [ $arr->id => $name . $arr->fullname ];
													}, \backend\modules\user\models\User::getListUserIdProfile() );
													$result['dataSelect']    = \backend\helpers\BackendHelpers::flattenArrayWithKeyAndValue( $result['dataSelect'] );
													$result['dataSelect'][0] = 'Chính tôi';
													if ( $model->status == 1 ) {
														$value = 1;
														if ( Yii::$app->user->id == $model->leader_accept ) {
															return Html::tag( 'div',
																Html::checkbox(
																	'customCheck',
																	$value,
																	[
																		'id'    => 'check-toggle-' . $model->id,
																		'value' => $model->id,
																		'class' => "custom-control-input check-toggle-unacceptable",
																	]
																) . '<label class="custom-control-label" for="' . 'check-toggle-' . $model->id . '"></label>',
																[
																	'class' => 'custom-control custom-checkbox'
																]
															);
														} else {
															if ( $model->leaderHasOne == null ) {
																return null;
															}
															if ( $model->leaderHasOne->userProfile == null ) {
																return $model->leaderHasOne->username;
															}

															return $model->leaderHasOne->userProfile->fullname;
														}
													} else {
														$value = 0;

														return Html::tag(
															'div',
															Html::checkbox( 'customCheck', $value, [
																'id'    => 'check-toggle-' . $model->id,
																'value' => $model->id,
																'class' => "custom-control-input"
															] ) . '<label class="custom-control-label" for="' . 'check-toggle-' . $model->id . '"></label>',
															[
																'class'          => 'edittable-accept custom-control custom-checkbox',
																'data-option'    => "direct",
																'myedit-options' => json_encode( $result )
															]
														);
													}
												}
												if ( $model->leaderHasOne == null ) {
													return '--';
												}
												if ( $model->leaderHasOne->userProfile == null ) {
													return $model->leaderHasOne->username;
												}

												return $model->leaderHasOne->userProfile->fullname;
											}
										},
										'filter'    => \backend\modules\chi\models\DeXuatChiModel::getListLeaderAccept(),
//
									],
									[
										'attribute' => 'accountant_accept',
										'format'    => 'raw',
										'value'     => function ( $model ) {
											$curr_user = \common\models\User::find()->where( [ 'id' => Yii::$app->user->id ] )->one();
											if ( $curr_user->subroleHasOne == null ) {
												if ( $model->accountantHasOne == null ) {
													return '--';
												} else {
													return $model->accountantHasOne->userProfile->fullname;
												}
											} else {
												if ( \backend\modules\user\models\UserSubRole::is_current_user_is_ketoan() && ( $model->status == 1 || $model->status == 3 ) ) {
													if ( $model->status == 1 ) {
														$value = 0;
													} else {
														$value = 1;
														if ( Yii::$app->user->id == $model->accountant_accept && $model->tp_status == 0 ) {
															return Html::tag(
																'div',
																Html::checkbox(
																	'customCheck',
																	$value,
																	[
																		'id'    => 'check-toggle-' . $model->id,
																		'value' => $model->id,
																		'class' => "custom-control-input check-toggle"
																	]
																) . '<label class="custom-control-label" for="' . 'check-toggle-' . $model->id . '"></label>',
																[
																	'class' => 'custom-control custom-checkbox'
																]
															);
														} else {
															if ( $model->accountantHasOne == null ) {
																return null;
															}
															if ( $model->accountantHasOne->userProfile == null ) {
																return $model->accountantHasOne->username;
															}

															return $model->accountantHasOne->userProfile->fullname;
														}
													}

													return Html::tag(
														'div',
														Html::checkbox(
															'customCheck',
															$value,
															[
																'id'    => 'check-toggle-' . $model->id,
																'value' => $model->id,
																'class' => "custom-control-input check-toggle"
															]
														) . '<label class="custom-control-label" for="' . 'check-toggle-' . $model->id . '"></label>',
														[
															'class' => 'custom-control custom-checkbox'
														]
													);
												}

												if ( $model->accountantHasOne == null ) {
													return null;
												}
												if ( $model->accountantHasOne->userProfile == null ) {
													return $model->accountantHasOne->username;
												}

												return $model->accountantHasOne->userProfile->fullname;
											}
										},
										'filter'    => \backend\modules\chi\models\DeXuatChiModel::getListAccountAccept(),

									],
									[
										'attribute'     => 'inspectioner',
										'format'        => 'raw',
										'value'         => function ( $model ) {

											if ( $model->inspectionerHasOne == null ) {
												if ( \backend\modules\user\models\UserSubRole::is_current_user_is_truongphong() && in_array( $model->status, [
														1,
														3
													] ) ) {
													return '<select class="select_inspectioner"></select>';
												}

												return '--';
											} else {
												if ( \backend\modules\user\models\UserSubRole::is_current_user_is_truongphong() && in_array( $model->status, [
														1,
														3
													] ) ) {
													return '<select class="select_inspectioner"><option value="' . $model->inspectioner . '" selected="selected">' . $model->inspectionerHasOne->userProfile->fullname . '</option></select>';
												}
											}

											return $model->inspectionerHasOne->userProfile->fullname;
										},
										'filter'        => \backend\modules\chi\models\DeXuatChiModel::getListInspectionerDexuatchi(),
										'filterOptions' => [ 'class' => 'filter_select' ],
									],
									[
										'attribute' => 'status',
										'format'    => 'raw',
										'value'     => function ( $model ) {
											if ( ! array_key_exists( $model->status, DeXuatChi::STATUS ) ) {
												return null;
											}

											return DeXuatChi::STATUS[ $model->status ];
										},
										'filter'    => \backend\modules\chi\models\DeXuatChiModel::STATUS
									],
								],
							] ); ?>
                        </div>
						<?php Pjax::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php

$urlLeaderAccept       = Url::toRoute( [ 'leader-accept' ] );
$urlDelete             = Url::toRoute( [ 'delete' ] );
$urlChangePageSize     = Url::toRoute( [ 'perpage' ] );
$urlListKhoanChi       = Url::toRoute( [ 'khoan-chi/get-list-khoan-chi' ] );
$urlUpdateKhoanChi     = Url::toRoute( [ 'de-xuat-chi/update-khoan-chi' ] );
$urlListInspectioner   = Url::toRoute( [ 'de-xuat-chi/get-inspectioner-list' ] );
$urlUpdateInspectioner = Url::toRoute( [ 'de-xuat-chi/update-inpsectioner' ] );


$tit = Yii::t( 'backend', 'Notification' );

$resultSuccess = Yii::$app->params['update-success'];
$resultDanger  = Yii::$app->params['update-danger'];

$deleteSuccess = Yii::$app->params['delete-success'];
$deleteDanger  = Yii::$app->params['delete-danger'];

$data_title = Yii::t( 'backend', 'Are you sure?' );
$data_text  = Yii::t( 'backend', '' );


$script = <<< JS

var urlLeaderAccept = '$urlLeaderAccept'
var urlDelete = '$urlDelete';
var urlChangePageSize = '$urlChangePageSize';
var urlListKhoanChi = '$urlListKhoanChi';
var urlUpdateKhoanChi = '$urlUpdateKhoanChi';
var urlListInspectioner = '$urlListInspectioner';
var urlUpdateInspectioner= '$urlUpdateInspectioner';

var tit = '$tit'

var resultSuccess ='$resultSuccess' 
var resultDanger='$resultDanger' 

var deleteSuccess= '$deleteSuccess' 
var deleteDanger='$deleteDanger'

var data_title= '$data_title' 
var data_text = '$data_text'


JS;

$this->registerJs( $script, \yii\web\View::POS_HEAD );
?>

