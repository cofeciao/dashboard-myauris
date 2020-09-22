<?php
/**
 * Created by PhpStorm.
 * User: abc
 * Date: 3/23/2020
 * Time: 10:46 AM
 */

$css = <<<CSS
.app-content:before{
content:unset !important;
}
CSS;


$this->registerCss($css);


?>


<section>
    <?php
    echo \yii\helpers\Html::a('Clear Cache', [\yii\helpers\Url::to('cache/deletecache')]);


    echo 'ok'; ?>
</section>








