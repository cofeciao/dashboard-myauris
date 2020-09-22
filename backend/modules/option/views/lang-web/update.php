<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use conquer\codemirror\CodemirrorWidget;

/* @var $this yii\web\View */
/* @var $model backend\modules\news\models\News */

$this->title = Yii::t('backend', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Option'), 'url' => ['/option']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'File Language'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <div class="fixed-table-toolbar">
        <?php
        if (Yii::$app->session->hasFlash('alert')) {
            ?>
            <div class="alert <?= Yii::$app->session->getFlash('alert')['class']; ?> alert-icon alert-close alert-dismissible fade show"
                 role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <i class="font-icon font-icon-warning"></i>
                <?= Yii::$app->session->getFlash('alert')['body']; ?>
            </div>
            <?php
        }
        ?>

        <div class="bootstrap-table-header"><h1><?= Html::encode($this->title) ?></h1></div>
        <div class="columns columns-right btn-group pull-right">
            <?= Html::a('<i class="font-icon font-icon-plus-1"></i>', ['create'], ['class' => 'btn btn-default', 'title' => 'Thêm mới']) ?>
            <?= Html::button('<i class="font-icon font-icon-refresh"></i>', ['class' => 'btn btn-default', 'title' => 'Refresh', 'onclick' => 'window.location="' . \Yii::$app->getRequest()->getUrl() . '"']) ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <section class="card">
        <div class="card-block">
            <?php
            $form = ActiveForm::begin();
            ?>
            <?= $form->field($code, 'code')->widget(
                CodemirrorWidget::class,
                [
                    'preset' => 'php',
                    'options' => ['rows' => 30],
                ]
            )->label($model['file'] . '.php');
            ?>
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            <?php
            ActiveForm::end();
            ?>
        </div>
    </section>
</div>
