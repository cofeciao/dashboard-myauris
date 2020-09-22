<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$avatarDefault = Url::to('@web/local') . '/default/avatar-default.png';
$avatar = ($formAvatar->fileImage != null && file_exists(Yii::$app->basePath . '/web/uploads/avatar/70x70/' . $formAvatar->fileImage)) ? Url::to('@web/uploads') . '/avatar/70x70/' . $formAvatar->fileImage : $avatarDefault;

?>
    <section id="dom" class="cls-dom">
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
                <div class="card">
                    <div class="card-content collapse show" id="content-import-customer">
                        <div class="card-body card-dashboard">
                            <div class="customer-content import-customer-content">
                                <div class="header-top">
                                    <h4>Chụp hình khách hàng: <?= $customer->full_name; ?></h4>
                                </div>
                                <?php
                                $form = ActiveForm::begin([
                                    'id' => 'form-update-customer',
                                    'action' => Url::toRoute(['upload-avatar', 'id' => $customer->primaryKey])
                                ]);
                                ?>
                                <div class="cc-block">
                                    <div class="ccb-header">
                                        <div class="ccbh-title">Thông tin khách hàng</div>
                                    </div>
                                    <div class="ccb-content">
                                        <div class="c-content">
                                            <div class="c-avatar m-0">
                                                <div class="customer-avatar">
                                                    <div class="avatar-upload">
                                                        <img src="<?= $avatar ?>" class="img-avatar"/>
                                                        <div class="avatar-upload-icon">
                                                            <i class="fa fa-upload"></i>
                                                        </div>
                                                    </div>
                                                    <div class="avatar-text">
                                                        <p class="avatar-text-title">Upload avatar</p>
                                                        <p class="avatar-text-note">(Tối đa 5Mb)</p>
                                                    </div>
                                                    <div class="hidden">
                                                        <?= $form->field($formAvatar, 'fileImage')->fileInput(['class' => 'ipt-avatar'])->label(false) ?>
                                                        <?= $form->field($formAvatar, 'id')->textInput()->label(false) ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="c-info">
                                                <p>Khách hàng: <?= $customer->full_name; ?></p>
                                                <p>Mã khách hàng: <?= $customer->customer_code ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php ActiveForm::end() ?>

                                <!-- CHUP HINH -->
                                <?php
                                $form = ActiveForm::begin([
                                    'id' => 'form-chup-hinh',
                                    'options' => [
                                        'class' => 'form-upload',
                                        'form-name' => 'FormChupHinh'
                                    ],
                                    'action' => Url::toRoute(['/clinic/chup-hinh/upload-image', 'id' => $customer->primaryKey]),
                                ]);
                                ?>
                                <div class="cc-block">
                                    <div class="ccb-header">
                                        <div class="ccbh-title">Hình chụp thăm khám</div>
                                        <div class="ccbh-edit" style="display: none;">
                                            <button type="button" class="btn btn-sm btn-danger remove-image">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="g-download-list">
                                            <button type="button" class="btn btn-primary">Tải về <span>1</span> hình
                                            </button>
                                        </div>
                                    </div>
                                    <div class="ccb-content">
                                        <?= $form->field($formChupHinh, 'fileImage')->fileInput(['multiple' => 'multiple', 'class' => 'btn-upload hidden'])->label(false) ?>
                                        <div class="g-list-file">
                                            <?php
                                            if (count($listChupHinh) > 0) {
                                                foreach ($listChupHinh as $file) {
                                                    ?>
                                                    <div class="g-file">
                                                        <div>
                                                            <div class="g-tools">
                                                                <span>
                                                                    <a class="g-download g-image"
                                                                       data-image="<?= $file['webContentLink'] ?>"
                                                                       data-id="<?= $file['id'] ?>"
                                                                       data-title="<?= $customer->full_name ?>"
                                                                       data-type="<?= $file['type'] ?>">
                                                                        <i class="fa fa-download"></i>
                                                                    </a>
                                                                </span>
                                                                <span>
                                                                    <a class="g-check">
                                                                        <label class="square-checkbox">
                                                                            <input type="checkbox">
                                                                            <span></span>
                                                                        </label>
                                                                    </a>
                                                                </span>
                                                            </div>
                                                            <div class="g-view">
                                                                <i class="fa fa-search"></i>
                                                            </div>
                                                            <img class="g-thumb" src="<?= $file['thumbnailLink'] ?>">
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                            } ?>
                                            <div class="g-btn-upload">
                                                <div class="btn-upload-tmp"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php ActiveForm::end() ?>
                                <!-- END CHUP HINH -->

                                <!-- CHUP BANH MOI -->
                                <?php
                                $form = ActiveForm::begin([
                                    'id' => 'form-chup-banh-moi',
                                    'options' => [
                                        'class' => 'form-upload',
                                        'form-name' => 'FormChupBanhMoi'
                                    ],
                                    'action' => Url::toRoute(['/clinic/chup-banh-moi/upload-image', 'id' => $customer->primaryKey]),
                                ]);
                                ?>
                                <div class="cc-block">
                                    <div class="ccb-header">
                                        <div class="ccbh-title">Chụp banh môi</div>
                                        <div class="ccbh-edit" style="display: none;">
                                            <button type="button" class="btn btn-sm btn-danger remove-image">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="g-download-list">
                                            <button type="button" class="btn btn-primary">Tải về <span>1</span> hình
                                            </button>
                                        </div>
                                    </div>
                                    <div class="ccb-content">
                                        <?= $form->field($formChupBanhMoi, 'fileImage')->fileInput(['multiple' => 'multiple', 'class' => 'btn-upload hidden'])->label(false) ?>
                                        <div class="g-list-file">
                                            <?php
                                            if (count($listChupBanhMoi) > 0) {
                                                foreach ($listChupBanhMoi as $file) {
                                                    ?>
                                                    <div class="g-file">
                                                        <div>
                                                            <div class="g-tools">
                                                                <span>
                                                                    <a class="g-download g-image"
                                                                       data-image="<?= $file['webContentLink'] ?>"
                                                                       data-id="<?= $file['id'] ?>"
                                                                       data-title="<?= $customer->full_name ?>"
                                                                       data-type="<?= $file['type'] ?>">
                                                                        <i class="fa fa-download"></i>
                                                                    </a>
                                                                </span>
                                                                <span>
                                                                    <a class="g-check">
                                                                        <label class="square-checkbox">
                                                                            <input type="checkbox">
                                                                            <span></span>
                                                                        </label>
                                                                    </a>
                                                                </span>
                                                            </div>
                                                            <div class="g-view">
                                                                <i class="fa fa-search"></i>
                                                            </div>
                                                            <img class="g-thumb" src="<?= $file['thumbnailLink'] ?>">
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                            } ?>
                                            <div class="g-btn-upload">
                                                <div class="btn-upload-tmp"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php ActiveForm::end() ?>
                                <!-- END CHUP BANH MOI -->

                                <!-- CHUP CUI -->
                                <?php
                                $form = ActiveForm::begin([
                                    'id' => 'form-chup-cui',
                                    'options' => [
                                        'class' => 'form-upload',
                                        'form-name' => 'FormChupCui'
                                    ],
                                    'action' => Url::toRoute(['/clinic/chup-cui/upload-image', 'id' => $customer->primaryKey]),
                                ]);
                                ?>
                                <div class="cc-block">
                                    <div class="ccb-header">
                                        <div class="ccbh-title">Chụp cùi</div>
                                        <div class="ccbh-edit" style="display: none;">
                                            <button type="button" class="btn btn-sm btn-danger remove-image">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="g-download-list">
                                            <button type="button" class="btn btn-primary">Tải về <span>1</span> hình
                                            </button>
                                        </div>
                                    </div>
                                    <div class="ccb-content">
                                        <?= $form->field($formChupCui, 'fileImage')->fileInput(['multiple' => 'multiple', 'class' => 'btn-upload hidden'])->label(false) ?>
                                        <div class="g-list-file">
                                            <?php
                                            if (count($listChupCui) > 0) {
                                                foreach ($listChupCui as $file) {
                                                    ?>
                                                    <div class="g-file">
                                                        <div>
                                                            <div class="g-tools">
                                                                <span>
                                                                    <a class="g-download g-image"
                                                                       data-image="<?= $file['webContentLink'] ?>"
                                                                       data-id="<?= $file['id'] ?>"
                                                                       data-title="<?= $customer->full_name ?>"
                                                                       data-type="<?= $file['type'] ?>">
                                                                        <i class="fa fa-download"></i>
                                                                    </a>
                                                                </span>
                                                                <span>
                                                                    <a class="g-check">
                                                                        <label class="square-checkbox">
                                                                            <input type="checkbox">
                                                                            <span></span>
                                                                        </label>
                                                                    </a>
                                                                </span>
                                                            </div>
                                                            <div class="g-view">
                                                                <i class="fa fa-search"></i>
                                                            </div>
                                                            <img class="g-thumb" src="<?= $file['thumbnailLink'] ?>">
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                            } ?>
                                            <div class="g-btn-upload">
                                                <div class="btn-upload-tmp"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php ActiveForm::end() ?>
                                <!-- END CHUP CUI -->

                                <!-- CHUP KET THUC -->
                                <?php
                                $form = ActiveForm::begin([
                                    'id' => 'form-chup-final',
                                    'options' => [
                                        'class' => 'form-upload',
                                        'form-name' => 'FormChupFinal'
                                    ],
                                    'action' => Url::toRoute(['/clinic/chup-final/upload-image', 'id' => $customer->primaryKey]),
                                ]);
                                ?>
                                <div class="cc-block">
                                    <div class="ccb-header">
                                        <div class="ccbh-title">Chụp kết thúc</div>
                                        <div class="ccbh-edit" style="display: none;">
                                            <button type="button" class="btn btn-sm btn-danger remove-image">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="g-download-list">
                                            <button type="button" class="btn btn-primary">Tải về <span>1</span> hình
                                            </button>
                                        </div>
                                    </div>
                                    <div class="ccb-content">
                                        <?= $form->field($formChupFinal, 'fileImage')->fileInput(['multiple' => 'multiple', 'class' => 'btn-upload hidden'])->label(false) ?>
                                        <div class="g-list-file">
                                            <?php
                                            if (count($listChupFinal) > 0) {
                                                foreach ($listChupFinal as $file) {
                                                    ?>
                                                    <div class="g-file">
                                                        <div>
                                                            <div class="g-tools">
                                                                <span>
                                                                    <a class="g-download g-image"
                                                                       data-image="<?= $file['webContentLink'] ?>"
                                                                       data-id="<?= $file['id'] ?>"
                                                                       data-title="<?= $customer->full_name ?>"
                                                                       data-type="<?= $file['type'] ?>">
                                                                        <i class="fa fa-download"></i>
                                                                    </a>
                                                                </span>
                                                                <span>
                                                                    <a class="g-check">
                                                                        <label class="square-checkbox">
                                                                            <input type="checkbox">
                                                                            <span></span>
                                                                        </label>
                                                                    </a>
                                                                </span>
                                                            </div>
                                                            <div class="g-view">
                                                                <i class="fa fa-search"></i>
                                                            </div>
                                                            <img class="g-thumb" src="<?= $file['thumbnailLink'] ?>">
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                            } ?>
                                            <div class="g-btn-upload">
                                                <div class="btn-upload-tmp"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php ActiveForm::end() ?>
                                <!-- END CHUP KET THUC -->

                                <!-- HINH TKNC -->
                                <?php
                                $form = ActiveForm::begin([
                                    'id' => 'form-hinh-tknc',
                                    'options' => [
                                        'class' => 'form-upload',
                                        'form-name' => 'FormHinhTknc'
                                    ],
                                    'action' => Url::toRoute(['/clinic/tknc/upload-image', 'id' => $customer->primaryKey]),
                                ]);
                                ?>
                                <div class="cc-block">
                                    <div class="ccb-header">
                                        <div class="ccbh-title">Thiết kế nụ cười</div>
                                        <div class="ccbh-edit" style="display: none;">
                                            <button type="button" class="btn btn-sm btn-danger remove-image">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="g-download-list">
                                            <button type="button" class="btn btn-primary">Tải về <span>1</span> hình
                                            </button>
                                        </div>
                                    </div>
                                    <div class="ccb-content">
                                        <?= $form->field($formHinhTknc, 'fileImage')->fileInput(['multiple' => 'multiple', 'class' => 'btn-upload hidden'])->label(false) ?>
                                        <div class="g-list-file">
                                            <?php
                                            if (count($listHinhTknc) > 0) {
                                                foreach ($listHinhTknc as $file) {
                                                    ?>
                                                    <div class="g-file">
                                                        <div>
                                                            <div class="g-tools">
                                                                <span>
                                                                    <a class="g-download g-image"
                                                                       data-image="<?= $file['webContentLink'] ?>"
                                                                       data-id="<?= $file['id'] ?>"
                                                                       data-title="<?= $customer->full_name ?>"
                                                                       data-type="<?= $file['type'] ?>">
                                                                        <i class="fa fa-download"></i>
                                                                    </a>
                                                                </span>
                                                                <span>
                                                                    <a class="g-check">
                                                                        <label class="square-checkbox">
                                                                            <input type="checkbox">
                                                                            <span></span>
                                                                        </label>
                                                                    </a>
                                                                </span>
                                                            </div>
                                                            <?php if (isset($file['type'])) { ?>
                                                                <div class="g-chooses <?= $file['type'] == 'local' && $file['imageType'] != 0 ? 'active' : '' ?>">
                                                                <span>
                                                                    <a class="g-choose">
                                                                        <label class="square-checkbox"
                                                                               data-choose="before">
                                                                            <input type="checkbox" <?= $file['type'] == 'local' && $file['imageType'] == 1 ? 'checked' : '' ?>>
                                                                            <span></span>
                                                                            <span>Before</span>
                                                                        </label>
                                                                    </a>
                                                                </span>
                                                                    <span>
                                                                    <a class="g-choose">
                                                                        <label class="square-checkbox"
                                                                               data-choose="after">
                                                                            <input type="checkbox" <?= $file['type'] == 'local' && $file['imageType'] == 2 ? 'checked' : '' ?>>
                                                                            <span></span>
                                                                            <span>After</span>
                                                                        </label>
                                                                    </a>
                                                                </span>
                                                                </div>
                                                            <?php } ?>
                                                            <div class="g-view">
                                                                <i class="fa fa-search"></i>
                                                            </div>
                                                            <img class="g-thumb" src="<?= $file['thumbnailLink'] ?>">
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                            } ?>
                                            <div class="g-btn-upload">
                                                <div class="btn-upload-tmp"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php ActiveForm::end() ?>
                                <!-- END HINH TKNC -->

                                <!-- UOM RANG 1 -->
                                <?php
                                $form = ActiveForm::begin([
                                    'id' => 'form-uom-rang-1',
                                    'options' => [
                                        'class' => 'form-upload',
                                        'form-name' => 'FormUomRang1'
                                    ],
                                    'action' => Url::toRoute(['/clinic/uom-rang1/upload-image', 'id' => $customer->primaryKey]),
                                ]);
                                ?>
                                <div class="cc-block">
                                    <div class="ccb-header">
                                        <div class="ccbh-title">Ảnh chụp Ướm răng lần 1</div>
                                        <div class="ccbh-edit" style="display: none;">
                                            <button type="button" class="btn btn-sm btn-danger remove-image">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="g-download-list">
                                            <button type="button" class="btn btn-primary">Tải về <span>1</span> hình
                                            </button>
                                        </div>
                                    </div>
                                    <div class="ccb-content">
                                        <?= $form->field($formUomRang1, 'fileImage')->fileInput(['multiple' => 'multiple', 'class' => 'btn-upload hidden'])->label(false) ?>
                                        <div class="g-list-file">
                                            <?php
                                            if (count($listUomRang1) > 0) {
                                                foreach ($listUomRang1 as $file) {
                                                    ?>
                                                    <div class="g-file">
                                                        <div>
                                                            <div class="g-tools">
                                                                <span>
                                                                    <a class="g-download g-image"
                                                                       data-image="<?= $file['webContentLink'] ?>"
                                                                       data-id="<?= $file['id'] ?>"
                                                                       data-title="<?= $customer->full_name ?>"
                                                                       data-type="<?= $file['type'] ?>">
                                                                        <i class="fa fa-download"></i>
                                                                    </a>
                                                                </span>
                                                                <span>
                                                                    <a class="g-check">
                                                                        <label class="square-checkbox">
                                                                            <input type="checkbox">
                                                                            <span></span>
                                                                        </label>
                                                                    </a>
                                                                </span>
                                                            </div>
                                                            <div class="g-view">
                                                                <i class="fa fa-search"></i>
                                                            </div>
                                                            <img class="g-thumb" src="<?= $file['thumbnailLink'] ?>">
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                            } ?>
                                            <div class="g-btn-upload">
                                                <div class="btn-upload-tmp"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php ActiveForm::end() ?>
                                <!-- END UOM RANG 1-->

                                <!-- UOM RANG 2 -->
                                <?php
                                $form = ActiveForm::begin([
                                    'id' => 'form-uom-rang-2',
                                    'options' => [
                                        'class' => 'form-upload',
                                        'form-name' => 'FormUomRang2'
                                    ],
                                    'action' => Url::toRoute(['/clinic/uom-rang2/upload-image', 'id' => $customer->primaryKey]),
                                ]);
                                ?>
                                <div class="cc-block">
                                    <div class="ccb-header">
                                        <div class="ccbh-title">Ảnh chụp Ướm răng lần 2</div>
                                        <div class="ccbh-edit" style="display: none;">
                                            <button type="button" class="btn btn-sm btn-danger remove-image">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="g-download-list">
                                            <button type="button" class="btn btn-primary">Tải về <span>1</span> hình
                                            </button>
                                        </div>
                                    </div>
                                    <div class="ccb-content">
                                        <?= $form->field($formUomRang2, 'fileImage')->fileInput(['multiple' => 'multiple', 'class' => 'btn-upload hidden'])->label(false) ?>
                                        <div class="g-list-file">
                                            <?php
                                            if (count($listUomRang2) > 0) {
                                                foreach ($listUomRang2 as $file) {
                                                    ?>
                                                    <div class="g-file">
                                                        <div>
                                                            <div class="g-tools">
                                                                <span>
                                                                    <a class="g-download g-image"
                                                                       data-image="<?= $file['webContentLink'] ?>"
                                                                       data-id="<?= $file['id'] ?>"
                                                                       data-title="<?= $customer->full_name ?>"
                                                                       data-type="<?= $file['type'] ?>">
                                                                        <i class="fa fa-download"></i>
                                                                    </a>
                                                                </span>
                                                                <span>
                                                                    <a class="g-check">
                                                                        <label class="square-checkbox">
                                                                            <input type="checkbox">
                                                                            <span></span>
                                                                        </label>
                                                                    </a>
                                                                </span>
                                                            </div>
                                                            <div class="g-view">
                                                                <i class="fa fa-search"></i>
                                                            </div>
                                                            <img class="g-thumb" src="<?= $file['thumbnailLink'] ?>">
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                            } ?>
                                            <div class="g-btn-upload">
                                                <div class="btn-upload-tmp"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php ActiveForm::end() ?>
                                <!-- END UOM RANG 2-->

                                <!-- HINH FINAL -->
                                <?php
                                $form = ActiveForm::begin([
                                    'id' => 'form-hinh-final',
                                    'options' => [
                                        'class' => 'form-upload',
                                        'form-name' => 'FormHinhFinal'
                                    ],
                                    'action' => Url::toRoute(['/clinic/hinh-final/upload-image', 'id' => $customer->primaryKey]),
                                ]);
                                ?>
                                <div class="cc-block">
                                    <div class="ccb-header">
                                        <div class="ccbh-title">Ảnh chụp Final</div>
                                        <div class="ccbh-edit" style="display: none;">
                                            <button type="button" class="btn btn-sm btn-danger remove-image">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="g-download-list">
                                            <button type="button" class="btn btn-primary">Tải về <span>1</span> hình
                                            </button>
                                        </div>
                                    </div>
                                    <div class="ccb-content">
                                        <?= $form->field($formHinhFinal, 'fileImage')->fileInput(['multiple' => 'multiple', 'class' => 'btn-upload hidden'])->label(false) ?>
                                        <div class="g-list-file">
                                            <?php
                                            if (count($listHinhFinal) > 0) {
                                                foreach ($listHinhFinal as $file) {
                                                    ?>
                                                    <div class="g-file">
                                                        <div>
                                                            <div class="g-tools">
                                                                <span>
                                                                    <a class="g-download g-image"
                                                                       data-image="<?= $file['webContentLink'] ?>"
                                                                       data-id="<?= $file['id'] ?>"
                                                                       data-title="<?= $customer->full_name ?>"
                                                                       data-type="<?= $file['type'] ?>">
                                                                        <i class="fa fa-download"></i>
                                                                    </a>
                                                                </span>
                                                                <span>
                                                                    <a class="g-check">
                                                                        <label class="square-checkbox">
                                                                            <input type="checkbox">
                                                                            <span></span>
                                                                        </label>
                                                                    </a>
                                                                </span>
                                                            </div>
                                                            <div class="g-view">
                                                                <i class="fa fa-search"></i>
                                                            </div>
                                                            <img class="g-thumb" src="<?= $file['thumbnailLink'] ?>">
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                            } ?>
                                            <div class="g-btn-upload">
                                                <div class="btn-upload-tmp"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php ActiveForm::end() ?>
                                <!-- END HINH FINAL-->

                                <!-- DENTAL FORM -->
                                <?php
                                $form = ActiveForm::begin([
                                    'id' => 'form-dental-form',
                                    'options' => [
                                        'class' => 'form-upload',
                                        'form-name' => 'FormDentalForm'
                                    ],
                                    'action' => Url::toRoute(['/clinic/dental-form/upload-image', 'id' => $customer->primaryKey]),
                                ]);
                                ?>
                                <div class="cc-block">
                                    <div class="ccb-header">
                                        <div class="ccbh-title">Ảnh chụp Dental Form</div>
                                        <div class="ccbh-edit" style="display: none;">
                                            <button type="button" class="btn btn-sm btn-danger remove-image">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="g-download-list">
                                            <button type="button" class="btn btn-primary">Tải về <span>1</span> hình
                                            </button>
                                        </div>
                                    </div>
                                    <div class="ccb-content">
                                        <?= $form->field($formDentalForm, 'fileImage')->fileInput(['multiple' => 'multiple', 'class' => 'btn-upload hidden'])->label(false) ?>
                                        <div class="g-list-file">
                                            <?php
                                            if (count($listDentalForm) > 0) {
                                                foreach ($listDentalForm as $file) {
                                                    ?>
                                                    <div class="g-file">
                                                        <div>
                                                            <div class="g-tools">
                                                                <span>
                                                                    <a class="g-download g-image"
                                                                       data-image="<?= $file['webContentLink'] ?>"
                                                                       data-id="<?= $file['id'] ?>"
                                                                       data-title="<?= $customer->full_name ?>"
                                                                       data-type="<?= $file['type'] ?>">
                                                                        <i class="fa fa-download"></i>
                                                                    </a>
                                                                </span>
                                                                <span>
                                                                    <a class="g-check">
                                                                        <label class="square-checkbox">
                                                                            <input type="checkbox">
                                                                            <span></span>
                                                                        </label>
                                                                    </a>
                                                                </span>
                                                            </div>
                                                            <div class="g-view">
                                                                <i class="fa fa-search"></i>
                                                            </div>
                                                            <img class="g-thumb" src="<?= $file['thumbnailLink'] ?>">
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                            } ?>
                                            <div class="g-btn-upload">
                                                <div class="btn-upload-tmp"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php ActiveForm::end() ?>
                                <!-- END DENTAL FORM -->
                            </div>
                            <div class="form-actions">
                                <button type="button" class="btn btn-primary btn-submit">
                                    <i class="fa fa-check-square-o"></i> Save
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <a id="target"></a>
<?php
$this->registerJsFile('/vendors/plugins/fancybox/dist/jquery.fancybox.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$urlDownload = Url::toRoute(['download']);
$urlChooseImage = Url::toRoute(['/clinic/tknc/choose-image', 'id' => $customer->id]);
$urlAppNotif = Url::toRoute(['/clinic/chup-hinh/app-notif', 'user_id' => $customer->directsale, 'customer_id' => $customer->primaryKey]);
$scipt = <<< JS
var scriptId = '$customer->primaryKey',
    listUpload = [],
    intervalDownload,
    listDownload = {},
    isDownloading = false,
    listImageFancy = {},
    currentFormName = null;
$('.form-upload').each(function(){
    let form = $(this),
        form_id = form.attr('id');
    listImageFancy[form_id] = [];
    form.find('.g-file').each(function(){
        let title = $(this).find('.g-image').attr('data-title'),
            image = $(this).find('.g-image').attr('data-image'),
            thumb = $(this).find('.g-thumb').attr('src');
        listImageFancy[form_id].push({
            src: image,
            opts: {
                caption: title,
                thumb: thumb
            }
        });
    });
});
$('body').on('click', '.g-view', function(){
    let form = $(this).closest('.form-upload'),
        form_id = form.attr('id'),
        i = $(this).closest('.g-file').index(),
        a = listImageFancy[form_id].slice(i, listImageFancy[form_id].length),
        b = listImageFancy[form_id].slice(0, i),
        tmp = $.merge(a, b);
    $.fancybox.open(tmp, {
        loop: true,
        buttons : [
            'download',
            'thumbs',
            'close'
        ]
    });
}).on('click', '.g-download', function(){
    $(this).closest('.g-file').children('div').myLoading({
        opacity: true,
        size: 'sm'
    });
    openGDownload($(this));
}).on('click', '.btn-upload-tmp', function() {
    $(this).closest('.ccb-content').find('.btn-upload').trigger('click');
}).on('click', '.g-download-list > button', function(){
    let el = $(this),
        content = el.closest('.cc-block');
    $('.g-download-list').slideUp();
    content.find('.g-tools.active').closest('.g-file').children('div').myLoading({
        opacity: true,
        size: 'sm'
    });
    content.find('.g-list-file').addClass('downloading');
    listDownload = content.find('.g-tools.active .g-download');
    openListGDownload(content);
}).on('click', '.avatar-upload-icon', function() {
    $(this).closest('.customer-avatar').find('.ipt-avatar').trigger('click');
}).on('change', '.ipt-avatar', function() {
    var input = this;
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = function(e){
            $(input).closest('.customer-avatar').find('.img-avatar').prop('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        $(input).closest('.customer-avatar').find('.img-avatar').prop('src', '$avatarDefault');
    };
}).on('change', '.btn-upload', function(){
    var input = this;
    $(input).closest('.ccb-content').find('.g-file-temp').remove();
    if (input.files && input.files[0]) {
        $(input).closest('.cc-block').find('.ccbh-edit').slideDown();
        $.each(input.files, function(k, v){
            let reader = new FileReader();
            reader.onload = function(e){
                $(input).closest('.cc-block').find('.g-btn-upload').before('<div class="g-file-temp g-file-'+ k +'"><div><img class="g-thumb" src="'+ e.target.result +'"></div></div>');
            }
            reader.readAsDataURL(v);
        });
    } else {
        $(input).closest('.cc-block').find('.ccbh-edit').slideUp();
    };
    return false;
}).on('change', '.g-check > .square-checkbox > input', function(){
    let el = $(this);
    $.when(el.closest('.g-tools').toggleClass('active')).done(function(){
        countGCheck(el);
        if(el.closest('.cc-block').find('.g-tools.active').length > 0){
            if(!el.closest('.cc-block').find('.g-download-list').is(':visible')) el.closest('.cc-block').find('.g-download-list').slideDown();
        } else {
            el.closest('.cc-block').find('.g-download-list').slideUp();
        }
    });
}).on('click', '.remove-image', function() {
    $(this).parent('.ccbh-edit').slideUp().closest('.cc-block').find('.g-list-file > .g-file-temp').remove();
    $(this).closest('.cc-block').find('.btn-upload').val('').trigger('change');
}).on('click', '.btn-submit', function(e) {
    e.preventDefault();
    $('.content-body').myLoading({
        fixed: true,
        opacity: true
    });
    $(window).bind('beforeunload', function(){
        setTimeout(function(){
            $('body').myUnloading();
        }, 50);
        return "";
    });
    $.when($('html, body').animate({scrollTop: $('form#form-update-customer').offset().top - $('.header-navbar').outerHeight()}, 1000)).done(function(){
        saveDataCustomer().then(function(result) {
            console.log(result);
            $('.content-body').myUnloading();
            toastr.success(result.msg, 'Thông báo');
            var id = result.id || null;
            $('.form-upload').each(function() {
                var url = $(this).attr('action') || null,
                    form_name = $(this).attr('form-name') || null,
                    _csrf = $(this).find('input[name="_csrf"]').val() || null,
                    input = $(this).find('.btn-upload') || null;
                if(form_name != null && input != null && input[0].files.length > 0){
                    Object.keys(input[0].files).forEach(function(k){
                        var form_data = new FormData();
                        form_data.append('_csrf', _csrf);
                        form_data.append(form_name +'[id]', id);
                        form_data.append(form_name +'[fileImage][]', input[0].files[k])
                        listUpload.push({
                            k: k,
                            form_name: form_name,
                            url: url,
                            data: form_data,
                        });
                    });
                    input.val('');
                }
            });
            if(id != null && listUpload.length > 0){
                toastr.warning('Bắt đầu upload hình ảnh khách hàng', 'Thông báo');
                $('.content-body').find('.g-btn-upload, .ccbh-edit').hide();
                $('.content-body').find('.g-file-temp').children('div').myLoading({
                    opacity: true,
                    size: 'sm'
                });
            }
            uploadImages(listUpload);
        }, function(err) {
            $('.content-body').myUnloading();
            toastr.error(err, 'Thông báo');
            $(window).unbind('beforeunload');
        });
    });
    return false;
}).on('click', '.g-choose label', function(e){
    e.preventDefault();
    let el = $(this),
        choose = el.attr('data-choose'),
        id = el.closest('.g-file').find('.g-download').attr('data-id'),
        act,
        form = el.closest('.form-upload');
    if(el.find('input').is(':checked')){
        act = 'remove';
        el.find('input').prop('checked', false);
    } else {
        act = 'add';
        form.find('label[data-choose="'+ choose +'"]').find('input').prop('checked', false);
        el.find('input').prop('checked', true);
        el.closest('.g-chooses').addClass('active');
        form.myLoading({
            msg: 'Đang tải...',
            opacity: true
        });
    }
    $.when(chooseImage(id, choose, act).then(function(res){
        toastr.success(res.msg, 'Thông báo');
    }, function(err) {
        toastr.error(err.msg, 'Thông báo');
        el.find('input').prop('checked', false);
    })).done(function() {
        form.myUnloading();
        checkGChoose(form);
    })
    return false;
});
function countGCheck(el){
    el.closest('.cc-block').find('.g-download-list > button > span').text(el.closest('.cc-block').find('.g-tools.active').length);
}
function saveDataCustomer(){
    return new Promise(function(resolve, reject){
        var ipt_avatar = $('.ipt-avatar')[0].files;
        if(ipt_avatar.length <= 0) return resolve({
            id: scriptId,
            msg: 'Cập nhật thông tin khách hàng thành công',
        });
        var form = $('#form-update-customer')[0],
            form_data = new FormData(form),
            url = $('#form-update-customer').attr('action');
        $.ajax({
            type: 'POST',
            url: url,
            dataType: 'json',
            data: form_data,
            cache: false,
            processData: false,
            contentType: false,
        }).done(function(res){
            if(res.code === 200){
                resolve(res);
            } else reject(res.msg);
        }).fail(function(err){
            console.log(err);
            reject('Có lỗi xảy ra!');
        });
    });
}
function appNotif(){
    $.get('$urlAppNotif');
}
function uploadImages(listUpload){
    if(listUpload.length > 0){
        var upload = listUpload[0];
        if(currentFormName == 'FormHinhTknc' && upload.form_name != 'FormHinhTknc'){
            appNotif();
        }
        $.when($('html, body').animate({scrollTop: $('form.form-upload[form-name="'+ upload.form_name +'"]').offset().top - $('.header-navbar').outerHeight()}, 1000)).done(function(){
            uploadImage(upload).then(function(){
                listUpload.splice(0, 1);
                uploadImages(listUpload);
            });
        });
    } else {
        if(currentFormName == 'FormHinhTknc'){
            appNotif();
        }
        toastr.success('Upload hình ảnh hoàn tất', 'Thông báo');
        $('.g-btn-upload').show();
        $(window).unbind('beforeunload');
    }
}
function uploadImage(upload){
    return new Promise(function(resolve, reject){
        var file = $('form.form-upload[form-name="'+ upload.form_name +'"]').find('.g-file-'+ upload.k)
        $.ajax({
            type: 'POST',
            url: upload.url,
            dataType: 'json',
            data: upload.data,
            cache: false,
            contentType: false,
            processData: false,
        }).done(function(res){
            var dataImage = res.data.dataImage || null;
            if(res.code === 200){
                currentFormName = upload.form_name;
                if(dataImage != null){
                    if(dataImage.id === null || dataImage.id === undefined){
                        console.log('dataImage id null');
                        file.children('div').myUnloading().prepend('<div class="upload-fail"><i class="fa fa-times"></i></div>');
                        setTimeout(function(){
                            file.remove();
                        }, 3000);
                        resolve();
                    } else {
                        console.log('upload success');
                        let form_id = $('form[form-name="'+ upload.form_name +'"]').attr('id'),
                            text_prepend;
                        console.log(upload, dataImage);
                        if(upload.form_name == 'FormHinhTknc' && dataImage.type != undefined){
                            text_prepend = '<div class="upload-success"><i class="fa fa-check"></i></div><div class="g-tools"><span><a class="g-download g-image" data-image="'+ dataImage.image +'" data-id="'+ dataImage.id +'" data-type="'+ dataImage.type +'" data-title="'+ dataImage.title +'"><i class="fa fa-download"></i></a></span><span><a class="g-check"><label class="square-checkbox"><input type="checkbox"><span></span></label></a></span></div><div class="g-chooses"><span><a class="g-choose"><label class="square-checkbox" data-choose="before"><input type="checkbox"><span></span><span>Before</span></label></a></span><span><a class="g-choose"><label class="square-checkbox" data-choose="after"><input type="checkbox"><span></span><span>After</span></label></a></span></div><div class="g-view"><i class="fa fa-search"></i></div>';
                        } else {
                            text_prepend = '<div class="upload-success"><i class="fa fa-check"></i></div><div class="g-tools"><span><a class="g-download g-image" data-image="'+ dataImage.image +'" data-id="'+ dataImage.id +'" data-type="'+ dataImage.type +'" data-title="'+ dataImage.title +'"><i class="fa fa-download"></i></a></span><span><a class="g-check"><label class="square-checkbox"><input type="checkbox"><span></span></label></a></span></div><div class="g-view"><i class="fa fa-search"></i></div>';
                        }
                        file.removeClass('g-file-temp g-file-'+ upload.k).addClass('g-file').children('div').prepend(text_prepend).myUnloading();
                        file.find('.g-thumb').attr('src', dataImage.thumb);
                        setTimeout(function(){
                            $.when(file.find('.upload-success').fadeOut()).done(function(){
                                file.find('.upload-success').remove();
                            });
                        }, 3000);
                        listImageFancy[form_id].push({
                            src: dataImage.image,
                            opts: {
                                caption: dataImage.title,
                                thumb: dataImage.thumb
                            }
                        });
                        resolve();
                    }
                } else {
                    console.log('dataImage null');
                    file.children('div').myUnloading().prepend('<div class="upload-fail"><i class="fa fa-times"></i></div>');
                    setTimeout(function(){
                        file.remove();
                    }, 3000);
                    resolve();
                }
            } else {
                toastr.error(res.data.msg, 'Lỗi!');
                file.children('div').myUnloading().prepend('<div class="upload-fail"><i class="fa fa-times"></i></div>');
                setTimeout(function(){
                    file.remove();
                }, 3000);
                resolve();
            }
        }).fail(function(err){
            console.log('upload error', upload, err);
            toastr.error('Lỗi upload hình', 'Thông báo upload hình');
            file.children('div').myUnloading().prepend('<div class="upload-fail"><i class="fa fa-times"></i></div>');
            setTimeout(function(){
                file.remove();
            }, 3000);
            resolve();
        }).catch(function(err){
            console.log('upload error', upload, err);
            toastr.error('Lỗi upload hình', 'Thông báo upload hình');
            file.children('div').myUnloading().prepend('<div class="upload-fail"><i class="fa fa-times"></i></div>');
            setTimeout(function(){
                file.remove();
            }, 3000);
            resolve();
        });
    });
}
function openListGDownload(content){
    if(isDownloading == true){
        return false;
    }
    clearInterval(intervalDownload);
    if(listDownload.length <= 0){
        content.find('.g-download-list').slideUp();
        $('.g-check input:checked').closest('.cc-block').find('.g-download-list').slideDown();
        return false;
    }
    isDownloading = true;
    var btn = listDownload[0];
    listDownload = listDownload.splice(1, listDownload.length);
    intervalDownload = setInterval(function(){
        openListGDownload(content);
    }, 1000);
    openGDownload($(btn)).then(
        function(){
            $.when($(btn).closest('.g-tools').removeClass('active').find('.g-check > label > input').prop('checked', false)).done(function(){
                isDownloading = false;
                if(listDownload.length <= 0){
                    content.find('.g-list-file').removeClass('downloading');
                    return false;
                }
            });
        }
    ).catch(function(){
        $.when($(btn).closest('.g-tools').removeClass('active').find('.g-check > label > input').prop('checked', false)).done(function(){
            isDownloading = false;
            if(listDownload.length <= 0){
                content.find('.g-list-file').removeClass('downloading');
                return false;
            }
        });
    });
}
function openGDownload(btnDownload){
    return new Promise((resolve, reject) => {
        var id = btnDownload.attr('data-id') || null,
            type = btnDownload.attr('data-type') || null;
        if(id === null) reject(false);
        var downloadUrl = "$urlDownload?fileId="+ id +"&type="+ type,
            req = new XMLHttpRequest();
        req.open("GET", downloadUrl, true);
        req.responseType = "blob";
        
        req.onload = function (event) {
            var blob = req.response,
                fileName = null,
                contentType = req.getResponseHeader("content-type");
            
            // IE/EDGE seems not returning some response header
            if (req.getResponseHeader("content-disposition")) {
                var contentDisposition = req.getResponseHeader("content-disposition");
                fileName = contentDisposition.substring(contentDisposition.indexOf("=")+1).replace(/"/g, "");
            } else {
                fileName = "unnamed." + contentType.substring(contentType.indexOf("/")+1).replace(/"/g, "");
            }
            
            if (window.navigator.msSaveOrOpenBlob) {
                // Internet Explorer
                window.navigator.msSaveOrOpenBlob(new Blob([blob], {type: contentType}), fileName);
            } else {
                var el = document.getElementById("target");
                el.href = window.URL.createObjectURL(blob);
                el.download = fileName;
                el.click();
                btnDownload.closest('.g-file').children('div').myUnloading();
                $('#target').removeAttr('href download');
                resolve(true);
            }
        };
        req.send();
    });
}
function chooseImage(id, type, act){
    return new Promise(function(resolve, reject) {
        $.ajax({
            type: 'POST',
            url: '$urlChooseImage',
            dataType: 'json',
            data: {
                image_id: id,
                type: type,
                act: act
            }
        }).done(function(res) {
            if(res.code == 200){
                resolve(res);
            } else {
                reject(res);
            }
        }).fail(function(err) {
            reject({
                code: 400,
                msg: 'Fail',
                err: err
            });
        });
    })
}
function checkGChoose(form){
    form.find('.g-chooses').each(function(){
        if($(this).find('input:checked').length > 0) $(this).addClass('active');
        else $(this).removeClass('active');
    });
}
JS;
$this->registerJs($scipt, \yii\web\View::POS_END);
