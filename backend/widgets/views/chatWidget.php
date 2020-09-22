<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

/* @var $commentTable string */
/* @var $listComment array */
/* @var $formComment ActiveForm */

?>
    <div class="comment-content">
        <h2>Chat Inbox</h2>
        <?php Pjax::begin([
            'id' => 'pjax-comment',
            'timeout' => false,
            'enablePushState' => false,
            'clientOptions' => ['method' => 'GET']
        ]); ?>
        <div class="comment-list">
            <?php
            if (is_array($listComment) && count($listComment) > 0) {
                foreach ($listComment as $comment) {
                    $user_comment = $comment->createdByHasOne->userProfile->fullname != null ? $comment->createdByHasOne->userProfile->fullname : $comment->createdByHasOne->username; ?>
                    <div class="comment<?= $comment->created_by == Yii::$app->user->id ? ' mine' : '' ?>">
                        <div class="comment-avt">
                            <img class="img-fluid" src="/images/default/avatar-default.png"
                                 alt="<?= $user_comment ?>"
                                 title="<?= $user_comment ?>">
                        </div>
                        <div class="comment-info">
                            <div class="comment-detail">
                                <span class="comment-user"><?= $user_comment ?></span>
                                <span class="comment-time">(<?= date('h:i d-m-Y',
                                        $comment->created_at) ?>)</span>
                            </div>
                            <div class="comment-text"><?= $comment->comment ?></div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        <?php Pjax::end() ?>
        <?php if ($model->primaryKey != null && $formComment != null) { ?>
            <div class="comment-form">
                <?php $form_comment = ActiveForm::begin([
                    'id' => 'form-comment',
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => true,
                    'validationUrl' => Url::toRoute([
                        '/chi/comment/validate-comment',
                        'id' => $model->primaryKey,
                        'commentTable' => $commentTable
                    ]),
                    'action' => Url::toRoute([
                        '/chi/comment/submit-comment',
                        'id' => $model->primaryKey,
                        'commentTable' => $commentTable
                    ])
                ]) ?>
                <?= $form_comment->field($formComment, 'comment')->textarea([
                    'class' => 'form-control',
                    'rows' => 6,
                    'placeHolder' => $formComment->getAttributeLabel('comment')
                ])->label(false) ?>
                <?= Html::submitButton('Bình luận', ['class' => 'btn btn-primary']) ?>
                <?php ActiveForm::end() ?>
            </div>
        <?php } ?>
    </div>
<?php
$script = <<< JS
if ($(".comment-list").length > 0) $(".comment-list").scrollTop($(".comment-list")[0].scrollHeight);
var formComment = $('#form-comment');
formComment.on('beforeSubmit', function (e) {
    e.preventDefault();
    var form = $(this),
        url = form.attr('action'),
        form_data = new FormData(form[0]);
    form.myLoading({
        opacity: true
    });
    $.ajax({
        type: 'POST',
        url: url,
        dataType: 'json',
        data: form_data,
        contentType: false,
        processData: false,
        cache: false
    }).done(res => {
        if (res.code == 200) {
            toastr.success(res.msg);
            form[0].reset();
            $.pjax.reload({container: '#pjax-comment', url: window.location.href}).then(function () {
                if ($(".comment-list").length > 0) $(".comment-list").scrollTop($(".comment-list")[0].scrollHeight);
            });
        } else {
            toastr.error(res.msg);
        }
        form.myUnloading();
    }).fail(f => {
        toastr.error('Có lỗi khi bình luận');
        form.myUnloading();
    });
    return false;
});
JS;
$this->registerJs($script, \yii\web\View::POS_END);