<?php

use yii\helpers\Html;
use backend\components\MyRequest;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
//$request = new MyRequest();

$this->title = 'Nội dung tư vấn';

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

            <?php $form = ActiveForm::begin([
                'action' => Url::toRoute('content/index'),
            ]); // ['options' => ['enctype' => 'multipart/form-data']]
            ?>
            <div class="form-actions">

                <?= $form->field($model, 'content')->textarea(['id'=> 'content']) ?>

            </div>
            <div class="form-actions">
                <?= Html::submitButton('<i class="fa fa-check-square-o"></i> Lưu Nội Dung',
                    ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</section>
