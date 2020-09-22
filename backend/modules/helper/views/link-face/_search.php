<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<?php
$form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    'id'=>'form-search-clinic',
    'options' => [
        'data-pjax' => true
    ],
]); ?>
    <div class="card-content collapse show search-clinic">
        <div class="card-body card-dashboard">
            <div class="row form-search">
                <div class="col-xl-3 col-lg-3 col-md-4 col-xs-4 col-4">
                    <div class="row form-search">
                        <label class="col-12 control-label">Nhập thông tin khách hàng</label>

                    </div>
                </div>
            </div>
            <div class="row form-search">
                <div class="form-search-col col-lg-3 col-4">
                        <div class="col-12 form-search-clinic">
                        <?= $key = $form->field($model, 'name')->textInput(['placeholder' => 'Nhập tên hoặc số điện thoại'])->label(false) ?>
                        </div>
                </div>
                <div class="col-lg-3 col-4">
                    <div class="form-group row fbsearch">
                        <?= Html::submitButton('Tìm kiếm', ['class' => 'btn btn-primary']) ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>