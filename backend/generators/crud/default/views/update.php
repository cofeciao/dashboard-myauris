<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = Yii::t('backend', 'Update');
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model-><?= $generator->getNameAttribute() ?>, 'url' => ['view', <?= $urlParams ?>]];
$this->params['breadcrumbs'][] = $this->title;
?>

<section id="dom">
    <div class="row">
        <div class="col-12">
            <?= "<?php\n" ?>
            if (Yii::$app->session->hasFlash('alert')) {
            ?>
            <div class="alert <?= "<?= " ?> Yii::$app->session->getFlash('alert')['class']; ?> alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <?= "<?= " ?> Yii::$app->session->getFlash('alert')['body']; ?>
            </div>
            <?= "<?php\n" ?>
            }
            ?>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><?= "<?= " ?> $this->title.': ' . $model-><?= $generator->getNameAttribute() ?>; ?></h4>
                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a class="block-page"
                                   onclick='window.location="<?= "<?= " ?> \Yii::$app->getRequest()->getUrl(); ?>"'><i
                                            class="ft-rotate-cw"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            <li><a data-action="close"><i class="ft-x"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body card-dashboard">
                        <?= '<?= ' ?>$this->render('_form', [
                        'model' => $model,
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
