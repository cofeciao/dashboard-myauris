<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */

/* @var $exception Exception */

use yii\helpers\Html;
use yii\helpers\Url;

$codeError = 'Lỗi không xác định';
if (isset($exception->statusCode)) {
    $codeError = $exception->statusCode;
}

$this->title = $codeError . ' - ' . $exception->getMessage();
$this->params['breadcrumbs'][] = 'Error ' . $codeError;
?>

<section class="flexbox-container">
    <div class="col-12 d-flex align-items-center justify-content-center">
        <div class="col-md-4 col-10 p-0">
            <div class="card-header bg-transparent border-0">
                <h2 class="error-code text-center mb-2"><?= $codeError; ?></h2>
                <h3 class="text-uppercase text-center"><?= $exception->getMessage(); ?></h3>
            </div>
            <div class="card-content">
                <div class="row py-2">
                    <div class="col-12 col-sm-12 col-md-12">
                        <?= Html::a('<i class="ft-home"></i> Back to Home', Url::home(), ['class' => 'btn btn-primary btn-block']); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


