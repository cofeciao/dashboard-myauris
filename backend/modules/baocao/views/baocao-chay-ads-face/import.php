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
            <div class="card import-ads">
                <div class="card-header">
                    <h4 class="card-title">Tạo mới với danh sách File Ads</h4>
                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body card-dashboard">
                        <div class="container">
                            <div class="row">
                                <?php
                                $form = ActiveForm::begin([
                                    'id' => 'form-baocao-ads-import',
                                    'options' => ['enctype' => 'multipart/form-data']]);
                                ?>
                                <?= $form->field($model, 'fileExcel')->fileInput(); ?>

                                <div class="form-actions">
                                    <?= Html::resetButton('<i class="ft-x"></i> Close', ['class' =>
                                        'btn btn-warning mr-1 close-import', 'data-dismiss' => 'modal']) ?>
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
