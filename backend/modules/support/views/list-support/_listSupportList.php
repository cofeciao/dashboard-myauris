<?php

use yii\helpers\Url;

?>

<li class="col-12 media">
    <div class="media-body mb-2 bg-white p-2">
        <h4 class="mt-0 mb-2 text-bold-600 info text-uppercase"><?= $model->name ?></h4>
        <?php if ($model->question) { ?>
        <p class="">
            <?= $model->question ?>
        </p>
        <?php } ?>
        <a href="<?= Url::toRoute(['/support/list-group/view', 'slug' => $model->slug]) ?>" class="btn btn-outline-info">Xem</a>
    </div>
</li>
