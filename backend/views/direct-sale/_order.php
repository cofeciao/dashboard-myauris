<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 19-Jan-19
 * Time: 10:03 AM
 */

use unclead\multipleinput\MultipleInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\modules\clinic\models\PhongKhamDichVu;
use backend\modules\clinic\models\PhongKhamSanPham;
use backend\modules\clinic\models\PhongKhamLoaiThanhToan;
use backend\modules\clinic\models\PhongKhamDirectSale;

?>
    <section id="striped-row-form-layouts">
        <div class="justify-content-md-center">
            <div class="card">
                <?php
                if ($customer) {
                    $nameCustomer = $customer->full_name == null ? $customer->name : $customer->full_name; ?>
                    <div class="card-header">
                        Khách hàng: <span class="card-title"
                                          id="striped-row-layout-card-center"><?= $nameCustomer; ?></span> -
                        Mã: <span class="card-title"
                                  id="striped-row-layout-card-center"><?= $customer->customer_code; ?></span>
                    </div>
                    <?php
                }
                ?>
                <div class="card-content collpase show">
                    <div class="vertical-scroll scroll-example">
                        <div class="card-body">
                            <?php $form = ActiveForm::begin([
                                'class' => 'form form-horizontal striped-rows',
                                'id' => 'form-don-hang',
                                'enableAjaxValidation' => true,
                                'enableClientValidation' => true,
                                'validationUrl' => ['validate-order', 'id' => $model->getAttribute('customer_id')],
                                'action' => Url::toRoute(['order', 'id' => $model->getAttribute('customer_id')]),
                            ]); ?>
                            <?php
                            if ($customer) {
                                echo $form->field($model, 'customer_id')->hiddenInput(['value' => $customer->id])->label(false);
                            }
                            ?>
                            <?= $form->field($model, 'dich_vu', [
                                'template' => '{input}',
                            ])->hiddenInput()->label(false); ?>

                            <div class="form-body">
                                <?php
                                $model->customer_order = $orderData;
                                $model->thanh_toan = $thanhtoanData;

                                $arrSP = ArrayHelper::map(PhongKhamSanPham::getSanPham(), 'id', 'name');
                                $arrSP[0] = 'Chọn sản phẩm...';
                                ksort($arrSP);

                                $arrDV = ArrayHelper::map(PhongKhamDichVu::getDichVu(), 'id', 'name');
                                $arrDV[0] = 'Chọn sản phẩm...';
                                ksort($arrDV);

                                $arrThanhToan = ArrayHelper::map(PhongKhamLoaiThanhToan::getClinicLoaiThanhToan(), 'id', 'name');
                                ?>
                                <?= $form->field($model, 'customer_order', [
                                    'template' => '{input}'
                                ])->widget(MultipleInput::class, [
                                    'max' => 5,
                                    'min' => 1,
                                    'allowEmptyList' => false,
                                    'enableGuessTitle' => true,
                                    'cloneButton' => true,
                                    'columns' => [
                                        [
                                            'name' => 'id',
                                            'type' => 'hiddenInput',
                                            'value' => function ($data) {
//                                                $data = json_decode(json_encode($data), true);
                                                $result = $data['id'];
                                                return $result;
                                            },
                                            'options' => [
                                                'class' => 'id_order',
                                            ]
                                        ],
                                        [
                                            'name' => 'dich_vu',
                                            'type' => 'dropDownList',
                                            'title' => 'Dịch vụ',
                                            'defaultValue' => 0,
                                            'value' => function ($data) {
//                                                $data = json_decode(json_encode($data), true);
                                                return $data['dich_vu'];
                                            },
                                            'items' => $arrDV
                                        ],
                                        [
                                            'name' => 'san_pham',
                                            'type' => 'dropDownList',
                                            'title' => 'Sản phẩm',
                                            'defaultValue' => 0,
                                            'value' => function ($data) {
//                                                $data = json_decode(json_encode($data), true);
                                                return $data['san_pham'];
                                            },
                                            'items' => $arrSP,
                                            'options' => [
                                                'class' => 'sl-sp san-pham-clinic',
                                            ]
                                        ],
                                        [
                                            'name' => 'so_luong',
                                            'title' => 'Số lượng',
                                            'defaultValue' => 1,
                                            'value' => function ($data) {
//                                                $data = json_decode(json_encode($data), true);
                                                $result = $data['so_luong'] == null ? 1 : $data['so_luong'];
                                                return $result;
                                            },
                                            'options' => [
                                                'type' => 'number',
                                                'class' => 'input-priority sl-sp so-luong-clinic',
                                            ]
                                        ],
                                        [
                                            'name' => 'thanh_tien',
                                            'title' => 'Thành tiền',
                                            'defaultValue' => 0,
                                            'value' => function ($data) {
//                                                $data = json_decode(json_encode($data), true);
                                                return $data['thanh_tien'];
                                            },
                                            'options' => [
                                                'class' => 'thanh-tien',
                                                'readonly' => 'readonly',
                                            ]
                                        ],
                                    ],
                                    'iconMap' => [
                                        'myFt' => [
                                            'drag-handle' => 'ft-file-plus',
                                            'remove' => 'ft-delete my-remove',
                                            'add' => 'ft-plus',
                                            'clone' => 'ft-file-plus my-clone',
                                        ],
                                    ],
                                    'iconSource' => 'myFt',
                                ])->label(false);
                                ?>

                                <div class="row">
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-xs-6 col-6">
                                        <?php
                                        $dr = \common\models\User::getNhanVienTuDirectSale();
                                        $directsale = [];
                                        if ($dr != null) {
                                            foreach ($dr as $key => $item) {
                                                $directsale[$item->id] = $item->userProfile->fullname;
                                            }
                                        }
                                        echo $form->field($model, 'direct_sale_id', [
                                            'template' => '{label}{input}',
                                        ])->dropDownList($directsale, ['prompt' => 'Chọn direct sale...']) ?>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-xs-6 col-6">
                                        <?= $form->field($model, 'chiet_khau', [
                                            'template' => '{label}{input}',
                                        ])->textInput(['class' => 'on-keyup form-control']) ?>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-xs-6 col-6">
                                        <?= $form->field($model, 'thanh_tien', [
                                            'template' => '{label}{input}',
                                        ])->textInput(['readonly' => true])->label('Số tiền chưa thanh toán') ?>
                                    </div>
                                </div>

                                <?= $form->field($model, 'thanh_toan', [
                                    'template' => '{input}',
                                ])->widget(MultipleInput::class, [
                                    'max' => 5,
                                    'min' => 1,
                                    'allowEmptyList' => false,
                                    'enableGuessTitle' => true,
                                    'cloneButton' => true,
                                    'columns' => [
                                        [
                                            'name' => 'id',
                                            'type' => 'hiddenInput',
                                            'value' => function ($data) {
//                                                $data = json_decode(json_encode($data), true);
                                                $result = $data['id'];
                                                return $result;
                                            },
                                            'options' => [
                                                'class' => 'id_thanh_toan',
                                            ]
                                        ],
                                        [
                                            'name' => 'tien_thanh_toan',
                                            'type' => 'textInput',
                                            'title' => 'Số tiền thanh toán',
                                            'value' => function ($data) {
//                                                $data = json_decode(json_encode($data), true);
                                                return $data['tien_thanh_toan'];
                                            },
                                            'options' => [
                                                'class' => 'on-keyup',
                                            ]
                                        ],
                                        [
                                            'name' => 'loai_thanh_toan',
                                            'type' => 'radioList',
                                            'title' => 'Loại thanh toán',
                                            'defaultValue' => 1,
                                            'value' => function ($data) {
//                                                $data = json_decode(json_encode($data), true);
                                                $result = $data['loai_thanh_toan'] == null ? 1 : $data['loai_thanh_toan'];
                                                return $result;
                                            },
                                            'items' => $arrThanhToan,
                                            'options' => [
                                                'class' => 'list-hoz',
                                            ]
                                        ],
                                        [
                                            'name' => 'tam_ung',
                                            'type' => 'dropDownList',
                                            'defaultValue' => 0,
                                            'value' => function ($data) {
//                                                $data = json_decode(json_encode($data), true);
                                                return $data['tam_ung'];
                                            },
                                            'items' => ['0' => '', '1' => 'Đặt cọc'],
                                        ],
                                    ],
                                    'iconMap' => [
                                        'myFt' => [
                                            'drag-handle' => 'ft-file-plus',
                                            'remove' => 'ft-delete',
                                            'add' => 'ft-plus',
                                            'clone' => 'ft-file-plus',
                                        ],
                                    ],
                                    'iconSource' => 'myFt',
                                ])->label(false);
                                ?>

                                <?= $form->errorSummary($model); ?>
                            </div>
                            <div class="form-actions center">
                                <?= Html::button('<i class="ft-x"></i> Close', ['class' =>
                                    'btn btn-default mr-1 close-order', 'data-pjax' => 0]) ?>
                                <?= Html::submitButton(
                                        '<i class="fa fa-check-square-o"></i> Save',
                                        ['class' => 'btn btn-primary', 'data-pjax' => 0]
                                    ) ?>
                            </div>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
$order = <<< JS
    $('.vertical-scroll').perfectScrollbar({
		suppressScrollX : true,
        theme: 'dark',
        wheelPropagation: true
	});
    jQuery('.multiple-input').on('afterInit', function(){
        // $(this).find('.on-keyup').attr('onkeyup', 'formatNumber($(this))')
    }).on('afterAddRow', function(e, row){
        getMoney();
        // console.log(row.find('.on-keyup'));
        row.find('.id_order').val('0');
        row.find('.id_thanh_toan').val('0');
        // row.find('.on-keyup').attr('onkeyup', 'formatNumber($(this))');
    }).on('afterDeleteRow', function(){
        getMoney();
    });
    
    $('.on-keyup').each(function() {
        var order_discount = $(this).val().replace(/\./g, '');
        $(this).val(addCommas(order_discount));
        getMoney();
    });
    getMoney();
JS;
$this->registerJs($order, \yii\web\View::POS_END);
