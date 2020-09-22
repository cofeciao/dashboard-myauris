<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin([
    'action' => ['index?catagory_id=' . $model['catagory_id']],
    'method' => 'get',
    'options' => [
        'data-pjax' => 1,
        'id' => 'list-support-search',
    ],
]); ?>

<div class="card-content collapse show search-clinic">
    <div class="card-body card-dashboard">
        <div class="row justify-content-md-start form-search">
            <div class="col-4">
                <div class="form-group row">
                    <div class="col-12 form-search-clinic">
                        <?= $form->field($model, 'keyword')->textInput(['placeholder' => 'Tên bài viết, mô tả, nội dung'])->label(false) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <?= Html::submitButton('Tìm kiếm', ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Tất cả', ['?catagory_id=' . $model['catagory_id']], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end() ?>
