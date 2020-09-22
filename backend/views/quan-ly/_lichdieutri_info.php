<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tran
 * Date: 19-04-2019
 * Time: 06:38 PM
 */

use yii\widgets\ListView;

?>
<div class="sub-panel">
    <?= ListView::widget([
        'dataProvider' => $dataLichDieuTriProvider,
        'itemView' => '_item_lichdieutri_info',
        'layout' => '{items}{pager}'
    ]) ?>
</div>
