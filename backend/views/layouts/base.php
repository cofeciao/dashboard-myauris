<?php

use yii\helpers\Html;
use backend\assets\AppAsset;
use yii\helpers\Url;
use backend\components\MyComponent;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
$icon = MyComponent::getCookies('icon');
if ($icon == false) {
    $icon = 0;
}
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <html lang="<?php echo Yii::$app->language ?>">
    <head>
        <meta charset="<?php echo Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <link rel="shortcut icon" type="image/png" href="<?= Url::to('@web/images/ico/favicon.png'); ?>">
        <link rel="apple-touch-icon" sizes="180x180"
              href="<?= Url::to('@web/images/ico/apple-touch-icon.png'); ?>">
        <link rel="icon" type="image/png" sizes="32x32"
              href="<?= Url::to('@web/images/ico/favicon-32x32.png'); ?>">
        <link rel="icon" type="image/png" sizes="16x16"
              href="<?= Url::to('@web/images/ico/favicon-16x16.png'); ?>">
        <link rel="manifest" href="<?= Url::to('@web/images/ico/site.webmanifest'); ?>">

        <script type="text/javascript">
            function homeUrl() {
                return '<?= FRONTEND_HOST_INFO; ?>';
            };
            var menuIcon = false;
            <?php
            if ($icon == 1) {
            ?>
            menuIcon = true;
            <?php
            }
            ?>
        </script>
        <?php echo Html::csrfMetaTags() ?>
        <title><?php echo Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <script src="<?= Url::to('@web/js/customHeader.js'); ?>"></script>
    </head>
    <?php
    if ($icon == 1) {
        $clas = 'menu-collapsed';
    } else {
        $clas = 'menu-expanded';
    }
    ?>
    <body class="vertical-layout vertical-menu 2-columns fixed-navbar <?= $clas; ?>"
          data-open="click" data-menu="vertical-menu" data-col="2-columns">
    <?php $this->beginBody() ?>
    <!--    Loading Start-->
    <div class="myLoading-container fixed" style="z-index: 9999; display: block;">
        <div class="myLoading-indicator">
            <div class="myLoading-indicator-spinner">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
            </div>
            <div class="myLoading-indicator-text">Tải dữ liệu</div>
        </div>
    </div>
    <!--    End Loading-->
    <?php echo $content ?>
    <!--    Show modal of create or update modules-->
    <div class="modal fade text-left" id="custom-modal" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

            </div>
        </div>
    </div>
    <script src="<?= Url::to('@web/js/core/app-menu.js'); ?>"></script>
    <script src="<?= Url::to('@web/js/core/app.js'); ?>"></script>
    <script src="<?= Url::to('@web/js/custom.js'); ?>"></script>
    <?php $this->endBody() ?>
    <script>
        var mydivCall = new myDiv();
        var notification = document.getElementById('notification');
        notification.addEventListener('click', function (e) {
            e.preventDefault();
            if (!window.Notification) {
                alert('Máy tính không hỗ trợ Notification. Liên hệ kỹ thuật.');
            } else {
                Notification.requestPermission(function (p) {
                    if (p === 'denied') {
                        toastr.error('Notification đã tắt, điều này không cho phép.', 'Cảnh báo');
                    } else if (p === 'granted') {
                        toastr.success('Notification đã được bật', 'Thông báo');
                    }
                });
            }
        });

        function showModal(el, url) {
            var data_target = el.attr('data-target') || null,
                modal = $(data_target) || null;
            if (modal == null) return false;
            $(data_target).find('.modal-content').load(url);
        }

        $(function () {
            $('body').on('click', 'button[type=reset]', function () {
                $(this).closest('#custom-modal, #modalCenter').find('.close').trigger('click');
            }).on('shown.bs.modal', function () {
                $('.ui.dropdown').dropdown({forceSelection: false});
                $('.select2').select2();
                $('.select2.hide-search').select2({minimumResultsForSearch: Infinity});
                $('body').on('click', '.clear-value', function (e) {
                    e.preventDefault();
                    $(this).closest('.input-group').find('input').val('');
                }).on('click', '.clear-option', function (e) {
                    e.preventDefault();
                    var selectedVal = $(this).closest('.input-group').find('option:selected').val();
                    $(this).closest('.input-group').find('option:selected').removeAttr('selected'); //.prop('selected', false);
                    $(this).closest('.input-group').find('.ui.dropdown').dropdown('restore default text')
                        .dropdown('remove selected', selectedVal);
                });
            }).on('hidden.bs.modal', '#custom-modal', function () {
                $(this).closest('#custom-modal').find('.modal-content').empty();
            }).on('hidden.bs.modal', '#modalCenter', function () {
                $(this).closest('#modalCenter').find('.modal-content').empty();
            });
        })


    </script>
    </body>
    </html>
<?php $this->endPage() ?>