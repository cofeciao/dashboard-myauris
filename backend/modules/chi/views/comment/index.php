<?php

use yii\widgets\Pjax;

$css = <<< CSS
.comment.mine {
    text-align: right;
}
CSS;
$this->registerCss($css);

Pjax::begin(['id' => 'pjax-comments', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]);
?>
<div class="comments">
    <?php
    if ($listComments != null && is_array($listComments)) {
        foreach ($listComments as $comment) {
            ?>
            <div class="comment<?= $comment->created_by == Yii::$app->user->id ? ' mine' : '' ?>">
                <div class="user">
                    <?= $comment->createdByHasOne->userProfile->fullname ?> said:
                    <span style="font-size: 12px; font-style: italic; color: #999;">(<?= date('d-m-Y', $comment->created_at) ?>)</span>
                </div>
                <b><?= $comment->comment ?></b>
            </div>
            <?php
        }
    }
    ?>
</div>
<?php
Pjax::end();

echo $this->render('_form', [
    'model' => $model
]) ?>
