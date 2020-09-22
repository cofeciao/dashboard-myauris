<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 20-May-19
 * Time: 1:41 PM
 */

use yii\widgets\Pjax;
use yii\helpers\Html;

$this->title = 'Log Call Vht';
?>

<section id="dom">
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
                <div class="card-content collapse show">
                    <div class="card-body card-dashboard">
                        <div style="margin-top:5px;border:1px solid #ccc;border-radius:3px">
                            <?php //Pjax::begin(['id' => 'vht-call-log', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]);?>
                            <div id="vht-call-log">
                                <div id="w0" class="grid-view">
                                    <div class="pane-single-table">
                                        <div id="listCampaign" class="cp-grid cp-widget pane-hScroll">
                                            <div class="grid-header">
                                                <table>
                                                    <colgroup>
                                                        <col>
                                                        <col>
                                                        <col>
                                                        <col>
                                                        <col>
                                                        <col>
                                                        <col>
                                                        <col>
                                                        <col>
                                                        <col>
                                                        <col>
                                                    </colgroup>
                                                    <thead>
                                                    <tr>
                                                        <th width="60" rowspan="2">STT</th>
                                                        <th width="80" rowspan="2">Hướng</th>
                                                        <th width="100">Gọi từ số</th>
                                                        <th width="80">Máy nhánh</th>
                                                        <th width="100">Gọi đến số</th>
                                                        <th width="80" rowspan="2">Thời gian</th>
                                                        <th width="50" rowspan="2">Ghi âm</th>
                                                        <th width="120" rowspan="2">Dung lượng</th>
                                                        <th rowspan="2">Bắt đầu</th>
                                                        <th rowspan="2">Kết nối</th>
                                                        <th rowspan="2">Kết thúc</th>
                                                    </tr>
                                                    <tr id="w0-filters" class="filters">
                                                        <td class="d-none"></td>
                                                        <td class="d-none"></td>
                                                        <td>
                                                            <?= Html::input('text', 'from_number', Yii::$app->request->get('from_number'), [
                                                                'class' => 'form-control'
                                                            ]) ?>
                                                        </td>
                                                        <td>
                                                            <?= Html::input('text', 'to_extension', Yii::$app->request->get('to_extension'), [
                                                                'class' => 'form-control'
                                                            ]) ?>
                                                        </td>
                                                        <td>
                                                            <?= Html::input('text', 'to_number', Yii::$app->request->get('to_number'), [
                                                                'class' => 'form-control'
                                                            ]) ?>
                                                        </td>
                                                        <td class="d-none"></td>
                                                        <td class="d-none"></td>
                                                        <td class="d-none"></td>
                                                        <td class="d-none"></td>
                                                        <td class="d-none"></td>
                                                        <td class="d-none"></td>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                            <div class="grid-content my-content pane-vScroll"
                                                 data-minus='{"0":42,"1":".header-navbar","2":".btn-add-campaign","3":".pager-wrap","4":".footer","5":".card-header"}'>
                                                <table>
                                                    <colgroup>
                                                        <col>
                                                        <col>
                                                        <col>
                                                        <col>
                                                        <col>
                                                        <col>
                                                        <col>
                                                        <col>
                                                        <col>
                                                        <col>
                                                        <col>
                                                    </colgroup>
                                                    <tbody>
                                                    <?php
                                                    if ($model != null && is_array($model->items) && count($model->items) > 0) {
                                                        $stt = ($currentPage - 1) * $pageSize + 1;
                                                        foreach ($model->items as $item) {
                                                            ?>
                                                            <tr>
                                                                <td><?= $stt ?></td>
                                                                <td>
                                                                    <?php
                                                                    if ($item->direction == 1) {
                                                                        echo 'Gọi vào';
                                                                    } elseif ($item->direction == 3) {
                                                                        echo 'Gọi ra';
                                                                    } else {
                                                                        echo '-';
                                                                    } ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    if ($item->from_number) {
                                                                        echo $item->from_number;
                                                                    } else {
                                                                        echo '-';
                                                                    } ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    if ($item->to_extension) {
                                                                        echo $item->to_extension;
                                                                    } else {
                                                                        echo '-';
                                                                    } ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    if ($item->to_number) {
                                                                        echo $item->to_number;
                                                                    } else {
                                                                        echo '-';
                                                                    } ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    if ($item->duration == 0) {
                                                                        echo '-';
                                                                    } else {
                                                                        echo \common\helpers\MyHelper::SecondsToTime($item->duration);
                                                                    } ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    if ($item->record_file_size == 0) {
                                                                        echo '-';
                                                                    } else {
                                                                        echo Html::button('<i class="fa fa-play-circle"></i>', [
                                                                            'class' => 'btn btn-sm btn-primary',
                                                                            'data-toggle' => 'popover',
                                                                            'data-content' => '<iframe src =\'' . $item->recording_url . '\'></iframe>',
                                                                            'data-html' => 'true',
                                                                            'data-placement' => 'bottom'
                                                                        ]);
                                                                    } ?>
                                                                </td>
                                                                <td><?= \common\helpers\MyHelper::NumberToByte($item->record_file_size); ?></td>
                                                                <td><?= date('d-m-Y H:i:s', $item->time_started); ?></td>
                                                                <td><?= date('d-m-Y H:i:s', $item->time_connected); ?></td>
                                                                <td><?= date('d-m-Y H:i:s', $item->time_ended); ?></td>
                                                            </tr>
                                                            <?php
                                                            $stt++;
                                                        }
                                                    } else {
                                                        ?>
                                                        <tr>
                                                            <td colspan="11">
                                                                <div class="empty">Không tìm thấy kết quả nào.</div>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                        $total = $model != null ? $model->total : 0;
                                    ?>
                                    <div class="pager-wrap clearfix">
                                        <div class="summary pull-right">
                                            Trình bày
                                            <b><?= number_format(($currentPage - 1) * $pageSize + 1, 0, '', '.') ?>
                                                -<?= number_format($currentPage * $pageSize, 0, '', '.') ?></b> trong số
                                            <b><?= number_format($total, 0, '', '.') ?></b> mục
                                        </div>
                                        <div class="pull-right mr-2">
                                            <?= Html::input('text', 'go-to-page', $currentPage, [
                                                'class' => 'go-to-page'
                                            ]) ?> / <?= ceil($total / $pageSize) ?>
                                        </div>
                                        <div class="pageSize pull-right mr-1">
                                            <?= Html::dropDownList('', $pageSize, [
                                                10 => 10,
                                                20 => 20,
                                                50 => 50,
                                            ], [
                                                'class' => 'page-size-widget ui dropdown selection upward',
                                                'id' => 'page-size-widget'
                                            ]) ?>
                                            <label for="SmallSelect">dữ liệu</label>
                                        </div>
                                        <?= \yii\widgets\LinkPager::widget([
                                            'pagination' => $pages,
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
                                        ]);
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php //Pjax::end();?>

                            <?php /*Pjax::begin(['id' => 'vht-call-log', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]); ?>
                            <?= \common\grid\MyGridView::widget([
                                'dataProvider' => $dataProvider,
//                                'filterModel' => $searchModel,
                                'filterSelector' => 'select[name="per-page"]',
                                'layout' => '{errors} <div class="pane-single-table">{items}</div><div class="pager-wrap clearfix">{summary}' .
                                    Yii::$app->controller->renderPartial('@backend/views/layouts/my-gridview/_goToPage', ['totalPage' => $totalPage, 'currentPage' => Yii::$app->request->get($dataProvider->getPagination()->pageParam)]) .
                                    Yii::$app->controller->renderPartial('@backend/views/layouts/my-gridview/_pageSize') .
                                    '{pager}</div>',
                                'tableOptions' => [
                                    'id' => 'listCampaign',
                                    'class' => 'cp-grid cp-widget pane-hScroll',
                                ],
                                'myOptions' => [
                                    'class' => 'grid-content my-content pane-vScroll',
                                    'data-minus' => '{"0":42,"1":".header-navbar","2":".btn-add-campaign","3":".pager-wrap","4":".footer","5":".card-header"}'
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
                                            'width' => 60,
                                        ],
                                    ],
                                    [
                                        'attribute' => 'direction',
                                        'label' => 'Hướng',
                                        'headerOptions' => [
                                            'width' => 80,
                                        ],
                                        'value' => function ($model) {
                                            if ($model->direction == 1) {
                                                $result = 'Gọi vào';
                                            } else if ($model->direction == 3) {
                                                $result = 'Gọi ra';
                                            } else {
                                                $result = '-';
                                            }
                                            return $result;
                                        }
                                    ],
                                    [
                                        'attribute' => 'from_number',
                                        'label' => 'Gọi từ số',
                                        'headerOptions' => [
                                            'width' => 100,
                                        ],
                                        'value' => 'from_number'
                                    ],
                                    [
                                        'attribute' => 'to_extension',
                                        'label' => 'Máy nhánh',
                                        'headerOptions' => [
                                            'width' => 80,
                                        ],
                                        'value' => 'to_extension'
                                    ],
                                    [
                                        'attribute' => 'to_number',
                                        'label' => 'Gọi đến số',
                                        'headerOptions' => [
                                            'width' => 100,
                                        ],
                                        'value' => 'to_number'
                                    ],
                                    [
                                        'attribute' => 'duration',
                                        'label' => 'Thời gian',
                                        'headerOptions' => [
                                            'width' => 80,
                                        ],
                                        'value' => function ($model) {
                                            if ($model->duration == 0) {
                                                return null;
                                            }
                                            return \common\helpers\MyHelper::SecondsToTime($model->duration);
                                        }
                                    ],
                                    [
                                        'attribute' => 'recording_url',
                                        'format' => 'raw',
                                        'label' => 'Ghi âm',
                                        'headerOptions' => [
                                            'width' => 50,
                                        ],
                                        'value' => function ($model) {
                                            if ($model->record_file_size == 0) {
                                                return null;
                                            }
                                            return '
                                                <button type="button" class="btn btn-sm btn-primary" data-toggle="popover"
                                                data-content="<iframe src =\'' . $model->recording_url . '\'></iframe>"
                                                data-html="true" data-placement="bottom">
                                                    <i class="fa fa-play-circle"></i>
                                                </button>
                                                ';
                                        },
                                        'contentOptions' => [
                                            'class' => 'align-middle'
                                        ]
                                    ],
                                    [
                                        'attribute' => 'record_file_size',
                                        'format' => 'raw',
                                        'label' => 'Dung lượng',
                                        'headerOptions' => [
                                            'width' => 120,
                                        ],
                                        'value' => function ($model) {
                                            return \common\helpers\MyHelper::NumberToByte($model->record_file_size);
                                        }
                                    ],
                                    [
                                        'attribute' => 'time_started',
                                        'label' => 'Bắt đầu',
                                        'headerOptions' => [
                                        ],
                                        'value' => function ($model) {
                                            return date('d-m-Y H:i:s', $model->time_started);
                                        }
                                    ],
                                    [
                                        'attribute' => 'time_connected',
                                        'label' => 'Kết nối',
                                        'headerOptions' => [
                                        ],
                                        'value' => function ($model) {
                                            return date('d-m-Y H:i:s', $model->time_connected);
                                        }
                                    ],
                                    [
                                        'attribute' => 'time_ended',
                                        'label' => 'Kết thúc',
                                        'headerOptions' => [
                                        ],
                                        'value' => function ($model) {
                                            return date('d-m-Y H:i:s', $model->time_ended);
                                        }
                                    ],
                                ],
                            ]); ?>
                            <?php Pjax::end();*/ ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
$currentUrl = Yii::$app->request->getUrl();
$urlChangePageSize = \yii\helpers\Url::toRoute(['/log/vht-call-log/perpage']);
$script = <<< JS
    var callLog = new myGridView(),
        currentUrl = '$currentUrl';
    jQuery('#w0').yiiGridView({"filterUrl":currentUrl,"filterSelector":"#w0-filters input, #w0-filters select","filterOnFocusOut":true});
    callLog.init({
        pjaxId: '#vht-call-log',
        urlChangePageSize: '$urlChangePageSize'
    });
JS;

$this->registerJs($script, \yii\web\View::POS_END);
$this->registerJsFile('/js/yii.gridView.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerJsFile('/js/scripts/popover/popover.min.js', ['depends' => 'yii\web\JqueryAsset']);
?>
