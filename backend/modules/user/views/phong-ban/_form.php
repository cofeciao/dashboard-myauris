<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\User;
use backend\modules\user\models\PhongBan;
use backend\modules\user\models\RbacAuthItem_;

/* @var $this yii\web\View */
/* @var $model backend\modules\user\models\PhongBan */
/* @var $form yii\widgets\ActiveForm */

$css = <<< CSS
.ui.search.selection.dropdown>input.search {
    padding: 0 5px;
    width: auto !important;
}
CSS;
$this->registerCss( $css );

if ( $model->roleHasMany != null ) {
	$model->roles = ArrayHelper::map( $model->roleHasMany, 'name', 'name' );
}
if ( $model->userHasMany != null ) {
	$model->users = ArrayHelper::map( $model->userHasMany, 'id', 'id' );
}
?>

    <div class="phong-ban-form">

		<?php $form = ActiveForm::begin(); ?>
        <div class="form-actions">
            <div class="row">
                <div class="col-md-6 col-12">
					<?= $form->field( $model, 'name' )->textInput( [ 'maxlength' => true ] ) ?>
                </div>
                <div class="col-md-6 col-12">
					<?= $form->field( $model, 'parent' )->dropDownList( ArrayHelper::map( PhongBan::getMenuPhongBan( $model->primaryKey ), 'id', 'name' ), [
						'placeholder' => 'Chọn phòng ban...',
						'class'       => 'select2 form-control',
						'id'          => 'parent'
					] ) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-12">
					<?= $form->field( $model, 'roles' )->dropDownList( ArrayHelper::map( RbacAuthItem_::getItemForPhongBan( null, [
						User::USER_ADMINISTRATOR,
						User::USER_DEVELOP
					] ), 'name', 'description' ), [
						'placeholder'        => 'Quyền...',
						'multiple'           => true,
						'id'                 => 'select-role',
						'class'              => 'form-control select2 load-data-on-change',
						'load-data-element'  => '#select-user',
						'load-data-url'      => Url::toRoute( [ 'get-users-by-roles' ] ),
						'load-data-key'      => 'roles',
						'load-data-callback' => 'afterLoadUser',
						'load-data-method'   => 'GET',
						'load-data-replace'  => 'false',
					] ) ?>
                </div>
                <div class="col-md-6 col-12">
					<?= $form->field( $model, 'users' )->dropDownList( ArrayHelper::map( User::getAllUsers( [] ),
						'id', 'fullname' ), [
						'placeholder' => 'Nhân viên...',
						'id'          => 'select-user',
						'class'       => 'form-control select2',
						'multiple'    => true,
					] ) ?>
                </div>
            </div>

			<?php if ( Yii::$app->controller->action->id == 'create' ) {
				$model->status = 1;
			}
			?>
			<?= $form->field( $model, 'status' )->checkbox() ?>
        </div>
        <div class="form-actions">
			<?= Html::resetButton( '<i class="ft-x"></i> Cancel', [
				'class' =>
					'btn btn-warning mr-1'
			] ) ?>
			<?= Html::submitButton( '<i class="fa fa-check-square-o"></i> Save',
				[ 'class' => 'btn btn-primary' ] ) ?>
        </div>

		<?php ActiveForm::end(); ?>

    </div>
<?php
$script = <<< JS
$('#select-role, #select_user, #parent').select2();
function afterLoadUser(data){
    data = typeof data === 'object' ? Object.keys(data) : [];
    $('#select-user').val(data).trigger('change');
}
JS;
$this->registerJs( $script, \yii\web\View::POS_END );