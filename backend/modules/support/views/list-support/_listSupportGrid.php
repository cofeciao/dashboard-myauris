<?php

use yii\helpers\Url;

?>

<div class="col-lg-4 col-md-12">
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <h4 class="card-title info"><?= $model->name ?></h4>
                <?php if ($model->question) { ?>
                    <p class="card-text">
                        <?= $model->question ?>
                    </p>
                <?php } ?>
                <a href="<?= Url::toRoute(['view', 'catagory_id' => $model->catagory_id, 'id' => $model->id]) ?>"
                   data-pjax="0" class="btn btn-outline-info">Xem</a>
            </div>
        </div>
    </div>
</div>
