<?php

use yii\helpers\Url;

?>
<nav class="side-menu-addl">
    <header class="side-menu-addl-title">
        <div class="caption">Widgets</div>
    </header>
    <ul class="side-menu-addl-list">
        <li class="header<?php if (isset(Yii::$app->controller->id) && ((Yii::$app->controller->id == 'option') || (Yii::$app->controller->id == 'option'))) {
    echo ' sub-active';
} ?>">
            <a href="<?= Url::toRoute(['/option']); ?>" class="block-click">
	                <span class="tbl-row">
	                    <span class="tbl-cell tbl-cell-caption">Option</span>
	                </span>
            </a>
        </li>
        <li class="header<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'lang-web') {
    echo ' sub-active';
} ?>">
            <a href="<?= Url::toRoute(['/option/lang-web']); ?>" class="block-click">
	                <span class="tbl-row">
	                    <span class="tbl-cell tbl-cell-caption">File Language</span>
	                </span>
            </a>
        </li>
    </ul>
</nav><!--.side-menu-addl-->