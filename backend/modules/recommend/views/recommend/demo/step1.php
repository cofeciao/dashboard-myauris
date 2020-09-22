<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\recommend\models\Recommend */

$this->title = Yii::t('backend', 'Thực hiện tư vấn');
$this->params['breadcrumbs'][] = ['label' => 'Recommends', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section id="dom">
    <div class="row">
        <div class="col-12">
            <?php
            if (Yii::$app->session->hasFlash('alert')) {
                ?>
                <div class="alert <?=  Yii::$app->session->getFlash('alert')['class']; ?> alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <?=  Yii::$app->session->getFlash('alert')['body']; ?>
                </div>
                <?php
            }
            ?>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><?=  $this->title; ?></h4>
                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a class="block-page"
                                   onclick='window.location="<?=  \Yii::$app->getRequest()->getUrl(); ?>"'><i
                                            class="ft-rotate-cw"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            <li><a data-action="close"><i class="ft-x"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body card-dashboard">


                        <div class="btn-add-campaign clearfix" style="margin-top:0px;position:relative">
                            <?=  Html::a('<i class="fa fa-plus"> Master Data</i>', ['index'], ['title' => 'Thêm mới', 'data-pjax' => 0, 'class' => 'btn btn-default pull-left']) ?>
                        </div>


                        <div class="recommend-form">

                            <?php $form = ActiveForm::begin(); ?>
                            <div class="form-actions">
                                <!--        --><?php //echo $form->field($model, 'gioi_tinh')->textInput(['maxlength' => true]); ?>

                                <?php echo $form->field($model, 'gioi_tinh')->radioList($model::getListGioiTinh()); ?>

                                <?php
                                echo $form->field($model, 'nhom_tuoi')->radioList($model::getListNhomTuoi());
                                ?>

                                <?php
                                echo $form->field($model, 'bo_cuc')->radioList($model::getListBoCuc());
                                ?>

                                <?php
                                echo $form->field($model, 'tinh_trang_rang')->checkboxList($model::getListTinhTrangRang());
                                ?>

                                <?php
                                echo $form->field($model, 'mong_muon')->checkboxList($model::getListMongMuon());
                                ?>

                                <?php
                                echo $form->field($model, 'phong_cach')->checkboxList($model::getListPhongCach());
                                ?>

                                <?php
                                // echo $form->field($model, 'giai_phap')->checkboxList($model::getListGiaiPhap());
                                ?>

                            </div>
                            <div class="form-actions">
                                <?= Html::resetButton('<i class="ft-x"></i> Cancel', ['class' =>
                                    'btn btn-warning mr-1']) ?>
                                <?= Html::submitButton('<i class="fa fa-check-square-o"></i> Giải pháp' ,
                                    ['class' => 'btn btn-primary']) ?>
                            </div>

                            <?php ActiveForm::end(); ?>

                        </div>






                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
