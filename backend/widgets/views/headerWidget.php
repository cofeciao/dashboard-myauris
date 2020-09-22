<?php

use backend\components\MyComponent;
use common\models\User;
use common\models\UserProfile;
use yii\helpers\Html;
use yii\helpers\Url;

$css = <<< CSS
.app-content:before { content: ""; position: fixed; width: 100%; height: 100%; left: 0; background-color: rgba(0,0,0,.5); cursor: pointer; right: 0; top: 0; bottom: 0; opacity: 0; transition: all .5s ease-in-out; }
.app-content.show-overlay:before { opacity: 1; z-index: 10; }
.nav-search .search-input { padding-top: 0; }
.nav-search .search-input.open { position: absolute; left: 0; right: 0; top: 0; width: 100%; z-index: 1001; margin-top: 0; box-shadow: 6px 12px 18px 0 rgba(25,42,70,.13); }
.nav-search .search-input.open .input { border-color: #E0E2E8; background-color: #FFF; display: block; }
.nav-search .search-input .search-input-close { z-index: 1001; display: none; position: absolute; right: 1rem; top: 40%; cursor: pointer; color: #2A2E30; }
.nav-search .search-input.open .search-input-close { display: block; }
.nav-search .search-input .search-list { position: absolute; top: 100%; left: 0; background: #FFF; width: 60rem; margin-top: .5rem; padding-left: 0; border-radius: .25rem; display: none; }
.nav-search .search-input .search-list.show { display: block; width: 98%; left: 1%; }
.nav-search .search-input .search-list li { border-bottom: 1px solid #F2F4F4; }
.nav-search .search-input .search-list li:first-child { border-top-left-radius: .25rem; border-top-right-radius: .25rem; }
.nav-search .search-input .search-list li.current_item, .nav-search .search-input .search-list li:hover { background-color: #F2F4F4; }
.nav-search .search-input .search-list li a { padding: 1.2rem 1rem; color: #404E67; }

@media (min-width: 768px) and (max-width: 992px) {
    .nav-search .search-input .search-list li a div:nth-child(1),
    .nav-search .search-input .search-list li a div:nth-child(2){
        margin-bottom: 1rem;
    }
}

@media (min-width: 768px) and (max-width: 992px) {
    .nav-search .search-list li a div:nth-child(1),
    .nav-search .search-list li a div:nth-child(2){
        margin-bottom: 1rem;
    }
}


CSS;
$this->registerCss($css);

$menuIcon = MyComponent::getCookies('icon');
if ($menuIcon === false) {
    $menuIcon = 0;
    $isActive = '';
} else {
    $isActive = 'is-active';
}

?>
    <nav class="header-navbar navbar-expand-md navbar navbar-with-menu fixed-top navbar-semi-dark navbar-shadow">
        <div class="navbar-wrapper">
            <div class="navbar-header">
                <ul class="nav navbar-nav flex-row">
                    <li class="nav-item mobile-menu d-md-none mr-auto"><a
                                class="nav-link nav-menu-main menu-toggle hidden-xs <?= $isActive; ?>" href="#"><i
                                    class="ft-menu font-large-1"></i></a></li>
                    <li class="nav-item mr-auto"><a class="navbar-brand" href="#">
                            <h1 class="brand-text"><?= VERSION; ?></h1></a></li>
                    <li class="nav-item d-md-none">
                        <a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i
                                    class="fa fa-ellipsis-v"></i></a>
                    </li>
                </ul>
            </div>
            <div class="navbar-container content">
                <div class="collapse navbar-collapse" id="navbar-mobile">
                    <ul class="nav navbar-nav mr-auto float-left">
                        <li class="nav-item d-none d-md-block"><a
                                    class="nav-link nav-menu-main menu-toggle hidden-xs"
                                    href="#"><i class="ft-menu"></i></a>
                        </li>
                        <!--                        <li class="dropdown nav-item mega-dropdown"><a class="dropdown-toggle nav-link" href="#"-->
                        <!--                                                                       data-toggle="dropdown"-->
                        <!--                                                                       aria-expanded="false"><i class="fa fa-plug"></i>-->
                        <!--                                Options</a>-->
                        <!--                            <ul class="mega-dropdown-menu dropdown-menu row">-->
                        <!--                                <li class="col-md-3">-->
                        <!--                                    <h6 class="dropdown-menu-header text-uppercase"><i class="fa fa-home"></i> Dashboard-->
                        <!--                                    </h6>-->
                        <!--                                    <ul class="drilldown-menu sliding-menu" style="height: 209px;">-->
                        <!--                                        <div class="sliding-menu-wrapper" style="width: 1118.25px;">-->
                        <!--                                            <ul id="menu-panel-wuz1d" class="menu-panel-root" style="width: 372.75px;">-->
                        <!--                                                <div class="row">-->
                        <!--                                                    <div class="col-6">-->
                        <!--                                                        <div class="custom-control custom-checkbox">-->
                        <!--                                                            <input type="checkbox" id="menu-icon-hide"-->
                        <!--                                                                   class="custom-control-input check-toggle "-->
                        <!--                                                                   name="menu-icon-hide" --><?php //if ($menuIcon == 1) {
                        //                                                                echo 'checked';
                        //                                                            } ?>
                        <!--                                                                   value="-->
                        <!--                        --><?php //echo $menuIcon; ?><!--">-->
                        <!--                                                            <label class="custom-control-label"-->
                        <!--                                                                   for="menu-icon-hide">Menu Icon</label>-->
                        <!--                                                        </div>-->
                        <!--                                                    </div>-->
                        <!--                                                </div>-->
                        <!--                                            </ul>-->
                        <!--                                        </div>-->
                        <!--                                    </ul>-->
                        <!--                                </li>-->
                        <!--                            </ul>-->
                        <!--                        </li>-->

                        <!--                        search-->
                        <?php if (Yii::$app->user->can(User::USER_DEVELOP) ||
                        Yii::$app->user->can('helperElasSearch')) { ?>
                            <li class="nav-item nav-search">
                                <a class="nav-link nav-link-search" href="#"><i class="ficon ft-search"></i></a>
                                <div class="search-input">
<!--                                    <input class="input autocomplete" type="text" placeholder="Tìm khách hàng...">-->
                                    <input class="input" type="text" placeholder="Tìm khách hàng..." tabindex="0" data-search="template-search">
                                    <div class="search-input-close"><i class="ficon ft-x"></i></div>
                                    <ul class="search-list"></ul>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>

                    <ul class="nav navbar-nav navbar-nav-right float-right">
                        <?php
                        if (Yii::$app->user->can(User::USER_MANAGER_ONLINE) ||
                            Yii::$app->user->can(User::USER_SEO) ||
                            Yii::$app->user->can(User::USER_NHANVIEN_ONLINE)) {
                            ?>
                            <li class="nav-item ">
                                <?= Html::button(
                                    '<i class="fa fa-plus"></i> Khách hàng',
                                    [
                                        'title' => 'Thêm khách hàng (Online)',
                                        'class' => 'btn btn-default btn-create pull-left',
                                        'data-pjax' => 0,
                                        'data-toggle' => 'modal',
                                        'data-backdrop' => 'static',
                                        'data-keyboard' => false,
                                        'data-target' => '#custom-modal',
                                        'onclick' => 'showModal($(this), "' . \yii\helpers\Url::toRoute(['/customer/customer-online/create']) . '");return false;',
                                    ]
                                )
                                ?>
                            </li>
                            <?php
                        }
                        ?>
                        <?php
                        if (Yii::$app->user->can(User::USER_MANAGER_LE_TAN) ||
                            Yii::$app->user->can(User::USER_LE_TAN)) {
                            ?>
                            <li class="nav-item">
                                <?= Html::button(
                                    '<i class="fa fa-plus"></i> Khách hàng',
                                    [
                                        'title' => 'Thêm khách hàng (Lễ tân)',
                                        'class' => 'btn btn-default btn-create pull-left ml-1',
                                        'data-pjax' => 0,
                                        'data-toggle' => 'modal',
                                        'data-backdrop' => 'static',
                                        'data-keyboard' => false,
                                        'data-target' => '#custom-modal',
                                        'onclick' => 'showModal($(this), "' . \yii\helpers\Url::toRoute(['/clinic/clinic/create']) . '");return false;',
                                    ]
                                )
                                ?>
                            </li>
                            <?php
                        }
                        ?>
                        <li class="nav-item">
                            <?php
                            $user = new \backend\modules\user\models\User();
                            $roleUser = $user->getRoleName(Yii::$app->user->id);
                            if (Yii::$app->user->can('callShow') ||
                                Yii::$app->user->can(User::USER_DEVELOP)) {
                                ?>
                                <?php
                                if (defined('CONSOLE_HOST') && CONSOLE_HOST != 1) {
                                ?>
                                <div class="open-phone">
                                    <div class="hotline">
                                        <a class="btn-hotline">
                                            <div class="hotline-circle"></div>
                                            <div class="hotline-circle-fill"></div>
                                            <i class="fa fa-mobile"></i>
                                        </a>
                                    </div>
                                </div>
                                <div id="call-365dep">
                                    <?= $this->render('_call'); ?>
                                </div>
                                <div style="display: none">
                                    <video id="remoteVideo" playsinline autoplay></video>
                                </div>
                                <?php
                                }
                                ?>
                                <?php
                            }
                            ?>
                        </li>
                        <li class="dropdown dropdown-language nav-item">
                            <a class="dropdown-toggle nav-link" id="dropdown-flag" href="#" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false" title="<?= Yii::t('backend', 'Language'); ?>">
                                <i class="flag-icon flag-icon-vn"></i>
                                <span class="selected-language"></span></a>
                            <div class="dropdown-menu" aria-labelledby="dropdown-flag">
                                <a class="dropdown-item" href="#" title="<?= Yii::t('backend', 'Tiếng việt'); ?>"><i
                                            class="flag-icon flag-icon-vn"></i> Tiếng việt</a>
                            </div>
                        </li>
                        <?= \backend\widgets\NotificationWidget::widget([]) ?>
                        <li class="dropdown dropdown-user nav-item">
                            <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                            <span class="avatar avatar-online">
                                <img src="<?= UserProfile::getAvatar('70x70'); ?>" alt="avatar">
                                <i></i>
                            </span>
                                <span class="user-name"><?= UserProfile::getFullName(); ?></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="<?= Url::toRoute(['/auth/profile']); ?>">
                                    <i class="ft-user"></i> Thông tin cá nhân
                                </a>
                                <a class="dropdown-item" href="<?= Url::toRoute(['/auth/change-pass-word']); ?>">
                                    <i class="ft-edit"></i> Thay đổi mật khẩu
                                </a>
                                <a class="dropdown-item" href="#" id="notification">
                                    <i class="ft-bell"></i> Bật Notification
                                </a>
                                <div class="dropdown-divider"></div>
                                <?= Html::a('<i class="ft-power"></i> ' . Yii::t('backend', 'Logout'), Url::toRoute(['/auth/logout']), ['class' => 'dropdown-item', 'data-method ' => 'POST']); ?>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
<?php
$urlIconMenu = Yii::$app->getUrlManager()->createUrl('config/set-menu-icon');
$urlSearch = \yii\helpers\Url::toRoute(['/helper/elas/search']);
$urlCustomerView = \yii\helpers\Url::toRoute(['/quan-ly/customer-view?id=']);
$urlShowAll = \yii\helpers\Url::toRoute(['/quan-ly/index?CustomerModelSearch%5Btype_search_lichhen%5D=date&CustomerModelSearch%5Btype_search_customer_come%5D=date&CustomerModelSearch%5Bbutton%5D=1&CustomerModelSearch%5Bkeyword%5D=']);
$script = <<< JS
$('body').on('change', '#menu-icon-hide', function() {
    var iconMenu = $(this).val();
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '$urlIconMenu',
        data: {icon:iconMenu}
    }).done(function(data) {
        if(data.status == '200') {
            location.reload();
            toastr.success('Thành công', 'Thông báo');
        }
    })
});

var d = $(".search-input input").data("search");
$(".nav-link-search").on("click", (function() {
    $(this).siblings(".nav-search").find(".search-input").addClass("open"),
    $(".search-input input").focus(),
    $(".search-input .search-list li").remove(),
    $(".search-input .search-list").addClass("show")
})),
$(".search-input-close i").on("click", (function() {
    var e = $(this).closest(".search-input");
    e.hasClass("open") && (e.removeClass("open"),
    $(".search-input input").val(""),
    $(".search-input input").blur(),
    $(".search-input .search-list").removeClass("show"),
    $(".app-content").hasClass("show-overlay") && $(".app-content").removeClass("show-overlay"))
})),
$(".app-content").on("click", (function() {
    var e = $(".search-input-close"), 
        n = $(e).parent(".search-input"), 
        t = $(".search-list");
    n.hasClass("open") && n.removeClass("open"),
    t.hasClass("show") && t.removeClass("show"),
    $(".app-content").hasClass("show-overlay") && $(".app-content").removeClass("show-overlay")
}));
var timeOutSearch;
$('body').on('keyup', '.search-input input', function(e) {
    if (38 !== e.keyCode && 40 !== e.keyCode && 13 !== e.keyCode) {
        clearTimeout(timeOutSearch);        
        timeOutSearch = setTimeout(function () {
            var n = $('.search-input input').val().toLowerCase(),
                t = '',
                s = '',
                i = '',
                l = 10;
            if ($(".search-list li").remove(), n != '') {
                $(".app-content").addClass("show-overlay");                
                $.post('$urlSearch', {search: n}, function(res) {
                    console.log('TYPE SEARCH : ' + res.type_search);
                    let arrayData = res.data;
                    for (let i = 0; i < arrayData.length; i++) {
                        t += '<li class="auto-suggestion d-flex align-items-center justify-content-between cursor-pointer"> ' +
                                        '<a href="$urlCustomerView' + arrayData[i].id + '" target="_blank" class="d-flex flex-wrap align-items-center justify-content-start w-100"> ' +
                                            '<div class="col-lg-2 col-md-6 col-12">Mã KH: <strong>' + (arrayData[i].customer_code != null ? arrayData[i].customer_code : '-') + '</strong></div>' +
                                            '<div class="col-lg-4 col-md-6 col-12">Tên KH: <strong> ' + (arrayData[i].name != null ? arrayData[i].name : (arrayData[i].full_name != null ? arrayData[i].full_name : (arrayData[i].forename != null ? arrayData[i].forename : '-')) ) + ' </strong></div>' +
                                            '<div class="col-lg-2 col-md-6 col-12">SĐT: <strong>' + (arrayData[i].phone != null ? arrayData[i].phone : '-') + '</strong></div>' +
                                            '<div class="col-lg-4 col-md-6 col-12">Địa chỉ: <strong>' + (arrayData[i].district != null && arrayData[i].district != '' ? 'Quận ' + arrayData[i].district + ', ' : '') + (arrayData[i].province != null ? arrayData[i].province : '-') + '</strong></div>' +
                                        '</a> ' +
                                    '</li>';
                    }
                    
                    if (t == '' && s == '') {
                        s = '<li class="auto-suggestion d-flex align-items-center justify-content-between cursor-pointer">' +
                         '<a class="d-flex align-items-center justify-content-between w-100"><div class="d-flex justify-content-start">' +
                          '<span class="mr-75"></span><span>No results found.</span></div></a></li>';
                    }
                    
                    i = t.concat(s);
                    l < arrayData.length && (i += '<li class="auto-suggestion d-flex align-items-center justify-content-between cursor-pointer">' +
                            '<a href="$urlShowAll' + n + '" target="_blank" class="d-flex flex-wrap align-items-center justify-content-center w-100">Xem tất cả</a></li>');
                    $('ul.search-list').html(i);
                }, 'json');
            } else 
                $(".app-content").hasClass("show-overlay") && $(".app-content").removeClass("show-overlay");
        }, 500);
        
        if (27 == e.keyCode) {
            $('.search-input input').val(''),
            $('.search-input input').blur(),
            $('.search-input').removeClass('open');
            
            if ($('.search-list').hasClass('show')) {
                $('.search-list').removeClass('show'),
                $('.search-input').removeClass('show');
            }
        }
    }
}).on("mouseenter", ".search-list li", (function(e) {
        $(this).siblings().removeClass("current_item"),
        $(this).addClass("current_item")
    }
));
JS;
$this->registerJs($script, \yii\web\View::POS_END);
