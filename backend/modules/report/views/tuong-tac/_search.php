<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Báo cáo Tương Tác";
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
                    <div class="row">
                        <div class="col-6">
                            <h4 class="card-title font-weight-bold"><?= $this->title; ?>  </h4>
                        </div>
                        <div class="col-6">
                            <h4 class="card-title font-weight-bold text-right" id="tong-fail"></h4>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xl-3 col-lg-3 col-3 col-sm-3 col-md-6 col-6">
                                <div class="input-group">
                                    <input type="text" class="form-control filter-data-from-online-report">
                                    <div class="input-group-append">
                                            <span class="input-group-text">
                                                <span class="fa fa-calendar"></span>
                                            </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
