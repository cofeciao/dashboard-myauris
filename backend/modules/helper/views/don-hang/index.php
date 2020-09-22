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
                                ID DON HANG
                            </div>

                            <div class="form-group">
                                <div class="row">

                                    <div class="col-md-3 col-3">
                                        <?= Html::textInput('id', null, ['class' => 'form-control id-don-hang', 'placeholder' => ' ID']); ?>
                                    </div>

                                    <div class="col-md-1 col-1">
                                        <?= Html::button('Run ', ['class' => 'btn btn-sx btn-primary', 'id' => 'help-pancake']); ?>
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
$script = <<< JS

$('body').on('click', '#help-pancake', function() {
    var id_don_hang = $('.id-don-hang').val();
    
        $('body').myLoading({
            fixed:true,
            msg: "Kiểm tra dữ liệu",
        });
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '$urlDel',
            data:{
                id_don_hang :id_don_hang,
            }
        }).done(function(res) {
            console.log(res);
            toastr.success(res.msg, 'Thông báo ' );
            $('body').myUnloading();
        }).fail(function(err) {
            // toastr.error(err.msg, 'Lỗi');
              toastr.success(res.msg, 'Tiếp tục');
            $('body').myUnloading();
        });
});




JS;
$this->registerJs($script, \yii\web\View::POS_END);
