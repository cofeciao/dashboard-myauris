<?php

use backend\modules\setting\models\Dep365CoSo;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use common\grid\MyGridView;
use backend\modules\user\models\User;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\user\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Nhân viên';
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'User'), 'url' => ['']];
$this->params['breadcrumbs'][] = $this->title;

$user = new User();
$roleUser = $user->getRoleName(Yii::$app->user->id);
$roleDev = User::USER_DEVELOP;

$idUser = false;
if ($roleUser == $roleDev) {
    $idUser = true;
}
$listCoSo = ArrayHelper::map(Dep365CoSo::getCoSo(), 'id', 'name');

$css = <<< CSS
.label-pancake {
    font-size: 12px;
    font-weight: 700;
}
CSS;
$this->registerCss($css);
?>
<section id="dom">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content collapse show">
                    <div class="card-body card-dashboard">
                        <div class="btn-add-campaign clearfix"
                             style="margin-top:0px;position:relative; margin-bottom: 10px">
                            <?= Html::button(
    '<i class="fa fa-plus"></i> Thêm mới',
    [
                                    'title' => 'Thêm mới',
                                    'class' => 'btn btn-default pull-left',
                                    'data-pjax' => 0,
                                    'data-toggle' => 'modal',
                                    'data-backdrop' => 'static',
                                    'data-keyboard' => false,
                                    'data-target' => '#custom-modal',
                                    'onclick' => 'showModal($(this), "' . \yii\helpers\Url::toRoute(['create']) . '");return false;',
                                ]
)
                            ?>
                        </div>
                        <div style="margin-top:5px;border:1px solid #ccc;border-radius:3px">
                            <?php Pjax::begin(['id' => 'userList', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]); ?>
                            <?= MyGridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'layout' => '{errors} <div class="pane-single-table">{items}</div><div class="pager-wrap clearfix">{summary}' .
                                    Yii::$app->controller->renderPartial('@backend/views/layouts/my-gridview/_goToPage', ['totalPage' => $totalPage, 'currentPage' => Yii::$app->request->get($dataProvider->getPagination()->pageParam)]) .
                                    Yii::$app->controller->renderPartial('@backend/views/layouts/my-gridview/_pageSize') .
                                    '{pager}</div>',
                                'tableOptions' => [
                                    'id' => 'listCampaign',
                                    'class' => 'cp-grid cp-widget pane-hScroll',
                                ],
                                'myOptions' => [
                                    'class' => 'grid-content my-content pane-vScroll',
                                    'data-minus' => '{"0":42,"1":".header-navbar","2":".btn-add-campaign","3":".pager-wrap","4":".footer","5":".card-header"}'
                                ],
                                'summaryOptions' => [
                                    'class' => 'summary pull-right',
                                ],
                                'pager' => [
                                    'firstPageLabel' => Html::tag('span', 'skip_previous', ['class' => 'material-icons']),
                                    'lastPageLabel' => Html::tag('span', 'skip_next', ['class' => 'material-icons']),
                                    'prevPageLabel' => Html::tag('span', 'play_arrow', ['class' => 'material-icons']),
                                    'nextPageLabel' => Html::tag('span', 'play_arrow', ['class' => 'material-icons']),
                                    'maxButtonCount' => 5,

                                    'options' => [
                                        'tag' => 'ul',
                                        'class' => 'pagination pull-left',
                                    ],

                                    // Customzing CSS class for pager link
                                    'linkOptions' => ['class' => 'page-link'],
                                    'activePageCssClass' => 'active',
                                    'disabledPageCssClass' => 'disabled page-disabled',
                                    'pageCssClass' => 'page-item',

                                    // Customzing CSS class for navigating link
                                    'prevPageCssClass' => 'page-item prev',
                                    'nextPageCssClass' => 'page-item next',
                                    'firstPageCssClass' => 'page-item first',
                                    'lastPageCssClass' => 'page-item last',
                                ],
                                'columns' => [
                                    [
                                        'class' => 'yii\grid\SerialColumn',
                                        'header' => 'STT',
                                        'headerOptions' => [
                                            'width' => 60,
                                            'rowspan' => 2
                                        ],
                                        'filterOptions' => [
                                            'class' => 'd-none'
                                        ],
                                    ],
                                    [
                                        'attribute' => 'id',
                                        'visible' => $idUser,
                                        'value' => function ($model) {
                                            return $model->id;
                                        },
                                        'headerOptions' => [
                                            'width' => 70,
                                            'rowspan' => 2
                                        ],
                                        'filterOptions' => [
                                            'class' => 'd-none'
                                        ],
                                    ],
                                    ['class' => 'yii\grid\ActionColumn',
                                        'header' => 'Actions',
                                        'template' => '<div class="btn-group" role="group">{view} {update} {delete} {resetPassword} {login-with-user}</div>',
                                        'buttons' => [
                                            'view' => function ($url, $model) {
                                                return Html::button(
                                                    '<i class="ft-eye"></i>',
                                                    [
                                                        'title' => 'Xem',
                                                        'class' => 'btn btn-default',
                                                        'data-pjax' => 0,
                                                        'data-toggle' => 'modal',
                                                        'data-backdrop' => 'static',
                                                        'data-keyboard' => false,
                                                        'data-target' => '#custom-modal',
                                                        'onclick' => 'showModal($(this), "' . $url . '");return false;',
                                                    ]
                                                );
                                            },
                                            'update' => function ($url, $model) {
                                                return Html::button(
                                                    '<i class="ft-edit blue"></i>',
                                                    [
                                                        'title' => 'Cập nhật',
                                                        'class' => 'btn btn-default',
                                                        'data-pjax' => 0,
                                                        'data-toggle' => 'modal',
                                                        'data-backdrop' => 'static',
                                                        'data-keyboard' => false,
                                                        'data-target' => '#custom-modal',
                                                        'onclick' => 'showModal($(this), "' . $url . '");return false;',
                                                    ]
                                                );
                                            },
                                            'delete' => function ($url, $model) {
                                                return Html::a('<i class="ft-trash-2 red confirm-color" data-id = "' . $model->id . '" ></i>', 'javascript:void(0)', ['class' => 'btn btn-default']);
                                            },
                                            'resetPassword' => function ($url, $model) {
                                                return Html::a('<i class="ft-refresh-cw gold reset-password" title = "Reset Password" data-id = "' . $model->id . '" ></i>', 'javascript:void(0)', ['class' => 'btn btn-default']);
                                            },
                                            'login-with-user' => function ($url, $model) {
                                                $user = new User();
                                                $roleUser = $user->getRoleName(Yii::$app->user->id);
                                                if (in_array($roleUser, [User::USER_DEVELOP, User::USER_ADMINISTRATOR])) {
                                                    $name = $model->userProfile->fullname != null ? $model->userProfile->fullname : $model->username;
                                                    return Html::a('<i class="ft-log-in green" title="Login bằng tài khoản ' . $name . '"></i>', $url, [
                                                        'class' => 'btn btn-default btn-login-with-user',
                                                        'data-user' => $name
                                                    ]);
                                                }
                                                return null;
                                            }
                                        ],
                                        'headerOptions' => [
                                            'width' => in_array($roleUser, [User::USER_DEVELOP, User::USER_ADMINISTRATOR]) ? 160 : 140,
                                            'rowspan' => 2
                                        ],
                                        'filterOptions' => [
                                            'class' => 'd-none'
                                        ],
                                    ],
                                    [
                                        'attribute' => 'fullname',
                                        'value' => 'userProfile.fullname'
                                    ],
                                    [
                                        'attribute' => 'email',
                                        'format' => 'html',
                                        'value' => function ($model) {
                                            return $model->email;
                                        },
                                        'headerOptions' => [
                                            'width' => 250,
                                        ],
                                    ],
                                    [
                                        'attribute' => 'phone',
                                        'value' => 'userProfile.phone',
                                    ],
//                                    'permission_coso',

                                    [
                                        'class' => \common\grid\EnumColumn::class,
                                        'attribute' => 'permission_coso',
//                                        'format' => 'raw',
//                                        'value' => function ($model) {
//                                            return $model->permission_coso;
//                                        },
//                                        'enum' => $listCoSo,
                                        'enum' => $listCoSo,
                                        'filter' => $listCoSo,
                                        'filterInputOptions' => [
                                            'class' => 'ui dropdown form-control'
                                        ],
                                        'value' => 'permission_coso'
                                    ],
                                    [
                                        'class' => \common\grid\EnumColumn::class,
                                        'attribute' => 'status',
                                        'enum' => User::statuses(),
                                        'filter' => User::statuses(),
                                        'filterInputOptions' => [
                                            'class' => 'ui dropdown form-control'
                                        ],
                                        'headerOptions' => [
                                            'width' => 220,
                                        ],
                                    ],
                                    [
                                        'attribute' => 'idpancake',
                                        'format' => 'html',
                                        'value' => function ($model) {
                                            return $model->userProfile->id_pancake . "<br><span class='label-pancake'>" . $model->userProfile->label_pancake . "</span>";
                                        },
                                    ],
                                    [
                                        'class' => \common\grid\EnumColumn::class,
                                        'attribute' => 'role_name',
                                        'enum' => User::roleName(),
                                        'filter' => User::roleName(),
                                        'value' => function ($model) {
                                            $user = new User();
                                            $role = $user->getRoleName($model->id);
                                            return $role;
                                        },
                                        'filterInputOptions' => [
                                            'class' => 'ui dropdown form-control'
                                        ],
                                        'headerOptions' => [
                                            'width' => 250,
                                        ],
                                    ],
                                    [
                                        'class' => \common\grid\EnumColumn::class,
                                        'attribute' => 'team',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return $model->team;
                                        },
                                        'enum' => Yii::$app->controller->module->params['team'],
                                        'filter' => Yii::$app->controller->module->params['team'],
                                        'filterInputOptions' => [
                                            'class' => 'ui dropdown form-control'
                                        ],
                                    ],
                                    'vpbx_acc',
                                    [
                                        'attribute' => 'online',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $time = time() - $model->logged_at;
                                            if ($time < 1800) {
                                                return '<span class="text-primary">Online</span>';
                                            } else {
                                                return 'Offline';
                                            }
                                        }
                                    ],
                                    [
                                        'attribute' => 'created_at',
                                        'value' => function ($model) {
                                            if ($model->created_at != null) {
                                                return Yii::$app->formatter->asDate($model->created_at, 'd-M-Y');
                                            }
                                        }
                                    ],
                                    [
                                        'attribute' => 'logged_at',
                                        'value' => function ($model) {
                                            if ($model->logged_at != null) {
                                                return Yii::$app->formatter->asDate($model->logged_at, 'd-M-Y');
                                            }
                                        },
                                        'headerOptions' => [
                                            'width' => 180,
                                        ],
                                    ],
                                ],
                            ]); ?>
                            <?php Pjax::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$urlIndexPage = \yii\helpers\Url::toRoute(['/site/index']);
$url = \yii\helpers\Url::toRoute(['show-hide']);
$urlDelete = \yii\helpers\Url::toRoute(['delete']);
$urlResetPass = \yii\helpers\Url::toRoute(['reset-password']);
$urlChangePageSize = \yii\helpers\Url::toRoute(['perpage']);
$tit = Yii::t('backend', 'Notification');

$resultSuccess = Yii::$app->params['update-success'];
$resultDanger = Yii::$app->params['update-danger'];

$deleteSuccess = Yii::$app->params['delete-success'];
$deleteDanger = Yii::$app->params['delete-danger'];

$data_title = Yii::t('backend', 'Are you sure?');
$data_text = Yii::t('backend', 'If delete, you will not be able to recover!');
$data_reset = Yii::t('backend', 'Nếu đặt lại mật khẩu cho User, User sẽ không đăng nhập bằng mật khẩu cũ nữa!');

$script = <<< JS
var userUser = new myGridView({
    pjaxId: '#userList',
    urlChangePageSize: '$urlChangePageSize',
});
$('#user-role-name').on('change', function(e) {
    var roleName = $(this).val();
    if(roleName == 'user_le_tan' || roleName == 'user_studio' || roleName == 'user_chup_hinh' || roleName == 'user_tk_nu_cuoi' || roleName == 'user_direct_sale') {
        $('.permisionCoso').slideDown();
    } else {
        $('.permisionCoso').slideUp();
    }
});
$('body').on('beforeSubmit', 'form#form-create_user', function(e){
    e.preventDefault();
    var form = $(this),
    currentUrl = $(location).attr('href'),
    formData = form.serialize();
    
    form.myLoading({
        opacity: true,
        msg: 'Đang xử lý'
    });
       
    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: formData,
        dataType: 'json',
    })
    .done(function(res) {
        if (res.status == 200) {
            toastr.success(res.mess, '$tit');
            $.when($.pjax.reload({url: currentUrl, method: 'POST', container: userUser.options.pjaxId})).done(function(){
                $('.modal-header').find('.close').trigger('click');
                form.myUnloading();
            });
        } else {
            toastr.error(res.mess, '$tit');
            form.myUnloading();
        }
    })
    .fail(function(err){
        form.myUnloading();
        console.log(err);
    });
   
   return false;
}).on('click', '.btn-login-with-user', function(e){
    e.preventDefault();
    var data_user = $(this).attr('data-user'),
        url = $(this).attr('href'),
        c = confirm('Bạn muốn đăng nhập vào tài khoản '+ data_user +'?');
    if(c){
        $('#dom').myLoading({
            opacity: true,
            size: 'lg',
            msg: 'Đăng nhập vào tài khoản '+ data_user
        });
        $.ajax({
            type: 'GET',
            url: url,
            dataType: 'json',
            data: {}
        }).done(function(res){
            if(res.code === 200){
                toastr.success(res.msg, 'Thông báo', {
                    onHidden: function(){
                        window.location.href = "$urlIndexPage";
                    }
                });
            } else {
                toastr.warning(res.msg, 'Thông báo');
                $('#dom').myUnloading();
            }
        }).fail(function(f){
            toastr.error('Có lỗi xảy ra.', 'Thông báo');
            $('#dom').myUnloading();
        });
    }
    return false;
});
$(document).ready(function () {
    $('body').on('change', '.check-toggle', function () {
        var id = $(this).val();
        $.post('$url', {id: id}, function (result) {
            if(result == 1) {
                toastr.success('$resultSuccess', '$tit');
            }
            if(result == 0) {
                toastr.error('$resultDanger', '$tit');
            }
        });
    });
    $('body').on('click', '.reset-password', function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        Swal.fire({
          title: 'Bạn có muốn thay đổi mật khẩu?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Vâng, tôi muốn!'
        }).then((result) => {
          if (result.value) {
              $.ajax({
                type: "POST",
                cache: false,
                data:{"id":id},
                url: "$urlResetPass",
                dataType: "json",
                success: function(data){
                    if(data.status == 'success') {
                        Swal.fire(
                          'Đặt lại mật khẩu!',
                          "Thành công. Mật khẩu mới là: " + data.pass,
                          'success'
                        );
                    }
                    if(data.status == 'failure' || data.status == 'exception')
                        Swal.fire(
                          'Đặt lại mật khẩu!',
                          "Thất bại, hãy liên hệ bộ phận kỹ thuật.",
                          'error'
                        );
                }
              });
          }
        });
    });
    
    $('body').on('click', '.confirm-color', function (e) {
        e.preventDefault();
        var currentUrl = $(location).attr('href');
        var id = $(this).attr("data-id");
        Swal.fire({
          title: 'Bạn có chắc muốn xoá?',
          text: "Bạn sẽ không khôi phục lại được!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Vâng, xoá nó!'
        }).then((result) => {
          if (result.value) {
              $.ajax({
                type: "POST",
                cache: false,
                data:{"id":id},
                url: "$urlDelete",
                dataType: "json",
                success: function(data){
                    if(data.status == 'success') {
                        toastr.success('$deleteSuccess', '$tit');
                        $.pjax.reload({url: currentUrl, method: 'POST', container: userUser.options.pjaxId});
                    }
                    if(data.status == 'failure' || data.status == 'exception')
                        toastr.error('Xoá không thành công', 'Thông báo');
                }
              });
          }
        });
    });
});
JS;

$this->registerJs($script, \yii\web\View::POS_END);
?>

