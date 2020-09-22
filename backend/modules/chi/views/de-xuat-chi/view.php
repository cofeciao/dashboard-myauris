<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use \backend\modules\chi\models\DeXuatChi;
use backend\modules\user\models\UserSubRole;

/* @var $this yii\web\View */
/* @var $model backend\modules\chi\models\DeXuatChi */
/* @var $formComment backend\modules\chi\models\Comment */
/* @var $listHoSo array */
/* @var $listComment array */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'De Xuat Chi'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$css = <<<CSS
.app-content:before{
content:unset !important;
}
CSS;


$this->registerCss($css);
\backend\modules\chi\assets\DexuatchiAssets::register($this);

?>
    <div class="modal-header bg-blue-grey bg-lighten-2 white">
        <h4 class="modal-title"><?= $this->title; ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body view-order-customer view-de-xuat-chi">
        <div class="detail-de-xuat-chi clearfix mb-1">
            <?= DetailView::widget([
                'model' => $model,
                'options' => ['class' => 'detail1-view table table-striped table-bordered detail-view'],
                'attributes' => [
                    'id',
                    [
                        'attribute' => 'created_by',
                        'format' => 'raw',
                        'label' => 'Người đề xuất',
                        'value' => function ($model) {
                            if ($model->nguoidexuatHasOne == null) {
                                return '--';
                            }
                            return $model->nguoidexuatHasOne->fullname;
                        }
                    ],
                    [
                        'attribute' => 'nguoi_trien_khai',
                        'format' => 'raw',
                        'value' => function ($model) {
                            if ($model->nguoitrienkhaiHasOne == null) {
                                return '--';
                            }
                            return $model->nguoitrienkhaiHasOne->fullname;
                        }
                    ],
                    [
                        'attribute' => 'so_tien_chi',
                        'format' => 'html',
                        'value' => function ($model) {
                            if ($model->so_tien_chi == null) {
                                return null;
                            }
                            return number_format($model->so_tien_chi, 0);
                        }
                    ],
                    ['attribute' => 'thoi_han_thanh_toan', 'format' => ['datetime', 'php:d-m-Y H:m']],
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'value' => function ($model) {
                            if (!array_key_exists($model->status, DeXuatChi::STATUS)) {
                                return null;
                            }
                            return DeXuatChi::STATUS[$model->status];
                        },
                    ],
                    'leader_accept',
                    [
                        'attribute' => 'leader_accept_at',
                        'format' => ['datetime', 'php:d-m-Y H:m'],

                    ],
                    'accountant_accept',
                    [
                        'attribute' => 'accountant_accept_at',
                        'format' => ['datetime', 'php:d-m-Y H:m'],

                    ],
                    [
                        'attribute' => 'tieu_chi_group',
                        'format' => 'html',
                        'value' => function ($model) {
                            $format = $this->context->renderTieuChiView($model->tieu_chi_group);
                            return $format;
                        }
                    ],
                    [
                        'attribute' => 'tp_status',
                        'value' => function ($model) {
                            return DeXuatChi::TP_STATUS[$model->tp_status];
                        }
                    ],
                    [
                        'attribute' => 'created_by',
                        'value' => function ($model) {
                            $user = new backend\modules\chi\models\DeXuatChi();
                            $userCreatedBy = $user->getUserCreatedBy($model->created_by);
                            if ($userCreatedBy == null) {
                                return '--';
                            } else {
                                return $userCreatedBy->fullname;
                            }
                        }
                    ],
                    [
                        'attribute' => 'updated_by',
                        'value' => function ($model) {
                            $user = new backend\modules\chi\models\DeXuatChi();
                            $userCreatedBy = $user->getUserCreatedBy($model->updated_by);
                            if ($userCreatedBy == null) {
                                return '--';
                            } else {
                                return $userCreatedBy->fullname;
                            }
                        }
                    ],

                ],
            ]) ?>
        </div>
        <?php if (is_array($listHoSo)) { ?>
            <div class="ho-so">
                <div class="cc-block cc-single">
                    <div class="ccb-header">
                        <div class="ccbh-title">Hồ sơ liên quan</div>
                    </div>
                    <div class="ccb-content">
                        <div class="g-list-file">
                            <?php foreach ($listHoSo as $hoSo) { ?>
                                <div class="g-file">
                                    <div class="form-group has-image">
                                        <span class="review">
                                            <?php
                                            $explode = explode('.', $hoSo->file);

                                            if (in_array($explode[count($explode) - 1], ['jpg', 'jpeg', 'png'])):
                                                ?>
                                                <img class="hoso-image" src="<?= '/uploads/ho-so/' . $hoSo->file ?>">
                                            <?php else: ?>
                                                <a target="_blank" href="<?= '/uploads/ho-so/' . $hoSo->file ?>">
                                                    <img class="hoso-file"
                                                         src="<?= \backend\modules\chi\models\form\FormHoSo::getUrlIconByExt($explode[count($explode) - 1]) ?>">
                                                    <?= $hoSo->file ?>
                                                </a>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="comment-content">
            <h2>Chat Inbox</h2>
            <?php if (is_array($listComment) && count($listComment) > 0) { ?>
                <div class="comment-list">
                    <?php
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
                                    <span class="comment-time">(<?= date('h:i d-m-Y', $comment->created_at) ?>)</span>
                                </div>
                                <div class="comment-text"><?= $comment->comment ?></div>
                            </div>
                        </div>
                        <?php
                    } ?>
                </div>
            <?php } ?>
            <?php if ($model->primaryKey != null && $formComment != null) { ?>
                <div class="comment-form">
                    <?php $form_comment = ActiveForm::begin(['id' => 'form-comment',
                        'enableAjaxValidation' => true,
                        'enableClientValidation' => true,
                        'validationUrl' => Url::toRoute(['/chi/comment/validate-comment', 'id' => $model->primaryKey]),
                        'action' => Url::toRoute(['/chi/comment/submit-comment', 'id' => $model->primaryKey])]) ?>
                    <?= $form_comment->field($formComment, 'comment')->textarea(['class' => 'form-control',
                        'rows' => 6,
                        'placeHolder' => $formComment->getAttributeLabel('comment')])->label(false) ?>
                    <?= Html::submitButton('Bình luận', ['class' => 'btn btn-primary']) ?>
                    <?php ActiveForm::end() ?>
                </div>
            <?php } ?>
        </div>
    </div>

<?php


$this->registerCssFile('/vendors/plugins/fancybox/dist/jquery.fancybox.css', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile('/vendors/plugins/fancybox/dist/jquery.fancybox.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$urlReloadPjax = Url::toRoute(['view', 'id' => $model->id]);


$tit = Yii::t('backend', 'Notification');

$resultSuccess = Yii::$app->params['update-success'];
$resultDanger = Yii::$app->params['update-danger'];

$deleteSuccess = Yii::$app->params['delete-success'];
$deleteDanger = Yii::$app->params['delete-danger'];

$data_title = Yii::t('backend', 'Are you sure?');
$data_text = Yii::t('backend', '');


$script = <<< JS
var tit = '$tit'

var resultSuccess ='$resultSuccess' 
var resultDanger='$resultDanger' 

var deleteSuccess= '$deleteSuccess' 
var deleteDanger='$deleteDanger'

var data_title= '$data_title' 
var data_text = '$data_text'

var urlReloadPjax = '$urlReloadPjax';
JS;
$this->registerJs($script, \yii\web\View::POS_HEAD);
