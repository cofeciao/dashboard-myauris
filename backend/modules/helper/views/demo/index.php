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

                        </div>

                        <div class="form-group">
                            <div class="row">
                                    <div class="col-md-3 col-3">
                                        <?= Html::textInput('search', null, ['class' => 'form-control search', 'id' => 'input-search','placeholder' => 'Search']); ?>
                                    </div>
                                    <div class="col-md-1 col-1">
                                        <?= Html::button('Tìm kiếm ', ['class' => 'btn btn-sx btn-primary','style' => "submit", 'id' => 'help-pancake']); ?>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="show-data">

</section>
<?php
$urlSearch = \yii\helpers\Url::toRoute('search');
$script = <<< JS

$('body').on('click', '#help-pancake', function () {
        var search = $('.search').val();
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '$urlSearch',
            data: {
                search: search,
            }
        }).done(function (res) {
            $('#show-data').empty();
            console.log(res.data);
            arrCustomer = res.data;
            alength = arrCustomer.length;
            for (i = 0; i < alength; i++) {
                item = arrCustomer[i];
                html = '<div class="row">'+
                            '<div class="col-12 px-3 mt-1">'+
                                '<div class="card">'+
                                    '<div class="card-content collapse show">'+
                                        '<div class="card-body card-dashboard">'+
                                            '<div class="form-group">'+
                                                    '<p>'+item.id+' '+item.customer_code+'<p>'+
                                                    '<p>'+item.name+' Địa chỉ : '+item.address+'<p>'+
                                                    '<p>'+item.phone+'<p>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>';
                
                $("#show-data").append(html);
            }
            // aListCustomer.forEach(element => {
            //   console.log(element);
            // });
        }).fail(function (err) {
            // toastr.error(err.msg, 'Lỗi');
            toastr.success(res.msg, 'Tiếp tục');
        });
    });

JS;
$this->registerJs($script, \yii\web\View::POS_END);
?>

