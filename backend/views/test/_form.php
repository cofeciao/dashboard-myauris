<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Test */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="test-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'view_number')->textInput() ?>

    <?= $form->field($model, 'position')->textInput() ?>

    <?php
    if (Yii::$app->controller->action->id == 'create') {
        $model->status = 1;
    }
    ?>
    <?= $form->field($model, 'status')->checkbox() ?>

    <div class="delete-image">
        <?php
        $src = '';
        if ($model->image != '') {
            $src = '/uploads/news/475x332/' . $model->image;
        }
        ?>
        <img src="<?= $src; ?>" id="imgPreview" width="200px"
             class="img-responsive"/>
        <button class="x-delete" type="button" id="img-delete">X</button>
    </div>
    <?= $form->field($model, 'image', [
        'template' => '<div class="input-group">{input}
                                    <div class="input-group-append">
                                    <button type="button" class="btn btn-menu-img" id="img-select">
                                    Upload Image
                                </button>
                            </div></div><div>Kích thước: (475x332)px</div>{error}{hint}'
    ])->textInput(['aria-label' => 'Text input with dropdown button', 'readonly' => '', 'class' => 'form-control', 'id' => 'menu-image']) ?>

    <div class="form-actions">
        <?= Html::resetButton('<i class="ft-x"></i> Close', ['class' =>
            'btn btn-warning mr-1']) ?>
        <?= Html::submitButton(
                '<i class="fa fa-check-square-o"></i> Save',
                ['class' => 'btn btn-primary']
            ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php
echo \backend\widgets\FileWidget::widget([
    'id' => 'menu-image',
]);
?>

<?php
$image = <<< JS
    $(document).ready(function() {
      $('#menu-image, .btn-menu-img').click(function() {
            $('#imgModal').modal();
      });
      $('#imgModal').on('hidden.bs.modal', function() {
            var imgSrc = $('#menu-image').val();
            $('#imgPreview').attr({'src': imgSrc});
            // $('#img-delete').show();            
      });
      $('#img-delete').click(function() {
            $('#imgPreview').attr({'src': ''});
            $('#img-delete').hide();
            $('input#menu-image').val('');
      });
      
      $('.delete-image').hover(function() {
          if($.trim($("#imgPreview").attr("src")) != "")
        {
            $('.x-delete').show();
        }
        
      }, function() {
        $('.x-delete').hide();
      })
      
    });
JS;


$this->registerJs($image, \yii\web\View::POS_END);

?>


