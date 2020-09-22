<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 26-Apr-19
 * Time: 4:00 PM
 */

use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datetimepicker\DateTimePicker;

$this->title = 'Helper Dev';
?>
<?php Pjax::begin(['id' => 'clinicColor', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]); ?>
    <section id="dom">
        <div class="row">
            <div class="col-12">
                <?php
                if (Yii::$app->session->hasFlash('alert')) {
                    ?>
                    <div class="alert <?= Yii::$app->session->getFlash('alert')['class']; ?> alert-dismissible"
                         role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <?= Yii::$app->session->getFlash('alert')['body']; ?>
                    </div>
                    <?php
                }
                ?>
                <div class="card">
                    <div class="card-content collapse show">
                        <?php
                        $form = ActiveForm::begin();
                        ?>
                        <div class="card-body card-dashboard">
                            <div class="btn-add-campaign clearfix" style="margin-top:0px;position:relative">
                                Help dev
                            </div>
                            <div style="margin-top:5px;border:1px solid #ccc;border-radius:3px">
                                <div class="row">
                                    <div class="col-12 mt-1 ml-1">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-3 col-3">
                                                    <?= $form->field($model, 'strtotime')->widget(DateTimePicker::class, [
                                                        'clientOptions' => [
                                                            'format' => 'dd-mm-yyyy hh:ii',
                                                            'autoclose' => true,
                                                        ],
                                                        'clientEvents' => [
                                                        ],
                                                        'options' => [
                                                            'class' => 'form-control timestam',
                                                        ]
                                                    ])->label(false) ?>
                                                </div>
                                                <div class="col-md-1 col-1">
                                                    <?= Html::button('Submit', ['class' => 'btn btn-sx btn-primary', 'id' => 'timestam']); ?>
                                                </div>
                                                <div class="col-md-3 col-3 result">
                                                    <span class="result"></span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3 col-3">
                                                    <?= $form->field($model, 'datetoint')
                                                        ->textInput(['class' => 'form-control int-date'])->label(false); ?>
                                                </div>
                                                <div class="col-md-1 col-1">
                                                    <?= Html::button('Submit', ['class' => 'btn btn-sx btn-primary', 'id' => 'int-date']); ?>
                                                </div>
                                                <div class="col-md-3 col-3 result-date">
                                                    <span class="result-date"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body card-dashboard mt-1">
                            <div class="btn-add-campaign clearfix" style="margin-top:0px;position:relative">
                                Export Database
                            </div>
                            <div style="margin-top:5px;border:1px solid #ccc;border-radius:3px">
                                <div class="row">
                                    <div class="col-12 mt-1 ml-1">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-3 col-3">
                                                    <?= Html::a('Export Database', ['export-database'], [
                                                        'id' => 'export-database'
                                                    ]) ?>
                                                    <?php
                                                    $last_database_export = Yii::$app->cache->get('last-database-backup');
                                                    $file_url = isset($last_database_export['file_url']) ? $last_database_export['file_url'] : null;
                                                    $file_name = isset($last_database_export['file_name']) ? $last_database_export['file_name'] : null;
                                                    ?>
                                                    <p class="last-database-export"
                                                       style="display: <?= is_array($last_database_export) && file_exists(Url::to('@backend/web') . '/' . str_replace([FRONTEND_HOST_INFO . '/'], '', $file_url)) ? 'block' : 'none' ?>">
                                                        Last
                                                        file: <?= is_array($last_database_export) && file_exists(Url::to('@backend/web') . '/' . str_replace([FRONTEND_HOST_INFO . '/'], '', $file_url)) ? Html::a($last_database_export['file_name'], $last_database_export['file_url'], [
                                                            'target' => '_blank'
                                                        ]) : '' ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="target"></div>
<?php Pjax::end(); ?>
<?php
$url = \yii\helpers\Url::toRoute('/helper/helper/strtotime');
$urlDate = \yii\helpers\Url::toRoute('/helper/helper/datetoint');
$urlDownload = \yii\helpers\Url::toRoute(['/helper/helper/download-database']);
$css = <<< CSS
    button#timestam, #int-date {
        line-height: 0.7;
    }
    .result, .result-date {
        line-height: 35px;
    }
CSS;
$script = <<< JS
    $('body').on('click', '#timestam', function() {
          var str = $('.timestam').val().trim();
          $.ajax({
            url: '$url',
            type:'POST',
            dataType:'json',
            data: {"str": str},
        }).done(function(data) {
            $('.result').html(data.date);
        })
    }).on('click', '#int-date', function() {
          var int = $('.int-date').val().trim();
          $.ajax({
            url: '$urlDate',
            type:'POST',
            dataType:'json',
            data: {"int": int},
        }).done(function(data) {
            $('.result-date').html(data.int);
        })
    }).on('click', '#export-database', function(e){
        var url = $(this).attr('href') || null;
        if(url !== null){
            $('body').myLoading({
                opacity: true
            });
            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'json'
            }).done(function(res){
                if(res.code === 200){
                    toastr.success(res.msg);
                    window.open(res.file_url, '_blank', null, res.file_name);
                    if(![null, undefined].includes(res.file_name)) $('.last-database-export').html('Last file: <a href="'+ res.file_url +'">' + res.file_name + '</a>').show();
                } else {
                    toastr.warning(res.msg);
                }
                $('body').myUnloading();
            }).fail(function(f){
                $('body').myUnloading();
                toastr.error('Lỗi server. Backup thất bại!');
            });
        }
        return false;
    });
JS;

$this->registerCss($css);
$this->registerJs($script, \yii\web\View::POS_END);
