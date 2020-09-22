<?php

use yii\helpers\Html;
use common\grid\MyGridView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\baocao\models\search\BaocaoChayAdsAdswordsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = Yii::t( 'backend', 'Baocao Chay Adwords' );
$this->params['breadcrumbs'][] = $this->title;


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
							<?= Html::button(
								'<i class="fa fa-plus"></i> Thêm mới',
								[
									'title'         => 'Thêm mới',
									'class'         => 'btn btn-default pull-left',
									'data-pjax'     => 0,
									'data-toggle'   => 'modal',
									'data-backdrop' => 'static',
									'data-keyboard' => false,
									'data-target'   => '#custom-modal',
									'onclick'       => 'showModal($(this), "' . \yii\helpers\Url::toRoute( [ '/baocao/baocao-chay-ads-adswords/create' ] ) . '");return false;',
								]
							)
							?>
                        </div>
						<?php // echo $this->render('_search', ['model' => $searchModel]);?>
                        <div style="margin-top:5px;border:1px solid #ccc;border-radius:3px">
							<?php \yii\widgets\Pjax::begin(
								[
									'id'              => 'chay-ads-adswords-pjax',
									'timeout'         => false,
									'enablePushState' => false,
									'clientOptions'   => [ 'method' => 'GET' ]
								]
							); ?>
							<?= MyGridView::widget( [
								'dataProvider'   => $dataProvider,
								'filterModel'    => $searchModel,
								'layout'         => '{errors} <div class="pane-single-table">{items}</div><div class="pager-wrap clearfix">{summary}' .
								                    Yii::$app->controller->renderPartial(
									                    '@backend/views/layouts/my-gridview/_goToPage',
									                    [
										                    'totalPage'   => $totalPage,
										                    'currentPage' => Yii::$app->request->get( $dataProvider->getPagination()->pageParam )
									                    ]
								                    ) .
								                    Yii::$app->controller->renderPartial( '@backend/views/layouts/my-gridview/_pageSize' ) .
								                    '{pager}</div>',
								'tableOptions'   => [
									'id'    => 'listCampaign',
									'class' => 'cp-grid cp-widget pane-hScroll',
								],
								'myOptions'      => [
									'class'      => 'grid-content my-content pane-vScroll',
									'data-minus' => '{"0":42,"1":".header-navbar","2":".btn-add-campaign","3":".form-search","4":".pager-wrap","5":".grid-footer","6":".footer"}'
								],
								'summaryOptions' => [
									'class' => 'summary pull-right',
								],
								'pager'          => [
									'firstPageLabel' => Html::tag(
										'span',
										'skip_previous',
										[ 'class' => 'material-icons' ]
									),
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
								'columns'        => [
									[
										'class'         => 'yii\grid\SerialColumn',
										'header'        => 'STT',
										'headerOptions' => [
											'width'   => 60,
											'rowspan' => 2
										],
										'filterOptions' => [
											'class' => 'd-none',
										],
									],
									[
										'class'         => 'yii\grid\ActionColumn',
										'header'        => 'Actions',
										'template'      => '<div class="btn-group" role="group">{update} {delete}</div>',
										'buttons'       => [

											'update' => function ( $url, $model ) {
												return Html::button(
													'<i class="ft-edit blue"></i>',
													[
														'class'         => 'btn btn-default',
														'data-pjax'     => 0,
														'data-toggle'   => 'modal',
														'data-backdrop' => 'static',
														'data-keyboard' => false,
														'data-target'   => '#custom-modal',
														'onclick'       => 'showModal($(this), "' . \yii\helpers\Url::toRoute( [
																'/baocao/baocao-chay-ads-adswords/update',
																'id' => $model->id
															] ) . '");return false;',
													]
												);
											},
											'delete' => function ( $url, $model ) {
												return Html::a(
													'<i class="ft-trash-2 red confirm-color" data-id = "' . $model->id . '" ></i>',
													'javascript:void(0)',
													[ 'class' => 'btn btn-default' ]
												);
											},
										],
										'headerOptions' => [
											'width'   => 100,
											'rowspan' => 2
										],
										'filterOptions' => [
											'class' => 'd-none'
										],
									],
									//ngay tao
									[
										'attribute'     => 'ngay_tao',
										'format'        => 'date',
										'value'         => 'ngay_tao',
										'filter'        => \dosamigos\datepicker\DatePicker::widget( [
											'model'         => $searchModel,
											'attribute'     => 'ngay_tao',
											'template'      => '{input}{addon}',
											'addon'         => '<i class="fa fa-calendar"></i>',
											'language'      => 'vi',
											'clientOptions' => [
												'autoclose' => true,
												'format'    => 'dd-mm-yyyy',
											],
											'options'       => [
												'autocomplete' => 'off',
											]
										] ),
										'headerOptions' => [
											'width' => 200,
										],
									],
									[
										'attribute' => 'amount_money',
										'format'    => 'raw',
										'value'     => function ( $model ) {
											return number_format( $model->amount_money, 0, ',', '.' );
										}
									],
									[
										'attribute' => 'product',
										'value'     => function ( $model ) {
											if ( ! empty( $model->product ) ) {
												return \backend\modules\baocao\models\BaocaoChayAdsAdswords::PRODUCT_LIST[ $model->product ];
											} else {
												return '--';
											}
										}
									],
									[
										'attribute' => 'created_by',
										'value'     => function ( $model ) {
											$user          = new backend\modules\baocao\models\BaocaoChayAdsAdswords();
											$userCreatedBy = $user->getUserCreatedBy( $model->created_by );

											return $userCreatedBy->fullname;
										}
									],

								],
							] ); ?>
                            <!--demo-->

							<?php \yii\widgets\Pjax::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$url               = \yii\helpers\Url::toRoute( [ '/baocao/baocao-chay-ads-adswords/show-hide' ] );
$urlDelete         = \yii\helpers\Url::toRoute( [ '/baocao/baocao-chay-ads-adswords/delete' ] );
$urlChangePageSize = \yii\helpers\Url::toRoute( [ '/baocao/baocao-chay-ads-adswords/perpage' ] );

$tit = Yii::t( 'backend', 'Notification' );

$resultSuccess = Yii::$app->params['update-success'];
$resultDanger  = Yii::$app->params['update-danger'];

$deleteSuccess = Yii::$app->params['delete-success'];
$deleteDanger  = Yii::$app->params['delete-danger'];

$data_title = Yii::t( 'backend', 'Are you sure?' );
$data_text  = Yii::t( 'backend', 'If delete, you will not be able to recover!' );

$script = <<< JS
var customPjax = new myGridView();
customPjax.init({
    pjaxId: '#chay-ads-adswords-pjax',
    urlChangePageSize: '$urlChangePageSize',
});

$(document).ready(function () {
    //click adsword-type list on modal
    $(document).on('change','#baocaochayadsadswords-post_type',function(e){
        
        let optionSelected = $(this).children("option:selected").val();
        switch(optionSelected){
            case "1":
                $(document).find('.keyword').removeClass('hide');
                $(document).find('.banner').addClass('hide');
                $(document).find('.video').addClass('hide');
                break;
            case "2":
                $(document).find('.keyword').addClass('hide');
                $(document).find('.banner').removeClass('hide');
                $(document).find('.video').addClass('hide');
                break;
            case "3":
                $(document).find('.keyword').addClass('hide');
                $(document).find('.banner').addClass('hide');
                $(document).find('.video').removeClass('hide');
                break;
        }
    })
    
    
    $('body').on('change', '.check-toggle', function () {
        var id = $(this).val();
        $.post('$url', {id: id}, function (result) {
            if(result == 1) {
                toastr.success('$resultSuccess', '$tit');
            }
            if(result == 0) {
                toastr.error('$resultDanger', '$tit');
            }
        });
    });
    $('body').on('click', '.confirm-color', function (e) {
        e.preventDefault();
        var id = JSON.parse($(this).attr("data-id"));
        var table = $(this).closest('tr');
        var currentUrl = $(location).attr('href');

        var id = $(this).attr("data-id");
        Swal.fire({
              title: 'Bạn có chắc muốn xoá?',
              text: "Bạn sẽ không khôi phục lại được!",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Vâng, xoá nó!'
          }).then((result) => {
              if (result.value) {
                  $.ajax({
                      type: "POST",
                      cache: false,
                      data:{"id":id},
                      url: "$urlDelete",
                      dataType: "json",
                      success: function(data){
                          if(data.status == 'success') {
                          toastr.success('$deleteSuccess', '$tit');
                          console.log(customPjax.options.pjaxId);
                          $.pjax.reload({url: currentUrl, method: 'POST', container: customPjax.options.pjaxId});
                      }
                      if(data.status == 'failure' || data.status == 'exception')
                          toastr.error('Xoá không thành công', 'Thông báo');
                }
                  });

            }

        });
    });
});

JS;

$this->registerJs( $script, \yii\web\View::POS_END );
?>

