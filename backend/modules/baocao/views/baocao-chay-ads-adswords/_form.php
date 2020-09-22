<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\baocao\models\BaocaoChayAdsAdswords */
/* @var $form yii\widgets\ActiveForm */

?>

<?php
$style = <<<CSS
.popover-inner {
   width: 500px !important;
      height:500px;

   max-width:500px !important;
}
.popover {
   width: 500px !important;
   height:500px;
   max-width:500px !important;
}
CSS;

$this->registerCss( $style );

$keyword_hide = '';
$banner_hide  = '';
$video_hide   = '';
if ( Yii::$app->controller->action->id == 'create' ) {
	$model->ngay_tao = date( 'd-m-Y', strtotime( '-1 day' ) );
	$keyword_hide    = '';
	$banner_hide     = 'hide';
	$video_hide      = 'hide';
}
if ( Yii::$app->controller->action->id == 'update' ) {
	$model->ngay_tao = date( 'd-m-Y', $model->ngay_tao );
}

?>



<?php $form = ActiveForm::begin( [
	'id'    => 'baocaoAdswordAjax',
	'class' => 'baocao-chay-ads-adswords-form',
] ); ?>
<div class="modal-body">
    <div class="form-actions">
        <div class="row">
            <div class="col-sm-6">
				<?= $form->field( $model, 'ngay_tao' )->widget( \dosamigos\datepicker\DatePicker::class, [
					'template'      => '{input}<span class="input-group-addon1 clear-value"><span class="fa fa-times"></span></span>{addon}',
					'clientOptions' => [
						'format'    => 'dd-mm-yyyy',
						'autoclose' => true,
					],
					'clientEvents'  => [],
					'options'       => [
						'readonly' => 'readonly',
						'class'    => 'form-control'
					]
				] ) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
				<?= $form->field( $model, 'amount_money' )->textInput( [ 'maxlength' => true ] ) ?>
            </div>
            <div class="col-sm-4">
				<?= $form->field( $model, 'product' )->dropDownList( \backend\modules\baocao\models\BaocaoChayAdsAdswords::PRODUCT_LIST ) ?>
            </div>

        </div>


        <hr>


		<?php if ( Yii::$app->controller->action->id == 'create' ) {
			$model->status = 1;
		}
		?>
		<?= $form->field( $model, 'status' )->checkbox() ?>
    </div>
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
$tit = Yii::t( 'backend', 'Notification' );

$script = <<< JS


$(document).ready(function(){
    $(".video-link .add-video").on('click',function(e){
        let i=$(".video-link input").length;
        let video_preview = $(this).parents('.video-link');
        video_preview.append('<div class="row video-link-'+i+'"><div class="col-sm-6">' +
         '<div class="input-group "><input type="text" name="video_items[]" class="form-control"><span class="input-group-btn">' +
         '<button class="btn btn-success video-preview" data-video_link_id="video-link-'+i+'" type="button">#</button><button class="btn btn-warning video-delete" data-video_link_id="video-link-'+i+'" type="button">X</button></span></div></div><div class="col-sm-6 video-preview-data video-link-'+i+'"></div></div>');
    // let button_preview=$(document).find('button.video-preview[data-video_link_id="video-link-'+i+'"]');
    //     button_preview.popover({ 
   // html : true,
   // container: 'body',
   // placement : "right",
   // content: function() {
   //     return '<iframe src="https://www.youtube.com/embed/soVVn-qQ0Yg" style="border:none" ></iframe>';    
   // }
    // });
    });
    $(document).on('click','.video-delete',function(e){
        $("."+$(this).data('video_link_id')).remove();
        $(this).popover();
    });
    $(document).on('click','.video-preview',function(e){
            let parent_video = $(this).parents('.video-link');
            parent_video.find('.video-preview-data.'+$(this).data('video_link_id')).html('<iframe src="https://www.youtube.com/embed/soVVn-qQ0Yg" style="border:none" ></iframe>');
            
            
            
            
            //popover
            /*$(this).parents('.video-link').find('.video-preview-data.'+$(this).data('video_link_id')).html();
                $(this).popover({ 
                   html : true,
                   container: 'body',
                   placement : "right",
                   content: function() {
                       return '<iframe src="https://www.youtube.com/embed/soVVn-qQ0Yg" style="border:none" ></iframe>';    
                   }
                });*/
                
    })
})

$('body').find('form#baocaoAdswordAjax').unbind('beforeSubmit').bind('beforeSubmit', function(e) {
   e.preventDefault();
   var currentUrl = $(location).attr('href');
   var formData = $('#baocaoAdswordAjax').serialize();
   
    $.ajax({
        url: $('#baocaoAdswordAjax').attr('action'),
        type: 'POST',
        data: formData,
        dataType: 'json',
    })
    .done(function(res) {
        if (res.status == 200) {
            $('.modal-header').find('.close').trigger('click');
            $.pjax.reload({url: currentUrl, method: 'POST', container:'#chay-ads-adswords-pjax'});
            setTimeout(function(){
                toastr.success(res.mess, '$tit');
            },500);
        } else {
            toastr.error(res.mess, '$tit');
            if(res.error){
                toastr.error(res.error, '$tit');
            }
        }
    });
   
   return false;
});

JS;

$this->registerJs( $script, \yii\web\View::POS_END );
?>
