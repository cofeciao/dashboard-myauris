<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Báo Cáo Lịch Hẹn";
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
                            <h4 class="card-title font-weight-bold text-right" id="tong-lich-moi"></h4>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-3">
                                <div class="input-group">
                                    <input type="text" class="form-control filter-data-from-online-report">
                                    <div class="input-group-append">
                                            <span class="input-group-text">
                                                <span class="fa fa-calendar"></span>
                                            </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-2">
                                <div class="input-group">
                                    <?= Html::dropDownList(
                'id-page',
                '',
                $listPage,
                ['class' => 'ui dropdown form-control', 'prompt' => 'Chọn page...', 'id' => 'id-page']
            ); ?>
                                    <span class="input-group-addon clear-option"><span class="fa fa-times"></span></span>
                                </div>
                            </div>

                            <div class="col-2">
                                <div class="input-group">
                                    <?= Html::dropDownList(
                                            'id-location',
                                            '',
                                            $listLocation,
                                            ['class' => 'ui dropdown form-control', 'prompt' => 'Chọn khu vực...', 'id' => 'id-location']
                                        ); ?>
                                    <span class="input-group-addon clear-option"><span class="fa fa-times"></span></span>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="input-group">
                                    <?= Html::dropDownList(
                                            'id-online',
                                            '',
                                            $listOnline,
                                            ['class' => 'ui dropdown form-control', 'prompt' => 'Chọn NV Online...', 'id' => 'id-online']
                                        ); ?>
                                    <span class="input-group-addon clear-option"><span class="fa fa-times"></span></span>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <?= Html::dropDownList(
                                            'id-coso',
                                            '',
                                            $listCoSo,
                                            ['class' => 'ui dropdown form-control', 'prompt' => 'Chọn Cơ Sở...', 'id' => 'id-coso']
                                        ); ?>
                                    <span class="input-group-addon clear-option"><span class="fa fa-times"></span></span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
