<?php

/* @var $userInfo \backend\modules\user\models\User */

use common\helpers\MyHelper;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\web\View;

$total = 0;
if (is_array($model)) {
    $total = count($model);
}

$css = <<< CSS
.header-navbar .navbar-container .dropdown-menu-media .media-list .media {
    background: #edf2fa;
}
.header-navbar .navbar-container .dropdown-menu-media .media-list .media:hover {
    cursor:pointer;
}
.header-navbar .navbar-container .dropdown-menu-media .media-list div.seen .media {
    background: #fff;
}.header-navbar .navbar-container .dropdown-menu-media .media-list div.seen .media:hover {
    cursor:pointer;
}
CSS;
$this->registerCss($css);
?>
    <li class="dropdown dropdown-notification nav-item">
        <?php Pjax::begin(['id' => 'pjax-notification', 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]) ?>
        <a class="nav-link nav-link-label" href="#" data-pjax="0" data-toggle="dropdown">
            <i class="ficon ft-bell"></i>
            <span class="badge badge-pill badge-default badge-danger badge-default badge-up total-notif-not-seen">
                <?= isset($total_notif_not_seen) && is_numeric($total_notif_not_seen) ? $total_notif_not_seen : 0; ?>
            </span>
        </a>
        <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
            <li class="dropdown-menu-header">
                <h6 class="dropdown-header m-0">
                    <span class="grey darken-2"><?= Yii::t('backend', 'Thông báo'); ?></span>
                </h6>
            </li>
            <li class="scrollable-container media-list">
                <?php
                if (isset($model) && is_array($model)) {
                    foreach ($model as $item) {
                        $bg = '';
                        $tit = '';
                        $seen = false;
                        if ($item->is_bg = 1) {
                            $bg = 'bg-teal';
                        }
                        if ($item->is_bg = 2) {
                            $bg = 'bg-yellow ';
                            $tit = 'yellow';
                        }
                        if ($item->is_bg = 3) {
                            $bg = 'bg-red';
                            $tit = 'red';
                        }
                        ?>
                        <div class="notification <?= $item->seen != 0 ? 'seen' : '' ?>" data-href="javascript:void(0)"
                             data-pjax="0"
                             data-view="<?= Url::toRoute(['/general/notification/view', 'id' => $item->primaryKey]) ?>">
                            <div class="media">
                                <div class="media-left align-self-center">
                                    <i class="<?= $item->icon; ?> icon-bg-circle <?= $bg; ?>"></i>
                                </div>
                                <div class="media-body">
                                    <h6 class="media-heading <?= $tit ?>"><?= $item->name; ?></h6>
                                    <p class="notification-text font-small-3 text-muted">
                                        <?= $item->description; ?>
                                    </p>
                                    <small>
                                        <time class="media-meta text-muted"
                                              datetime="<?= date(DATE_ATOM, $item->created_at); ?>">
                                            <?= MyHelper::TimeBefore($item->created_at); ?>
                                        </time>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <a class="no-notif" href="javascript:void(0)" data-pjax="0">
                        <div class="media">
                            <div class="media-body">
                                <h6 class="media-heading">Không có thông báo nào mới</h6>
                            </div>
                        </div>
                    </a>
                    <?php
                } ?>
            </li>
            <li class="dropdown-menu-footer">
                <a class="dropdown-item text-muted text-center"
                   href="<?= Url::toRoute('/general/notification'); ?>" data-pjax="0">
                    Tất cả thông báo <span class="badge badge-default badge-danger m-0 total-notif-not-seen">
                    <?= isset($total_notif_not_seen) && is_numeric($total_notif_not_seen) ? $total_notif_not_seen : 0; ?>
                    </span>
                </a>
            </li>
        </ul>
        <?php Pjax::end() ?>
    </li>
    <div class="d-none notif-tmp">
        <a class="" href="javascript:void(0)" data-pjax="0" data-view="">
            <div class="media">
                <div class="media-left align-self-center">
                    <i class=""></i>
                </div>
                <div class="media-body">
                    <h6 class="media-heading"></h6>
                    <p class="notification-text font-small-3 text-muted">
                    </p>
                    <small>
                        <time class="media-meta text-muted" datetime=""></time>
                    </small>
                </div>
            </div>
        </a>
    </div>
<?php
$roleUser = $userInfo->item_name != null ? $userInfo->item_name : '';
$userId = Yii::$app->user->id;
$script = <<< JS
console.log('notification-$roleUser', 'notification-user-$userId');
function handleNotification(res){
    console.log('new notification', res);
    if (typeof res.data !== 'object' || res.data.length <= 0) return false;
    var date = new Date(),
        d = date.getDay(),
        m = date.getMonth() + 1,
        y = date.getFullYear(),
        day = (d < 10 ? '0' + d : d) + ' ' + (m < 10 ? '0' + m : m) + ' ' + y;
    var data = {
        total_notif_not_seen: (parseInt($('.total-notif-not-seen').html().trim()) || 0) + 1,
        notif: {
            urlView: res.data.urlView || null,
            icon: res.data.icon || 'ft-alert-circle',
            bg: res.data.bg || 'bg-red',
            tit: res.data.tit || 'red',
            name: res.data.name || null,
            description: res.data.description || null,
            created_at: res.data.created_at || day
        }
    };
    $('.total-notif-not-seen').html(data.total_notif_not_seen);
    if($('.scrollable-container.media-list > a.no-notif').length > 0) {
        $('.scrollable-container.media-list > a.no-notif').remove();
    }
    if(data.notif.name != null){
        var tmp = $('.notif-tmp a').clone();
        tmp.removeClass('seen').attr('data-view', data.notif.urlView)
            .find('.media-left i').attr('class', data.notif.icon +' icon-bg-circle '+ data.notif.bg);
        tmp.find('.media-heading').addClass(data.notif.tit).html(data.notif.name)
            .next('.notification-text').html(data.notif.description);
        tmp.find('.media-meta').attr('datetime', data.notif.created_at).html(data.notif.created_at);
        $('.scrollable-container.media-list').prepend(tmp);
        var act;
        console.log(data.notif);
        if(data.notif.bg === 'bg-red'){
            act = 'error';
        } else if(data.notif.bg === 'bg-yellow'){
            act = 'warning';
        } else {
            act = 'success';
        }
        toastr[act](data.notif.description, data.notif.name, {
            onclick: function(){
                tmp.trigger('click');
            }
        });
    }
    if($('.scrollable-container.media-list > a').length > 5){
        $('.scrollable-container.media-list > a').eq(5).remove();
    }
}
socket.on('connect', function(){
    if('$roleUser' != ''){
        socket.on('notification-$roleUser', function(res){
            handleNotification(res);
        });
    }
    socket.on('notification-user-$userId', function(res){
        handleNotification(res);
    });
});
$('body').on('click', '.scrollable-container.media-list > div.notification', function(e){
    e.preventDefault();
    var a = $(this),
        view = a.attr('data-view') || null,
        total_notif_not_seen = (parseInt($('.total-notif-not-seen').html().trim()) || 1) - 1;
    if(view != null){
        $.when($('#custom-modal').find('.modal-content').load(view)).done(function(){
            $('#custom-modal').modal('show');
            if(!a.hasClass('seen')) $('.total-notif-not-seen').html(total_notif_not_seen);
            a.addClass('seen');
        });
    }
    return false;
});
JS;
$this->registerJs($script, View::POS_END);
