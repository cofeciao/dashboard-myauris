<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\support\models\Support */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Supports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="modal-header bg-blue-grey bg-lighten-2 white">
    <h4 class="modal-title"><?= $this->title; ?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <?= DetailView::widget([
        'model' => $model,
        'options' => ['class' => 'detail1-view table table-striped table-bordered detail-view'],
        'attributes' => [
            'name',
            [
                'attribute' => 'catagory_id',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->supportCatagoryHasOne->name;
                }
            ],
            [
                'attribute' => 'question',
                'format' => 'raw',
                'value' => 'question'
            ],
            [
                'attribute' => 'anwser',
                'format' => 'raw',
                'contentOptions' => [
                    'class' => 'td-answer'
                ]
            ],
            'created_at:datetime',
            'updated_at:datetime',
            [
                'attribute' => 'created_by',
                'value' => function ($model) {
                    $user = new backend\modules\support\models\Support();
                    $userCreatedBy = $user->getUserCreatedBy($model->created_by);
                    return $userCreatedBy->fullname;
                }
            ],
            [
                'attribute' => 'updated_by',
                'value' => function ($model) {
                    $user = new backend\modules\support\models\Support();
                    $userCreatedBy = $user->getUserCreatedBy($model->updated_by);
                    return $userCreatedBy->fullname;
                }
            ],
            [
                'attribute' => 'users_view',
                'value' => function ($model) {
                    $users_view = \backend\modules\support\models\Support::getListUsersView($model->users_view);
                    if (!is_array($users_view) || count($users_view) <= 0) {
                        return null;
                    }
                    $users = '';
                    foreach ($users_view as $user_view) {
                        if ($user_view != null) {
                            $user = '';
                            if (isset($user_view->userProfile)) {
                                $user = $user_view->userProfile->fullname;
                            } else {
                                $user = $user_view->username;
                            }
                            if ($user != '') {
                                if ($users != '') {
                                    $users .= ', ';
                                }
                                $users .= $user;
                            }
                        }
                    }
                    if ($users == '') {
                        return null;
                    }
                    return $users;
                }
            ]
        ],
    ]) ?>
</div>
<div class="modal-footer p-0"></div>

<?php
$urlUpdateView = \yii\helpers\Url::toRoute(['update-view', 'id' => $model->primaryKey, 'user' => Yii::$app->user->id]);
$this->registerCss("ol li, ul li, dl li {line-height: 1.4}");
if (!is_array($model->users_view)) {
    $model->users_view = [];
}
$updateView = in_array(Yii::$app->user->id, $model->users_view) ? 'false' : 'true';
$time = $model->time != null ? $model->time : 0;
$script = <<< JS
var dataUpdateView = $updateView,
    update = true,
    time = $time,
    scroll_top = false,
    endTime = false,
    timeout;
function updateView(){
    if(update == true && endTime == true && (scroll_top == true || ($('#custom-modal')[0].scrollTop >= ($('#custom-modal')[0].scrollHeight - $('#custom-modal')[0].offsetHeight)))){
        console.log('run update');
        $.get('$urlUpdateView', {}, function(res){
            if(res.code !== 200) {
                update = true;
                dataUpdateView = true;
                console.log('update failed');
            }
        }, 'json');
        update = false;
    }
}
$('body').find('#custom-modal').unbind('scroll');
if(dataUpdateView == true){
    $('body').find('#custom-modal').bind('scroll', function(){
        if(dataUpdateView == false) return false;
        var answer = $('#custom-modal .td-answer'),
            top = answer.position().top,
            height = answer.outerHeight();
        if($(this).scrollTop() + $(window).height() >= top + height){
            dataUpdateView = false;
            console.log('top ok');
            scroll_top = true;
            updateView();
        }
    });
}
if(time != 0){
    timeout = setTimeout(function(){
        endTime = true;
        console.log('end time');
        updateView();
    }, time * 60000);
    $('#custom-modal').on('hidden.bs.modal', function () {
        clearTimeout(timeout);
    })
}
JS;
$this->registerJs($script);
?>
