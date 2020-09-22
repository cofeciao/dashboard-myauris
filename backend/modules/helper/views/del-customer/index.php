<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 24-May-19
 * Time: 4:26 PM
 */

use yii\helpers\Html;

$this->title = 'Xóa khách hàng';
?>
    <section id="dom">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">
                            <div class="btn-add-campaign clearfix" style="margin-top:0px;position:relative">
                                Del Customer
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3 col-3">
                                        <?= Html::textInput('customer-id', null, ['class' => 'form-control customer-id']); ?>
                                    </div>
                                    <div class="col-md-1 col-1">
                                        <?= Html::button('Del', ['class' => 'btn btn-sx btn-primary', 'id' => 'del-customer']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
$urlDel = \yii\helpers\Url::toRoute('del-customer-about');
$script = <<< JS

$('body').on('click', '#del-customer', function() {
    var id = $('.customer-id').val();
    $('body').myLoading({
        fixed:true,
        msg: "Đang xóa dữ liệu",
    });
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '$urlDel',
        data:{id:id}
    }).done(function(res) {
        console.log(res);
        toastr.success(res.msg, 'Thông báo');
        $('body').myUnloading();
    }).fail(function(err) {
        toastr.error(err.msg, 'Lỗi');
        $('body').myUnloading();
    });
});

JS;
$this->registerJs($script, \yii\web\View::POS_END);
