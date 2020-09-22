<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tran
 * Date: 18-04-2019
 * Time: 03:36 PM
 */

use yii\helpers\Url;

?>
<div class="form-upload" id="<?= $folder ?>">

    <?php if (!empty($aImage)) : ?>
        <div class="g-list-file">
            <?php
            foreach ($aImage as $image) : ?>
                <div class="g-file">
                    <div>
                        <div class="g-tools">
                            <span class="hidden">
                                <a class="g-download g-image"
                                   data-image="<?= Url::to($image['webContentLink']) ?>"
                                    <i class="fa fa-download"></i>
                                </a>
                            </span>

                        </div>
                        <div class="g-view">
                            <i class="fa fa-search"></i>
                        </div>
                        <img class="g-thumb" src="<?= Url::to($image['webContentLink']) ?>">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>
