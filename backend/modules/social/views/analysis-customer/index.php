<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;

$this->title = Yii::t('backend', 'Analysis Customer');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Social'), 'url' => ['']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <section id="dom">
        <div class="row">
            <div class="col-12">
                <div class="card analysis-customer" id="content">
                    <div class="card-content customer-index">
                        <div class="card-body card-dashboard">
                            <?php Pjax::begin(
    ['id' => 'social-facebook',
                                    'timeout' => false, 'enablePushState' => true,
                                    'clientOptions' => ['method' => 'GET']]
); ?>
                            <?php $form = ActiveForm::begin([
                                'method' => 'get',
                                'id' => 'form-search-clinic',
                                'options' => [
                                    'data-pjax' => true
                                ],
                            ]); ?>
                            <div class="form-search">
                                <div class="row">
                                    <div class="col-xl-3">
                                        <div class="title">
                                            <label>Nguồn khách</label>
                                        </div>
                                        <div class="select-group">
                                            <?= $form->field($model, 'source')->dropDownList(
                                $filter['source'],
                                ['class' => 'select-multiple', 'multiple' => 'multiple']
                            )->label(false); ?>
                                        </div>
                                    </div>
                                    <div class="col-xl-3">
                                        <div class="title">
                                            <label>Thuộc tính phổ biến</label>
                                        </div>
                                        <div class="select-group">
                                            <?php if ($filter['analysis'] != null) {
                                echo $form->field($model, 'analysis')->dropDownList(
                                                    $filter['analysis'],
                                                    ['class' => 'select-multiple', 'multiple' => 'multiple']
                                                )->label(false);
                            } else {
                                ?>
                                                <div class="select-multiple">
                                                    <label class="p-1"> Chưa có dữ liệu nhấn
                                                        <?= Html::a(
                                                    'Khởi tạo',
                                                    Url::toRoute('create'),
                                                    ['class' => 'btn btn-sm btn-blue-grey',
                                                                'title' => 'Phân tích từ ghi chú của nhân viên về khách hàng'
                                                            ]
                                                ) ?>
                                                        để tạo mới dữ liệu !
                                                    </label>
                                                </div>
                                            <?php
                            } ?>
                                        </div>
                                    </div>
                                    <div class="col-xl-3">
                                        <div class="title">
                                            <label>Trường cần khởi tạo</label>
                                        </div>
                                        <div class="select-group">
                                            <?= $form->field($model, 'property')->dropDownList(
                                                                $filter['property'],
                                                                ['class' => 'select-multiple pr-0 pl-0', 'multiple' => 'multiple']
                                                            )->label(false); ?>
                                        </div>
                                    </div>
                                    <div class="col-xl-3">

                                        <div class="title">
                                            <label>Thời gian</label>
                                        </div>
                                        <div class="select-group">
                                            <div class="select-group">
                                                <?= $form->field($model, 'range')->dropDownList(
                                                    [
                                                        'tw'=>'Tuần này',
                                                        'tm'=>'Tháng này',
                                                        'lm'=>'Tháng trước',
                                                        'ty'=> 'Năm nay'
                                                ],
                                                    ['class' => 'form-control dropdown', 'prompt' => 'Không chọn']
                                                )->label(false); ?>
                                            </div>
                                        </div>
                                        <row>
                                            <div class="action-group">
                                                <?= Html::submitButton('Phân tích', [
                                                    'class' => 'btn btn-md btn-primary',
                                                    'id' => 'btn-submit', 'tabindex' => 1,
                                                    'data-content' => '#content-chart'
                                                ]) ?>
                                                <?= Html::resetButton('Chọn lại', [
                                                    'class' => 'btn btn-blue-grey',
                                                    'id' => 'btn-reset'
                                                ]) ?>
                                            </div>
                                        </row>
                                    </div>
                                </div>
                            </div>
                            <?php $form = ActiveForm::end() ?>
                            <div class="row">
                                <div class="col-12" id="content-chart">
                                    <div class="analysis-chart load-on-ready"></div>
                                </div>
                            </div>
                            <?php Pjax::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
<?php
$urlLoad = \yii\helpers\Url::toRoute(['load-data']);
$script = <<< JS
var urlLoad = '$urlLoad';
function loadData(el){
    if(el != null && el != undefined){
            el.myLoading();
            $.ajax({
                type: 'GET',
                url: urlLoad,
                dataType: 'json',
                data:  $('#form-search-clinic').serialize(),
            }).done(function(res) {
                el.myUnloading();
                /*el.html(res)*/
                $.when($('.load-on-ready').html('<canvas id="bar-stacked" height="500"></canvas>')).done(function() {
                    load(res);
                })
            }).fail(function(err) {
                el.myUnloading();
            })
    }
}
function load(data){
    var ctx = $("#bar-stacked");
    // Chart Options
    var chartOptions = {
        title:{
            display:false,
            text:""
        },
        tooltips: {
            mode: 'label'
        },
        responsive: true,
        maintainAspectRatio: false,
        responsiveAnimationDuration:500,
        scales: {
            xAxes: [{
                stacked: true,
                display: true,
                gridLines: {
                    color: "#f3f3f3",
                    drawTicks: true,
                },
                scaleLabel: {
                    display: true,
                }
            }],
            yAxes: [{
                stacked: true,
                display: true,
                gridLines: {
                    color: "#f3f3f3",
                    drawTicks: true,
                },
                scaleLabel: {
                    display: true,
                }
            }]
        }
    };
    // Chart Data
    var chartData = data;
    console.log('chartData', chartData);
    var config = {
        type: 'horizontalBar',
        // Chart Options
        options : chartOptions,
        data : chartData
    };
    // Create the chart
    var lineChart = new Chart(ctx, config);
}
$(document).ready(function() {
    $('.load-on-ready').each(function() {
        var el = $(this);
        loadData(el);
    });
    $('#form-search-clinic').on('beforeSubmit', function(e) {
        e.preventDefault();
        var content = $('.load-on-ready');
        loadData($(content));
        return false;
    })
   
})
JS;
$this->registerJS($script, \yii\web\View::POS_END);
$this->registerJsFile('/vendors/js/charts/chart.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);

?>