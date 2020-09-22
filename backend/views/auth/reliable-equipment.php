<?php

use yii\helpers\Url;

?>
<!-- ////////////////////////////////////////////////////////////////////////////-->
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-body">
            <section class="flexbox-container">
                <div class="col-12 d-flex align-items-center justify-content-center">
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-8 col-10 box-shadow-2 p-0">
                        <div class="card border-grey border-lighten-3 m-0">
                            <div class="card-header border-0">
                                <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
                                    <span>Lưu thiết bị đáng tin cậy</span>
                                </h6>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
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
                                    <div class="login-content">
                                        <div class="form-group text-center">
                                            <a href="<?= Url::toRoute(['reliable-equipment', 'accept' => 'true']) ?>"
                                               class="btn btn-success">Lưu đây là thiết bị đáng tin cậy của bạn</a>
                                            </button>
                                        </div>
                                        <div class="text-center">
                                            <a href="<?= Url::toRoute(['site/index']) ?>">Bỏ qua, tiếp tục vào trang
                                                quản lý</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>