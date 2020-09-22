<?php

use backend\modules\labo\models\LaboGiaiDoan;
use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model backend\modules\labo\models\LaboGiaiDoan */

if ($model->isNewRecord) {
    $this->title = Yii::t('backend', 'Tạo mới công đoạn làm Labo');
} else {
    $this->title = Yii::t('backend', 'Công đoạn làm Labo');
}
//$this->params['breadcrumbs'][] = ['label' => 'Labo Giai Doans', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
$avatarDefault = Url::to('@web/local') . '/default/avatar-default.png';

?>
<section id="dom">
    <div class="row">
        <div class="col-12">
            <?php
            if (Yii::$app->session->hasFlash('alert')) {
                ?>
                <div class="alert <?= Yii::$app->session->getFlash('alert')['class']; ?> alert-dismissible"
                     role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <?= Yii::$app->session->getFlash('alert')['body']; ?>
                </div>
                <?php
            }
            ?>
            <div class="content-body">
                <div class="row p-1">
                    <div class="col-lg-6 col-12">


                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <?php
                                    if (Yii::$app->user->can(User::USER_LE_TAN)):
                                        ?>
                                        <a href="<?= Url::toRoute(['labo-don-hang/create-don', 'don_id' => $phong_kham_don_hang_id]) ?>"
                                           class='btn btn-primary'> <i class="fa fa-backward"></i> Phiếu Labo
                                        </a>
                                    <?php endif; ?>

                                    <?php
                                    if (Yii::$app->user->can(User::USER_KY_THUAT_LABO)):
                                        ?>
                                        <a href="<?= Url::toRoute(['labo-don-hang/quan-ly-cong-doan', 'id' => $labo_don_hang_id]) ?>"
                                           class='btn btn-primary'> <i class="fa fa-backward"></i> Danh sách
                                        </a>
                                    <?php endif; ?>

                                    <?= $this->title; ?>
                                </h4>
                                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                        <li><a class="block-page"
                                               onclick='window.location="<?= \Yii::$app->getRequest()->getUrl(); ?>"'><i
                                                        class="ft-rotate-cw"></i></a></li>
                                        <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                        <li><a data-action="close"><i class="ft-x"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body card-dashboard">

                                    <div class="labo-giai-doan-form ">

                                        <?php $form = ActiveForm::begin(); ?>
                                        <div class="form-actions ">
                                            <?php
                                            if ($model->isNewRecord):
                                                ?>
                                                <?= $form->field($model, 'giai_doan')->dropDownList($model::getListGiaiDoan()) ?>

                                            <?php
                                            else:
                                                ?>
                                                <?= $form->field($model, 'giai_doan')->dropDownList($model::getListGiaiDoan(), ['disabled' => 'disabled']) ?>

                                            <?php
                                            endif;
                                            ?>

                                            <?= $form->field($model, 'labo_don_hang_id')->hiddenInput()->label(false) ?>

                                            <?= $form->field($model, 'note')->textarea(['rows' => 3]) ?>


                                            <?php
                                            if (Yii::$app->user->can(User::USER_LE_TAN)):
                                                echo $form->field($model, 'status')->radioList(LaboGiaiDoan::getListStatus());
                                            endif;
                                            ?>

                                        </div>

                                        <div class="form-actions">
                                            <?= Html::submitButton('<i class="fa fa-check-square-o"></i> Lưu',
                                                ['class' => 'btn btn-primary']) ?>
                                        </div>

                                        <?php ActiveForm::end(); ?>


                                    </div>

                                </div>
                            </div>

                            <?php
                            if (!$model->isNewRecord):
                                ?>
                                <div class="card-content">
                                    <div class="card-body card-dashboard">

                                        <?= $this->render('_index_labo_feedback', [
                                            'labo_giai_doan_id' => $model->id,
                                        ]) ?>

                                    </div>
                                </div>
                            <?php
                            endif;
                            ?>

                        </div>

                    </div>

                    <div class="col-lg-6 col-12">

                        <?php
                        if (!$model->isNewRecord):
                            ?>
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Hình ảnh công đoạn làm labo</h4>
                                    <a class="heading-elements-toggle"><i
                                                class="fa fa-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                            <li><a class="block-page"
                                                   onclick='window.location="<?= \Yii::$app->getRequest()->getUrl(); ?>"'><i
                                                            class="ft-rotate-cw"></i></a></li>
                                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                            <li><a data-action="close"><i class="ft-x"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-content collapse show">
                                    <div class="card-body card-dashboard">


                                        <div class="card-body card-dashboard">


                                            <?php $form = ActiveForm::begin([
                                                'action' => Url::toRoute('labo-giai-doan-image/create'),
                                            ]); // ['options' => ['enctype' => 'multipart/form-data']]
                                            ?>
                                            <div class="form-actions">
                                                <?= $form->field($modelGiaiDoanImage, 'labo_giai_doan_id')->hiddenInput(['value' => $model->id])->label(false) ?>

                                                <?= $form->field($modelGiaiDoanImage, 'image')->hiddenInput()->label(false) ?>
                                                <?= $form->field($modelGiaiDoanImage, 'google_id')->hiddenInput()->label(false) ?>

                                                <input type="hidden" value="1" name="another_page">
                                                <?php
                                                echo $form->field($modelGiaiDoanImage, 'imageFile')->fileInput();
                                                ?>

                                                <?= $form->field($modelGiaiDoanImage, 'status')->hiddenInput(['value' => 1])->label(false) ?>
                                            </div>
                                            <div class="form-actions">
                                                <?= Html::submitButton('<i class="fa fa-check-square-o"></i> Lưu Hình Ảnh',
                                                    ['class' => 'btn btn-primary']) ?>
                                            </div>

                                            <?php ActiveForm::end(); ?>



                                            <?= $this->render('_index_giai_doan_image', [
                                                'dataProvider' => $dataProvider,
                                                'totalPage' => $totalPage,
                                            ]) ?>

                                        </div>


                                    </div>
                                </div>
                            </div>

                        <?php
                        endif; // isNewRecord
                        ?>


                    </div>

                </div>


            </div>
        </div>
    </div>

</section>

<?php
$script = <<< JS
function loadElement(el, url, callback = function(){}){
    el.myLoading().load(url, {}, function(){
        el.myUnloading();
        if(typeof callback == "function") callback();
    });
}

$(window).ready(function(){
    $('.load-data').each(function(){
        var el = $(this),
        url_load = el.attr('url-load') || null;
        if(url_load != null){
            loadElement(el, url_load);
        }
    });
});

JS;
$this->registerJs($script, \yii\web\View::POS_END);


?>



