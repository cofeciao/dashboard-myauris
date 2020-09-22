<?php
/**
 * Created by PhpStorm.
 * User: luken
 * Date: 6/20/2020
 * Time: 16:46
 */
?>
<div class="col-12">
    <div class="card mb-1">
        <div class="card-header pb-1">
            <h4 class="card-title font-weight-600">
                <?= $title ?>
            </h4>
        </div>
        <div class="card-body">
            <div class="hk-row">
                <?php
                switch (count($data)) {
                    case 1:
                        $col = 'col-lg-12';
                        break;
                    case 2:
                        $col = 'col-lg-6';
                        break;
                    case 3:
                        $col = 'col-lg-4';
                        break;
                    default:
                        $col = 'col-lg6';
                        break;
                }
                $color = ['blue-grey', 'primary', 'danger', 'warning', 'success'];
                foreach ($data as $key => $value) {
                    ?>
                    <div class="<?= $col ?> co-so" style="padding-top: 2rem;">
                        <div class="position-absolute" style="top:0;z-index:1">
                            <span class="badge badge-<?= $color[$key]; ?> badge-pill float-left font-weight-bold">Cơ sở <?= $key ?></span>
                        </div>
                        <?php
                        foreach ($value as $item) {
                            ?>
                            <div class="hk-row">
                                <div class="col-9">
                                    <?= $item['name'] ?>
                                </div>
                                <div class="col-3 text-center">
                                    <?= $item['num'] ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
