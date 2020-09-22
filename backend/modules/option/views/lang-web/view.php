<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\modules\news\models\News;

/* @var $this yii\web\View */
/* @var $model backend\modules\news\models\News */

$this->title = $model['file'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Option'), 'url' => ['/option']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'File Language'), 'url' => ['/option/lang-web']];
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
            <?= Html::a('<i class="font-icon font-icon-pencil"></i>', ['update', 'file' => $model['file']], ['class' => 'btn btn-default', 'title' => 'Sửa']) ?>
            <?= Html::button('<i class="font-icon font-icon-refresh"></i>', ['class' => 'btn btn-default', 'title' => 'Refresh', 'onclick' => 'window.location="' . \Yii::$app->getRequest()->getUrl() . '"']) ?>

        </div>
    </div>
    <div class="clearfix"></div>
    <section class="card">
        <div class="card-block">
            <?php
            echo '<pre>';
            echo '<code data-language="php">';
            echo $model['orig'];
            echo '</code>';
            echo '</pre>';
            ?>
        </div>
    </section>
</div>

<?php
$this->registerCssFile(Yii::$app->request->BaseUrl . '/rainbow-master/themes/css/blackboard.css', [
    'depends' => [\yii\bootstrap\BootstrapAsset::class],
    'media' => 'screen',
    'type' => 'text/css',
], 'stylesheet');

$this->registerJsFile(Yii::$app->request->BaseUrl . '/rainbow-master/dist/rainbow.min.js', ['depends' => [yii\web\JqueryAsset::class]]);
$this->registerJsFile(Yii::$app->request->BaseUrl . '/rainbow-master/src/language/generic.js', ['depends' => [yii\web\JqueryAsset::class]]);
$this->registerJsFile(Yii::$app->request->BaseUrl . '/rainbow-master/src/language/php.js', ['depends' => [yii\web\JqueryAsset::class]]);
?>
