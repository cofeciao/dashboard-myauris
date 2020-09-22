<?php

use yii\helpers\Url;

if (!empty($model)) {
    ?>
<div class="row">
<?php
foreach ($model as $ite) {
        ?>
    <div class="col-lg-4 col-md-12">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <h4 class="card-title info"><?= $ite->name ?></h4>
                    <?php if ($ite->question) { ?>
                        <p class="card-text">
                            <?= $ite->question ?>
                        </p>
                    <?php } ?>
                    <a href="<?= Url::toRoute(['view', 'id' => $ite->id]) ?>" class="btn btn-outline-info">Xem</a>
                </div>
            </div>
        </div>
    </div>
    <?php
    } ?>
</div>
<?php
} else { ?>
<div class="row">
    <div class="col-12">
        <p>Không tìm thấy dữ liệu</p>
    </div>
</div>
<?php } ?>