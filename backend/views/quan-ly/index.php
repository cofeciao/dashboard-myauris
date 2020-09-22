<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 08-Apr-19
 * Time: 11:52 AM
 */

use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\customer\models\Dep365CustomerOnlineCome;
use backend\modules\customer\models\Dep365CustomerOnlineDathenStatus;
use common\grid\MyGridView;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Quản lý khách hàng';
$this->params['breadcrumbs'][] = $this->title;
?>
<section id="dom">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content collapse show customer-index">
                    <div class="card-body card-dashboard">
                        <?php Pjax::begin(
                            ['id' => 'quanly-customer-ajax', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]
                        ); ?>
                        <?php
                        echo $this->render('_search', ['model' => $searchModel]);
                        ?>
                        <div style="margin-top:5px;border:1px solid #ccc;border-radius:3px">
                            <?= MyGridView::widget([
                                'dataProvider' => $dataProvider,
//                                    'filterModel' => $searchModel,
                                'layout' => '{errors} <div class="pane-single-table">{items}</div><div class="pager-wrap clearfix">{summary}' .
                                    Yii::$app->controller->renderPartial('@backend/views/layouts/my-gridview/_goToPage', ['totalPage' => $totalPage, 'currentPage' => Yii::$app->request->get($dataProvider->getPagination()->pageParam)]) .
                                    Yii::$app->controller->renderPartial('@backend/views/layouts/my-gridview/_pageSize') .
                                    '{pager}</div>',
                                'tableOptions' => [
                                    'id' => 'listCampaign',
                                    'class' => 'cp-grid cp-widget pane-hScroll',
                                ],
                                'options' => [
                                    'class' => 'grid-view',
                                    'data-pjax' => 1]
                                ,
                                'rowOptions' => function ($model) {
                                    return ['data-key' => $model->id];
                                },
                                'myOptions' => [
                                    'class' => 'grid-content my-content pane-vScroll',
                                    'data-minus' => '{"0":42,"1":".header-navbar","2":".form-search","3":".pager-wrap","4":".footer"}'
                                ],
                                'summaryOptions' => [
                                    'class' => 'summary pull-right',
                                ],
                                'pager' => [
                                    'firstPageLabel' => Html::tag('span', 'skip_previous', ['class' => 'material-icons']),
                                    'lastPageLabel' => Html::tag('span', 'skip_next', ['class' => 'material-icons']),
                                    'prevPageLabel' => Html::tag('span', 'play_arrow', ['class' => 'material-icons']),
                                    'nextPageLabel' => Html::tag('span', 'play_arrow', ['class' => 'material-icons']),
                                    'maxButtonCount' => 5,

                                    'options' => [
                                        'tag' => 'ul',
                                        'class' => 'pagination pull-left',
                                    ],

                                    // Customzing CSS class for pager link
                                    'linkOptions' => ['class' => 'page-link'],
                                    'activePageCssClass' => 'active',
                                    'disabledPageCssClass' => 'disabled page-disabled',
                                    'pageCssClass' => 'page-item',

                                    // Customzing CSS class for navigating link
                                    'prevPageCssClass' => 'page-item prev',
                                    'nextPageCssClass' => 'page-item next',
                                    'firstPageCssClass' => 'page-item first',
                                    'lastPageCssClass' => 'page-item last',
                                ],
                                'columns' => [
                                    [
                                        'class' => 'yii\grid\SerialColumn',
                                        'header' => 'STT',
                                        'headerOptions' => [
                                            'width' => '60',
                                        ],
                                        'contentOptions' => [
                                            'style' => 'text-align: center'
                                        ]
                                    ],
                                    [
                                        'attribute' => 'avatar',
                                        'header' => 'Avatar',
                                        'format' => 'raw',
                                        'headerOptions' => [
                                            'width' => '100',
                                            'style' => 'text-align: center'
                                        ],
                                        'contentOptions' => [
                                            'width' => '60px',
                                            'height' => '60px',
                                            'style' => 'text-align: center'
                                        ],
                                        'value' => function ($model) {
                                            if ($model->avatar == null || !file_exists(Yii::$app->basePath . '/web/uploads/avatar/70x70/' . $model->avatar)) {
                                                $avatar = '/local/default/avatar-default.png';
                                            } else {
                                                $avatar = '/uploads/avatar/70x70/' . $model->avatar;
                                            }
                                            return Html::img($avatar);
                                        }
                                    ],
                                    [
                                        'attribute' => 'full_name',
                                        'headerOptions' => [
                                            'width' => '200',
                                        ],
                                        'format' => 'html',
                                        'contentOptions' => [
                                            'class' => 'edittable-customer',
                                            'data-field' => 'fullname',
                                        ],
                                        'value' => function ($model) {
                                            $name = $model->full_name == null ? $model->name : $model->full_name;
                                            return Html::a($name, ['customer-view', 'id' => $model->id], []);
                                        },
                                    ],
                                    [
                                        'attribute' => 'phone',
                                        'format' => 'raw',
                                        'headerOptions' => [
                                            'width' => '150',
                                        ],
                                        'value' => function($model){
                                            return '<div class="td-phone">' .
                                                Html::a(
                                                    '<button class="btn btn-success white btn-sm"><i class="fa fa-phone"></i></button>',
                                                    'javascript:void(0)',
                                                    [
                                                        'onclick' => 'return typeof mycall == \'object\' ? mycall.makeCall(\'' . $model->phone . '\') : toastr.warning("Không thể kết nối đến hệ thống gọi")',
                                                        'title' => 'Gọi'
                                                    ]
                                                ) .
                                                Html::button('<i class="fa fa-copy"></i>', [
                                                    'class' => 'btn btn-primary white btn-sm copy-data',
                                                    'data-phone' => $model->phone,
                                                    'data-copy' => '{"el_copy":"attr-copy","data_copy":"data-phone","success":"toastr.success(\"Đã copy số điện thoại!\")"}',
                                                    'data-pjax' => 0,
                                                    'title' => 'Copy số điện thoại'
                                                ]);
                                        }
                                    ],
                                    [
                                        'attribute' => 'customer_code',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return $model->customer_code;
                                        },
                                        'headerOptions' => [
                                            'width' => '150',
                                        ]
                                    ],
                                    [
                                        'class' => \common\grid\EnumColumn::class,
                                        'attribute' => 'sex',
                                        'value' => function ($model) {
                                            switch ($model->sex) {
                                                case 1:
                                                    $result = 'Nam Giới';
                                                    break;
                                                case 0:
                                                    $result = 'Nữ Giới';
                                                    break;
                                                case 2:
                                                    $result = 'Chưa xác định';
                                                    break;
                                                default:
                                                    $result = 'Chưa xác định';
                                                    break;
                                            }

                                            return $result;
                                        },
                                        'enum' => Dep365CustomerOnline::getSex(),
                                        'filter' => Dep365CustomerOnline::getSex(),
                                        'filterInputOptions' => [
                                            'class' => 'ui dropdown form-control'
                                        ],
                                    ],
                                    [
                                        'class' => \common\grid\EnumColumn::class,
                                        'attribute' => 'status',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            if ($model->status == '1') {
                                                return '<span class="dat-hen">Đặt hẹn</span>';
                                            }
                                            if ($model->status == '6') {
                                                return '<span class="status-tiem-nang">Tiềm năng</span>';
                                            }
                                            if ($model->status == '2') {
                                                return '<span class="status-fail">Fail</span>';
                                            }
                                            if ($model->status == '3') {
                                                return '<span class="status-kobm">KBM</span>';
                                            }
                                            if ($model->status == '4') {
                                                return '<span class="status-so-ao">Số ảo</span>';
                                            }
                                            if ($model->status == '5') {
                                                return '<span class="status-underfine">Chưa xác định</span>';
                                            }
                                        },
                                        'enum' => Dep365CustomerOnline::getStatusCustomerOnline(),
                                        'filter' => Dep365CustomerOnline::getStatusCustomerOnline(),
                                        'filterInputOptions' => [
                                            'class' => 'ui dropdown form-control'
                                        ],
                                        'headerOptions' => [
                                            'width' => 180,
                                        ],
                                    ],
                                    [
                                        'class' => \common\grid\EnumColumn::class,
                                        'attribute' => 'dat_hen',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $dathen = new Dep365CustomerOnlineDathenStatus();
                                            $data = $dathen->getOneDatHenStatus($model->dat_hen);
                                            if ($data == null) {
                                                return null;
                                            }
                                            if ($data->id == 1) {
                                                return '<span class="da-den">' . $data->name . '</span>';
                                            } elseif ($data->id == 2) {
                                                return '<span class="khong-den">' . $data->name . '</span>';
                                            } else {
                                                return $data->name;
                                            }
                                        },
                                        'enum' => Dep365CustomerOnline::getStatusDatHen(),
                                        'filter' => Dep365CustomerOnline::getStatusDatHen(),
                                        'filterInputOptions' => [
                                            'class' => 'ui dropdown form-control'
                                        ],
                                        'headerOptions' => [
                                            'width' => 180,
                                        ],
                                    ],
                                    [
                                        'class' => \common\grid\EnumColumn::class,
                                        'attribute' => 'co_so',
                                        'value' => 'coSoHasOne.name',
                                        'enum' => Dep365CustomerOnline::getCoSoDep365(),
                                        'filter' => Dep365CustomerOnline::getCoSoDep365(),
                                        'filterInputOptions' => [
                                            'class' => 'ui dropdown form-control'
                                        ],
                                        'headerOptions' => [
                                            'width' => 100,
                                        ],
                                    ],
                                    [
                                        'attribute' => 'time_lichhen',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            if ($model->time_lichhen == null) {
                                                return null;
                                            }
                                            return date('d-m-Y', $model->time_lichhen);
                                        },
                                        'headerOptions' => [
                                            'width' => '150',
                                        ]
                                    ],
                                    [
                                        'attribute' => 'customer_come_time_to',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $khachden = new Dep365CustomerOnlineCome();
                                            return $khachden->getNameTrangThaiKhachDenNotStatic($model->customer_come_time_to);
                                        },
                                    ],
                                    [
                                        'attribute' => 'customer_come',
                                        'format' => 'raw',
//                                        'value' => 'customer_come',
                                        'value' => function ($model) {
                                            if ($model->customer_come == null) {
                                                return null;
                                            }
                                            return date('d-m-Y H:i', $model->customer_come);
                                        },
                                        'filter' => \dosamigos\datepicker\DatePicker::widget([
                                            'model' => $searchModel,
                                            'attribute' => 'customer_come',
                                            'language' => 'vi',
                                            'clientOptions' => [
                                                'autoclose' => true,
                                                'format' => 'dd-mm-yyyy',
                                            ]
                                        ]),
                                        'headerOptions' => [
                                            'width' => 180,
                                        ],
                                    ],
                                    [
                                        'class' => \common\grid\EnumColumn::class,
                                        'attribute' => 'permission_user',
                                        'value' => function ($model) {
                                            $userProfile = new Dep365CustomerOnline();

                                            return $userProfile->getNhanVienTuVan($model->permission_user);
                                        },
                                        'enum' => Dep365CustomerOnline::getNhanVienTuVanFilter(),
                                        'filter' => Dep365CustomerOnline::getNhanVienTuVanFilter(),
                                        'filterInputOptions' => [
                                            'class' => 'ui dropdown form-control'
                                        ],
                                        'headerOptions' => [
                                            'width' => 200,
                                        ],
                                    ],
                                    [
                                        'attribute' => 'id_dich_vu',
                                        'value' => function ($model) {
                                            if ($model->id_dich_vu == null) {
                                                return null;
                                            }
                                            if ($model->dichVuOnlineHasOne != null) {
                                                return $model->dichVuOnlineHasOne->name;
                                            }

                                            return null;
                                        }
                                    ],
                                    [
                                        'attribute' => 'customer_come_time_to',
                                        'value' => function ($model) {
                                            return \backend\modules\customer\models\Dep365CustomerOnlineCome::getNameTrangThaiKhachDen($model->customer_come_time_to);
                                        },
                                        'format' => 'raw',
                                    ],
                                    [
                                        'attribute' => 'directSale',
                                        'value' => function ($model) {
                                            if ($model->directsale == null) {
                                                return null;
                                            }
                                            if ($model->directSaleHasOne != null) {
                                                return $model->directSaleHasOne->fullname;
                                            }

                                            return null;
                                        }
                                    ],
                                    [
                                        'attribute' => 'created_at',
                                        'format' => 'date',
                                        'value' => 'created_at',
                                        'filter' => \dosamigos\datepicker\DatePicker::widget([
                                            'model' => $searchModel,
                                            'attribute' => 'created_at',
                                            'template' => '{input}{addon}',
                                            'addon' => '<i class="fa fa-calendar"></i>',
                                            'language' => 'vi',
                                            'clientOptions' => [
                                                'autoclose' => true,
                                                'todayHighlight' => true,
                                                'format' => 'dd-mm-yyyy',
                                            ],
                                            'options' => [
                                                'autocomplete' => 'off',
                                            ]
                                        ]),
                                        'filterInputOptions' => [
                                            'class' => 'ui dropdown form-control'
                                        ],
                                        'headerOptions' => [
                                            'width' => 180,
                                        ],
                                    ],
                                ],
                            ]); ?>
                        </div>
                        <?php Pjax::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$urlChangePageSize = \yii\helpers\Url::toRoute(['/quan-ly/perpage']);

$script = <<< JS
var quanly = new myGridView();
quanly.init({
    pjaxId: '#quanly-customer-ajax',
    urlChangePageSize: '$urlChangePageSize'
});

JS;
$this->registerJs($script, \yii\web\View::POS_END);
?>
