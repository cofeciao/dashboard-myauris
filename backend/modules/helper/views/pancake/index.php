<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 24-May-19
 * Time: 4:26 PM
 */

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
                                Help Facebook Pancake - Cập nhật lại label_pancake theo ID nhân viên
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3 col-3">
                                        <?= Html::textInput('user-id', null, ['class' => 'form-control user-id','placeholder' => 'User ID']); ?>
                                    </div>
                                    <div class="col-md-3 col-3">
                                        <?= Html::textInput('lable-pancake', null, ['class' => 'form-control label-pancake' ,'placeholder' => 'label-pancake']); ?>
                                    </div>
                                    <div class="col-md-1 col-1">
                                        <?= Html::button('Lưu nhân viên', ['class' => 'btn btn-sx btn-primary', 'id' => 'help-pancake']); ?>
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
$urlDel = \yii\helpers\Url::toRoute('index');
$script = <<< JS

$('body').on('click', '#help-pancake', function() {
    var id = $('.user-id').val();
    var label_pancake = $('.label-pancake').val();
    $('body').myLoading({
        fixed:true,
        msg: "Kiểm tra dữ liệu",
    });
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '$urlDel',
        data:{id:id,
        label_pancake:label_pancake
        }
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
