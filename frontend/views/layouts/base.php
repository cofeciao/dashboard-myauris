<?php

use yii\helpers\Html;
use frontend\assets\AppAsset;

$bundle = AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html class="no-js" lang="<?php echo Yii::$app->language ?>">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <head>
        <meta charset="<?php echo Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width,initial-scale=1, user-scalable=no"/>
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <link rel="icon" type="image/png" href="<?= \yii\helpers\Url::to('@web/images/favicon.png'); ?>"/>

        <title><?php echo Html::encode($this->title) ?></title>
        <link rel="canonical" href="<?= \Yii::$app->request->absoluteUrl; ?>"/>
        <?= Html::csrfMetaTags(); ?>

        <meta content="INDEX,FOLLOW" name="robots"/>
        <meta name="resource-type" content="Document"/>
        <meta name="distribution" content="Global"/>
        <meta name="revisit-after" content="1 days"/>

        <meta property="og:site_name" content="<?= FRONTEND_HOST_INFO; ?>"/>
        <meta property="og:type" content="website"/>
        <meta property="og:locale" content="vi_VN"/>
        <?php $this->head() ?>

    </head>
    <body>
    <?php $this->beginBody() ?>
    <?= $content ?>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>