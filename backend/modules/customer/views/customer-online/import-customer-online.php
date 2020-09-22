<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 11-Dec-18
 * Time: 9:10 AM
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Import';
$this->params['breadcrumbs'][] = ['label' => 'Khách hàng trực tuyến', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
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
                <div class="card-header">
                    <h4 class="card-title">Tạo mới với danh sách</h4>
                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <?= Html::a('<i class="ft-plus"></i>', ['create'], ['title' => 'Thêm mới', 'data-pjax' => 0]) ?>
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a class="block-page"
                                   onclick='window.location="<?= \Yii::$app->getRequest()->getUrl(); ?>"'><i
                                            class="ft-rotate-cw"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            <li><a data-action="close"><i class="ft-x"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body card-dashboard">
                        <div class="container">
                            <div class="row">
                                <?php
                                $form = ActiveForm::begin(['id' => 'form-customer-online-import','options' => ['enctype' => 'multipart/form-data']]);
                                ?>
                                <?= $form->field($model, 'fileExcel')->fileInput(); ?>

                                <div class="form-actions">
                                    <?= Html::resetButton('<i class="ft-x"></i> Close', ['class' =>
                                        'btn btn-warning mr-1']) ?>
                                    <?= Html::submitButton(
                                            '<i class="fa fa-check-square-o"></i> Import',
                                            ['class' => 'btn btn-primary']
                                        ) ?>
                                </div>

                                <?php
                                ActiveForm::end();
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<?php
$script = <<< JS
    $('body').on('beforeSubmit', 'form#form-customer-online-import', function (event) {
        $.blockUI({
            message: '<div class="semibold"><span class="ft-refresh-cw icon-spin text-left"></span> <br>Import...</div>',
            overlayCSS: {
                backgroundColor: '#FFF',
                opacity: 0.9,
                cursor: 'wait'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'transparent'
            }
        });
    });
JS;
$this->registerJs($script, \yii\web\View::POS_END);
?>
