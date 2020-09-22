<?php

use yii\widgets\ListView;
use yii\widgets\Pjax;

?>

<section id="list-support">

    <div class="card">
        <?php
        echo $this->render('_search', ['model' => $searchModel]);
        ?>
    </div>

    <div class="post-wrap detail">
        <article class="post clearfix">
            <div class=" col-12 pt-2">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><?= $model->name ?></h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <?= $model->anwser ?>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>