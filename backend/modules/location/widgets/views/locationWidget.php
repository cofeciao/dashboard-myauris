<?php

use yii\helpers\Url;

?>
<nav class="side-menu-addl">
    <header class="side-menu-addl-title">
        <div class="caption"><?= \Yii::t('rbac', 'Widget'); ?></div>
    </header>
    <ul class="side-menu-addl-list">
        <li class="header<?php if (isset(Yii::$app->controller->id) && (Yii::$app->controller->id == 'location')) {
    echo ' sub-active';
} ?>">
            <a href="<?= Url::toRoute(['/location']); ?>" class="block-click">
	                <span class="tbl-row">
	                    <span class="tbl-cell tbl-cell-caption"><?= Yii::t('backend', 'Location'); ?></span>
	                </span>
            </a>
        </li>
        <li class="header<?php if (isset(Yii::$app->controller->id) && (Yii::$app->controller->id == 'country')) {
    echo ' sub-active';
} ?>">
            <a href="<?= Url::toRoute(['/location/country']); ?>" class="block-click">
	                <span class="tbl-row">
	                    <span class="tbl-cell tbl-cell-caption"><?= Yii::t('backend', 'Country'); ?></span>
	                </span>
            </a>
        </li>
        <li class="header<?php if (isset(Yii::$app->controller->id) && (Yii::$app->controller->id == 'province')) {
    echo ' sub-active';
} ?>">
            <a href="<?= Url::toRoute(['/location/province']); ?>" class="block-click">
	                <span class="tbl-row">
	                    <span class="tbl-cell tbl-cell-caption"><?= Yii::t('backend', 'Province'); ?></span>
	                </span>
            </a>
        </li>
        <li class="header<?php if (isset(Yii::$app->controller->id) && (Yii::$app->controller->id == 'district')) {
    echo ' sub-active';
} ?>">
            <a href="<?= Url::toRoute(['/location/district']); ?>" class="block-click">
	                <span class="tbl-row">
	                    <span class="tbl-cell tbl-cell-caption"><?= Yii::t('backend', 'District'); ?></span>
	                </span>
            </a>
        </li>
        <li class="header<?php if (isset(Yii::$app->controller->id) && (Yii::$app->controller->id == 'ward')) {
    echo ' sub-active';
} ?>">
            <a href="<?= Url::toRoute(['/location/ward']); ?>" class="block-click">
	                <span class="tbl-row">
	                    <span class="tbl-cell tbl-cell-caption"><?= Yii::t('backend', 'Ward'); ?></span>
	                </span>
            </a>
        </li>
    </ul>
</nav><!--.side-menu-addl-->