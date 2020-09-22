<?php

use backend\modules\labo\models\LaboFeedback;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use \common\models\User;

$mLaboFeedback = new LaboFeedback();
$mLaboFeedback->labo_giai_doan_id = $labo_giai_doan_id;
?>
<?php $form = ActiveForm::begin([
    'action' => Url::toRoute('labo-feedback/create'),
]); // ['options' => ['enctype' => 'multipart/form-data']]
?>
<div class="row">

    <div class="col-10">
        <?= $form->field($mLaboFeedback, 'labo_giai_doan_id')->hiddenInput()->label(false) ?>

        <?php
        echo $form->field($mLaboFeedback, 'content')->textarea()->label(false);
        ?>
        <input type="hidden" value="1" name="another_page">

        <?= $form->field($mLaboFeedback, 'status')->hiddenInput(['value' => 1])->label(false) ?>
    </div>
    <div class="col-2">
        <?= Html::submitButton('<i class="fa fa-check-square-o  "></i> Đánh giá',
            ['class' => 'btn btn-primary mt-1']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<div class="row">
    <div class="col-12">
        <?php
        $listMessage = LaboFeedback::getListFeedback($labo_giai_doan_id);
        foreach ($listMessage as $item):
            ?>
        <div class="row">
            <div class="col-9">
                <div class="alert alert-warning mt-1" role="alert">
                    <?= $item->content ?>
                    <?php
                    if(Yii::$app->user->can(User::USER_DEVELOP)):
                    ?>
                        <a href="<?= Url::toRoute(['labo-feedback/delete-one','id' => $item->id ]) ?>"><i class="fa fa-close pull-right"></i></a>
                    <?php
                    endif;
                    ?>
                </div>
            </div>
            <div class="col-3 mt-1">
                <?= $item->getUserCreatedBy($item->created_by)->fullname ?><br>
                <i><?= date('H:i:s d-m-Y ', $item->created_at) ?></i>
            </div>
        </div>
        <?php
        endforeach;
        ?>
    </div>
</div>
