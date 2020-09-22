<?php
/**
 * @var $this yii\web\View
 */

use backend\widgets\HeaderWidget;
use backend\widgets\FooterWidget;
use backend\widgets\LeftWidget;
use backend\widgets\AlertWidget;

$this->beginContent('@backend/views/layouts/base.php');
echo HeaderWidget::widget();
echo LeftWidget::widget();
echo $content;
echo FooterWidget::widget();
echo AlertWidget::widget();
$this->endContent();
