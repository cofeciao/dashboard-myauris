<?php

use backend\modules\setting\models\Dep365CoSo;
use backend\modules\user\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\user\models\User */
/* @var $modelSubrole backend\modules\user\models\UserSubRole */
/* @var $modelProfile common\models\UserProfile */
/* @var $form yii\widgets\ActiveForm */


?>
<?php $form = ActiveForm::begin(['id' => 'form-create_user']); ?>
<div class="modal-body">
    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?php
    if (Yii::$app->controller->action->id == 'update') {
        ?>

        <div class="row">
            <div class="col-6">
                <?= $form->field($modelProfile, 'fullname')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-6">
                <?= $form->field($modelProfile, 'phone')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <?= $form->field($model, 'status')->dropDownList(User::statuses()) ?>
        <div class="row">
            <div class="col-lg-4 col-xl-4 col-md-4 col-xs-6 col-6">
                <div class="form-group field-user-status">
                    <label class="control-label" for="user-status">Quyền</label>
                    <?php
                    $user = new User();
                    $auth = Yii::$app->authManager;
                    echo Html::dropDownList('User[role_name]', $user->getRoleName($model->id), ArrayHelper::map($auth->getChildRoles($user->getRoleName(Yii::$app->user->id)), 'name', 'description'), ['class' => 'form-control', 'id' => 'user-role-name']); ?>
                </div>
            </div>
            <div class="col-lg-4 col-xl-4 col-md-4 col-xs-6 col-6">
                <!--                    <div class="permisionCoso">-->
                <?= $form->field($model, 'permission_coso')->dropDownList(ArrayHelper::map(Dep365CoSo::getCoSo(), 'id', 'name'), ['prompt' => 'Chọn cơ sở phụ trách...']) ?>
                <!--                    </div>-->
            </div>
            <div class="col-lg-4 col-xl-4 col-md-4 col-xs-6 col-6">
                <?= $form->field($model, 'team')->dropDownList(Yii::$app->controller->module->params['team'], ['prompt' => 'Chọn Team...']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 col-xl-4 col-md-4 col-xs-6 col-6">
                <?= $form->field($model, 'vpbx_acc')->textInput() ?>
            </div>
            <div class="col-lg-4 col-xl-4 col-md-4 col-xs-6 col-6">
                <?= $form->field($model, 'vpbx_pass')->textInput() ?>
            </div>
            <div class="col-lg-4 col-xl-4 col-md-4 col-xs-6 col-6">
                <?= $form->field($modelSubrole, 'role')->dropDownList(\backend\modules\user\models\UserSubRole::$roles, ['prompt' => 'Role...']) ?>
            </div>
        </div>

        <?php
    }
    ?>
</div>
<div class="modal-footer">
    <?= Html::resetButton('<i class="ft-x"></i> Close', ['class' =>
        'btn btn-warning mr-1']) ?>
    <?= Html::submitButton(
        '<i class="fa fa-check-square-o"></i> Save',
        ['class' => 'btn btn-primary']
    ) ?>
</div>
<?php ActiveForm::end(); ?>
