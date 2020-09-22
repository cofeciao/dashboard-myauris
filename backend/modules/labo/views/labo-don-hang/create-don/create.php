<?php


/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;


/* @var $model backend\modules\labo\models\LaboDonHang */

$this->title = Yii::t('backend', 'Phiếu Labo răng sứ');
//$this->params['breadcrumbs'][] = ['label' => 'Labo Đơn Hàng', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<section id="dom">
    <div class="row">
        <div class="col-12">
            <?php
            if (Yii::$app->session->hasFlash('alert')) {
            ?>
                <div class="alert <?= Yii::$app->session->getFlash('alert')['class']; ?> alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <?= Yii::$app->session->getFlash('alert')['body']; ?>
                </div>
            <?php
            }
            ?>

            <div class="content-header row p-2 customer-header">
                <div class="content-header-left col-md-6 col-12 ">
                    <h4 class="content-header-title ">Khách hàng :<strong><?= $mCustomer->name ?></strong> - Mã KH :<strong><?= $mCustomer->customer_code ?></strong>
                        <a target="_blank" href="<?= Url::toRoute(['/quan-ly/customer-view', 'id' => $mCustomer->id], true) ?>">
                            <button type="button" class="btn btn-primary">Thông tin khách hàng</button>
                        </a>
                    </h4>
                </div>
            </div>

            <div class="content-body">
                <div class=" row px-1 pt-1">

                    <div class="col-lg-12 col-12">

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><?= $this->title; ?></h4>
                                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                        <li><a class="block-page" onclick='window.location="<?= \Yii::$app->getRequest()->getUrl(); ?>"'><i class="ft-rotate-cw"></i></a></li>
                                        <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                        <li><a data-action="close"><i class="ft-x"></i></a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card-content collapse show">
                                <div class="card-body card-dashboard">
                                    <?= $this->render('_form', [
                                        'model' => $model,
                                    ]) ?>
                                </div>
                            </div>
                        </div>

                    </div>


                </div>


            </div>

        </div>

    </div>

    <!--                    danh sach giai doan-->
    <div class=" row ">

        <div class="col-12">

            <?php
            if (false) :  //(!$model->isNewRecord): // tam thoi khong the hien giai doan de ke toan su dung
            ?>
                <div class="ccb-content load-data" url-load="<?= Url::toRoute(['labo-don-hang/giai-doan', 'id' => $model->id]) ?>">
                </div>
            <?php
            endif;
            ?>

        </div>

    </div>
    <!-- END danh sach giai doan-->

</section>
<?php
$script = <<< JS
function loadElement(el, url, callback = function(){}){
    el.myLoading().load(url, {}, function(){
    el.myUnloading();
    if(typeof callback == "function") callback();
    });
}

$(window).ready(function(){
    $('.load-data').each(function(){
        var el = $(this),
            url_load = el.attr('url-load') || null;
        if(url_load != null){
            loadElement(el, url_load);
        }
    });
});

JS;
$this->registerJs($script, \yii\web\View::POS_END);

//phan js xu ly DataListView

$url = Url::toRoute(['show-hide']);
$urlDelete = Url::toRoute(['labo-giai-doan/delete']);
$urlChangePageSize = Url::toRoute(['perpage']);

$tit = Yii::t('backend', 'Notification');

$resultSuccess = Yii::$app->params['update-success'];
$resultDanger = Yii::$app->params['update-danger'];

$deleteSuccess = Yii::$app->params['delete-success'];
$deleteDanger = Yii::$app->params['delete-danger'];

$data_title = Yii::t('backend', 'Are you sure?');
$data_text = Yii::t('backend', 'If delete, you will not be able to recover!');

$script = <<< JS
var customPjax = new myGridView();
customPjax.init({
    pjaxId: '#custom-pjax',
    urlChangePageSize: '$urlChangePageSize',
});

$(document).ready(function () {
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
        Swal.fire({
            title: "$data_title",
            text: "$data_text",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    cache: false,
                    data: {
                        "id": id
                    },
                    url: "$urlDelete",
                    dataType: "json",
                    success: function(data) {
                        if (data.status == 'success') {
                            toastr.success('$deleteSuccess', '$tit');
                            table.slideUp("slow");
                            $.pjax.reload({
                                url: currentUrl,
                                method: 'POST',
                                container: customPjax.options.pjaxId
                            });
                        }
                        if (data.status == 'failure' || data.status == 'exception')
                            toastr.error('Xoá không thành công', 'Thông báo');
                    }
                });
            }
        });
    });
});
JS;

$this->registerJs($script, \yii\web\View::POS_END);
