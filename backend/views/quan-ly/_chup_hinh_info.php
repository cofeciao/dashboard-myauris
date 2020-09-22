<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tran
 * Date: 18-04-2019
 * Time: 03:36 PM
 */

use yii\helpers\Url;

?>
<div class="sub-panel">
    <div class="sp-content">
        <?php
        if ($listChupHinh != null) {
            ?>
            <div class="owl-carousel owl-theme" data-carousel="owl-carousel">
                <?php
                foreach ($listChupHinh as $chuphinh) {
                    if ($chuphinh->image != null && file_exists(Yii::getAlias('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . $folder . '/thumb/' . $chuphinh->image)) {
                        ?>
                        <div class="item">
                            <img src="<?= Url::to('@web/uploads') . '/customer/' . $customer->slug . '-' . $customer->id . '/' . $folder . '/thumb/' . $chuphinh->image ?>"
                                 alt="<?= $customer->full_name != null ? $customer->full_name : $customer->name ?>"
                                 title="<?= $customer->full_name != null ? $customer->full_name : $customer->name ?>">
                        </div>
                        <?php
                    }
                } ?>
            </div>
            <?php
        } ?>
    </div>
</div>
