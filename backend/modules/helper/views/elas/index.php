<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 24-May-19
 * Time: 4:26 PM
 */

use backend\models\CustomerModel;
use yii\helpers\Html;

$this->title = 'Cập nhật lại label_pancake theo ID nhân viên';
?>
    <section id="dom">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">
                            <div class="btn-add-campaign clearfix" style="margin-top:0px;position:relative">
                                <?= CustomerModel::tableName() ?>
                                <p id="last"
                                   data-last="<?php $last = CustomerModel::find()->orderBy(['id' => SORT_DESC])->one()->getId();
                                   echo $last; ?>">Last : <?= $last ?></p>
                                <p id="first"
                                   data-first="<?php $first = CustomerModel::find()->orderBy(['id' => SORT_ASC])->one()->getId();
                                   echo $first; ?>">First : <?= $first ?></p>
                                <p id="count" data-count="<?php $count = CustomerModel::find()->count();
                                echo $count; ?>">Count : <?= $count ?></p>
                            </div>

                            <div class="form-group">
                                <div class="row">

                                    <div class="col-md-3 col-3">
                                        <?= Html::textInput('from', null, ['class' => 'form-control from-id', 'placeholder' => 'from ID']); ?>
                                    </div>
                                    <div class="col-md-3 col-3">
                                        <?= Html::textInput('to', null, ['class' => 'form-control to-id', 'placeholder' => 'to ID']); ?>
                                    </div>
                                    <div class="col-md-1 col-1">
                                        <?= Html::button('Thêm 2000 ', ['class' => 'btn btn-sx btn-primary', 'id' => 'them-1000']); ?>
                                        <br>
                                        <?= Html::button('Chạy ', ['class' => 'btn btn-sx btn-primary', 'id' => 'help-pancake']); ?>
                                        <br>
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
$urlDel = \yii\helpers\Url::toRoute('run');
$autoRun = \yii\helpers\Url::toRoute('auto-run');
$script = <<< JS

$('body').on('click', '#help-pancake', function() {
    var from_id = $('.from-id').val();
    var to_id = $('.to-id').val();
    
        $('body').myLoading({
            fixed:true,
            msg: "Kiểm tra dữ liệu",
        });
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '$urlDel',
            data:{
                from_id :from_id,
                to_id:to_id
            }
        }).done(function(res) {
            console.log(res);
            toastr.success(res.msg, 'Thông báo ' );
            console.log("from_id : "+from_id+" --> to_id : "+to_id);
            $('body').myUnloading();
        }).fail(function(err) {
            // toastr.error(err.msg, 'Lỗi');
              toastr.success(res.msg, 'Tiếp tục');
            $('body').myUnloading();
        });
});

$('body').on('click', '#them-1000', function() {
    var from_id = $('.from-id').val();
    var to_id = $('.to-id').val();
    $('.from-id').val( parseInt(from_id) +2000);
    $('.to-id').val( parseInt($('.from-id').val()) + 2000);    
});


var first = parseInt($('#first').attr("data-first"));
var last = parseInt($('#last').attr("data-last"));
var count = $('#count').attr("data-count");



JS;
$this->registerJs($script, \yii\web\View::POS_END);
