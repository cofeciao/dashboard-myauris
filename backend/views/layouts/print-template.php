<?php

use backend\assets\DepositTemplateAsset;
use yii\helpers\Html;
use yii\helpers\Url;

DepositTemplateAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?php echo Yii::$app->language ?>">
<head lang="<?php echo Yii::$app->language ?>">
    <meta charset="<?php echo Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php echo Html::csrfMetaTags() ?>
    <link rel="shortcut icon" type="image/png" href="<?= Url::to('@web/images/ico/favicon.png'); ?>">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?php echo $content ?>
<?php if (!isset($this->params['noprint'])):
    ?>
    <script>
        window.onload = function () {
            setTimeout(function () {
                window.print();
            }, 500);
        };
    </script>
<?php endif; ?>
<script>
    window.onafterprint = function () {
        window.close();
    };
</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
