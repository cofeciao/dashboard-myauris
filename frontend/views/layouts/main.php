<?php

use frontend\widgets\HeaderWidget;
use frontend\widgets\FooterWidget;
use yii\widgets\Breadcrumbs;

$this->beginContent('@frontend/views/layouts/common.php');
?>
<?= $content; ?>
<?php
$this->endContent();
?>