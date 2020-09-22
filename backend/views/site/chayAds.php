<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 04-Mar-19
 * Time: 3:03 PM
 */
use yii\helpers\Url;

$this->title = 'Chạy Advertising';
?>

<!--stats-->
<section id="site-content">
    <?php if ($checkCampaign != null) { ?>
        <div class="alert alert-danger mb-2 alert-campaign" role="alert">
            <strong>Bạn có chiến dịch cần phải kết thúc trong ngày!</strong>
            <ul>
                <?php foreach ($checkCampaign as $campaign) { ?>
                    <li>
                        <a href="<?= Url::toRoute(['/testab/chien-dich/update-campaign', 'id' => $campaign->id]) ?>" class="alert-link"><?= $campaign->name ?></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    <?php } else { ?>
    Welcome
    <?php } ?>
</section>
<!--/stats-->