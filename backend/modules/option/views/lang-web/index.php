<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\news\models\search\SearchNews */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'File Language');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Option'), 'url' => ['/option']];
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
            <?= Html::button('<i class="font-icon font-icon-refresh"></i>', ['class' => 'btn btn-default', 'title' => 'Refresh', 'onclick' => 'window.location="' . \Yii::$app->getRequest()->getUrl() . '"']) ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <section class="card">
        <div class="card-block">
            <section class="card">
                <div class="card-block">
                    <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th><?=Yii::t('backend', 'File Name'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($file as $keu => $item) {
                            $temp = explode('\\', $item);
                            $name = array_pop($temp);
                            $fileName = str_replace('.php', '', $name)
                            ?>
                            <tr>
                                <td><?= Html::a($name, ['update', 'file' => $fileName]); ?></td>
                                </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </section>
</div>
