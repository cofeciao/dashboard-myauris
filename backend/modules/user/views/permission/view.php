<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\user\models\RbacAuthItem */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'User'), 'url' => ['/user']];
$this->params['breadcrumbs'][] = ['label' => 'Permission', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section id="dom">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><?= $this->title; ?></h4>
                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
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

                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'name',
                                'description:html',
                                'ruleName',
                                'data',
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

