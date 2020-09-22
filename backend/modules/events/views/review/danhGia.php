<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 27-Feb-19
 * Time: 4:31 PM
 */

$this->registerCssFile('/css/danh-gia.css');
$this->title = 'Đánh giá lịch điều trị';
?>
    <div class="app">
        <div class="vote-index">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="content">
                            <div class="content-header">
                                <div class="col-md-3">
                                    <img style="height: 110px;" class="img-responsive" src="/images/logo-01.png"/>
                                </div>
                                <div class="col-md-9">
                                    <div class="right">
                                        <p>MY AURIS - SMART DENTAL XIN CẢM ƠN QUÝ KHÁCH HÀNG!</p>
                                        <h3 id="customer-name"><?= $nameCustomer; ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="content-content">
                                <p>Rất mong Quý khách dánh ít thời gian để đánh giá quá trình phục vụ của dịch vụ của My Auris!</p>
                                <h2 id="vote-title" style="text-transform: uppercase"><?= $nameDanhGia; ?></h2>
                                <div class="row vote-icon">
                                    <div class="col-md-4">
                                        <div class="embarrassed" id="vote-bad" point="-3">
                                            <img style="height: 150px;" src="/images/1.png"/>
                                            <p>Không hài lòng</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="embarrassed" id="vote-good" point="1">
                                            <img style="height: 150px;" src="/images/2.png"/>
                                            <p>Tốt</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="embarrassed" id="vote-excellent" point="2">
                                            <img style="height: 150px;" src="/images/3.png"/>
                                            <p>Rất hài lòng</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
$script = <<< JS
    $('body').on('click', '.embarrassed', function(e) {
        var point = $(this).attr('point');
        $.ajax({
            type: 'POST',
            data: {id: '$id', point: point, 'check': $check},
            url: '/events/review/danh-gia-result',
            dataType: 'json',
        }).done(function(res) {
            if(res.status == '200') {
                window.location.href = '/cam-on-khach-hang.html';
            }
            if(res.status == '403') {
                window.location.href = '/play-video.html';
            }
        })
    });
JS;

$this->registerJs($script, \yii\web\View::POS_END);
