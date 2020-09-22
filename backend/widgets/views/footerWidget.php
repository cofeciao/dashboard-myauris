<?php

use yii\helpers\Url;
use yii\helpers\Html;

?>
<footer class="footer footer-static footer-light navbar-border">
    <p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2">
        <span class="float-md-left d-block d-md-inline-block">Copyright &copy; 2018 <?= Html::a('myauris.vn', Url::to('http://myauris.vn'), ['class' => 'text-bold-800 grey darken-2', 'target' => '_blank']); ?>
            , All rights reserved. </span>
        <span class="float-md-right d-block d-md-inline-block d-none d-lg-block">Hand-crafted & Made with <i
                    class="ft-heart pink"></i></span>
    </p>
</footer>