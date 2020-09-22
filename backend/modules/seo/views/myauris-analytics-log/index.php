<?php

use yii\helpers\Url;
use yii\helpers\Html;
use common\grid\MyGridView;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\seo\models\search\MyaurisAnalyticsLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', Yii::t('backend', 'Myauris Analytics Logs'));
$this->params['breadcrumbs'][] = $this->title;

$css = <<< CSS
.log-detail {
    display: flex;
}
.log-title {
    width: 100px;
}
.log-info {
    width: calc(100% - 100px);
}
.log-detail + .log-detail {
    border-top: dashed 1px #ccc;
    padding-top: 10px;
    margin-top: 10px;
}
.log-info p {
    margin-bottom: .5em;
}
.log-info p:last-child {
    margin-bottom: 0;
}
.log-content {
    margin-bottom: 10px;
    display: none;
}
.log-tool {
    text-align: center;
}
.log-tool .btn-hide {
    display: none;
}
CSS;
$this->registerCss($css);
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
                        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
                        <?php Pjax::begin(['id' => 'custom-pjax', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]); ?>
                        <div style="margin-top:5px;border:1px solid #ccc;border-radius:3px">
                            <?= MyGridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
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
                                    'data-minus' => '{"0":42,"1":".header-navbar","2":".btn-add-campaign","3":".pager-wrap","4":".footer"}'
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
                                //'filterModel' => $searchModel,
                                'columns' => [
                                    [
                                        'class' => 'yii\grid\SerialColumn',
                                        'header' => 'STT',
                                        'headerOptions' => [
                                            'width' => 30,
                                            'rowspan' => 2
                                        ],
                                        'filterOptions' => [
                                            'class' => 'd-none',
                                        ],
                                    ],

                                    /*[
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => 'Actions',
                                        'template' => '<div class="btn-group" role="group">{update} {delete}</div>',
                                        'buttons' => [
                                            'update' => function ($url, $model) {
                                                return Html::a('<i class="ft-edit blue"></i>', $url, ['data-pjax' => 0, 'class' => 'btn btn-default']);
                                            },
                                            'delete' => function ($url, $model) {
                                                return Html::a('<i class="ft-trash-2 red confirm-color" data-id = "' . json_encode([$model->id]) . '" ></i>', 'javascript:void(0)', ['class' => 'btn btn-default']);
                                            },
                                        ],
                                        'headerOptions' => [
                                            'width' => 100,
                                            'rowspan' => 2
                                        ],
                                        'filterOptions' => [
                                            'class' => 'd-none'
                                        ],
                                    ],*/
                                    [
                                        'attribute' => 'max_time',
                                        'format' => 'raw',
                                        'filter' => \dosamigos\datepicker\DatePicker::widget([
                                            'model' => $searchModel,
                                            'attribute' => 'max_time',
                                            'template' => '{input}{addon}',
                                            'language' => 'vi',
                                            'clientOptions' => [
                                                'autoclose' => true,
                                                'format' => 'dd-mm-yyyy',
                                                'todayHighlight' => true
                                            ],
                                            'options' => [
                                                'autocomplete' => 'off',
                                            ]
                                        ]),
                                        'value' => function ($model) {
                                            if ($model->max_time == null) return null;
                                            return date('H:i d-m-Y', $model->max_time);
                                        },
                                        'headerOptions' => [
                                            'width' => 120,
                                        ]
                                    ],
                                    [
                                        'attribute' => 'logs',
                                        'format' => 'html',
                                        'headerOptions' => [
                                            'width' => 250,
                                            'rowspan' => 2
                                        ],
                                        'filterOptions' => [
                                            'class' => 'd-none',
                                        ],
                                        'value' => function ($model) {
                                            $tmp = '[' . $model->logs . ']';
                                            $logs = json_decode($tmp);
                                            if (!is_array($logs)) return null;
                                            $arr_logs = [];
                                            $current_text = '';
                                            $current_class = '';
                                            $count = 1;
                                            foreach ($logs as $log) {
                                                if ($log->event_url != '') {
                                                    $text = ucfirst($log->event_name);
                                                    $class = ['call' => 'success', 'zalo' => 'info', 'form' => 'secondary'][strtolower($log->event_name)];
                                                } else {
                                                    $text = ucfirst($log->from_url != '' ? $log->from_url : 'direct');
                                                    $class = ['google' => 'danger', 'facebook' => 'primary', 'zalo' => 'info', '' => 'warning'][strtolower($log->from_url)];
                                                    if ($log->from_url === 'google' && strpos($log->first_url, '?gclid=') !== false) {
                                                        $text .= ' AD';
                                                        $class = 'badge bg-danger bg-darken-4';
                                                    }
                                                }
                                                if ($current_text == $text) {
                                                    $count++;
                                                    continue;
                                                } else {
                                                    if ($current_text != '') {
                                                        $arr_logs[] = [
                                                            'class' => 'badge badge-' . $current_class,
                                                            'text' => $current_text . ($count == 1 ? '' : ' x' . $count)
                                                        ];
                                                    }
                                                    $current_text = $text;
                                                    $current_class = $class;
                                                    $count = 1;
                                                }
                                            }
                                            $arr_logs[] = [
                                                'class' => 'badge badge-' . $current_class,
                                                'text' => $current_text . ($count == 1 ? '' : ' x' . $count)
                                            ];
                                            $str_logs = '';
                                            foreach ($arr_logs as $log) {
                                                $str_logs .= '<span class="' . $log['class'] . '">' . $log['text'] . '</span>';
                                            }
                                            return $str_logs;
                                        }
                                    ],
                                    [
                                        'attribute' => 'logs_detail',
                                        'format' => 'raw',
                                        'headerOptions' => [
                                            'width' => 500,
                                            'rowspan' => 2
                                        ],
                                        'contentOptions' => [
                                            'class' => 'text-left'
                                        ],
                                        'filterOptions' => [
                                            'class' => 'd-none',
                                        ],
                                        'value' => function ($model) {
                                            $tmp = '[' . $model->logs . ']';
                                            $logs = json_decode($tmp);
                                            if (!is_array($logs)) return null;
                                            $arr_logs = [];
                                            $current_title = '';
                                            $current_text = '';
                                            $current_class = '';
                                            $count = 1;
                                            foreach ($logs as $log) {
                                                if ($log->event_url != '') {
                                                    $title = ucfirst($log->event_name);
                                                    $text = '<p>' . $log->event_url . '</p><p>Thời gian: ' . date('H:i d-m-Y', $log->time) . '</p>';
                                                    $class = ['call' => 'success', 'zalo' => 'info', 'form' => 'secondary'][strtolower($log->event_name)];
                                                } else {
                                                    $title = ucfirst($log->from_url != '' ? $log->from_url : 'direct');
                                                    $text = ($title == 'Direct' ? '<p>Trang đầu: ' . $log->first_url . '</p>' : '<p>Nguồn: ' . $log->referer_url . '</p><p>Trang đầu: ' . $log->first_url . '</p>') . '<p>Thời gian: ' . date('H:i d-m-Y', $log->time) . '</p>';
                                                    $class = ['google' => 'danger', 'facebook' => 'primary', 'zalo' => 'info', '' => 'warning'][strtolower($log->from_url)];
                                                    if ($log->from_url === 'google' && strpos($log->first_url, '?gclid=') !== false) {
                                                        $title .= ' AD';
                                                        $class = 'badge bg-danger bg-darken-4';
                                                    }
                                                }
                                                if ($current_text == $text) {
                                                    $count++;
                                                    continue;
                                                } else {
                                                    if ($current_text != '') {
                                                        $arr_logs[] = [
                                                            'class' => 'badge badge-' . $current_class,
                                                            'title' => $current_title . ($count == 1 ? '' : ' x' . $count),
                                                            'text' => $current_text
                                                        ];
                                                    }
                                                    $current_title = $title;
                                                    $current_text = $text;
                                                    $current_class = $class;
                                                    $count = 1;
                                                }
                                            }
                                            $arr_logs[] = [
                                                'class' => 'badge badge-' . $current_class,
                                                'title' => $current_title . ($count == 1 ? '' : ' x' . $count),
                                                'text' => $current_text
                                            ];
                                            $str_logs = '<div class="log"><div class="log-content">';
                                            foreach ($arr_logs as $log) {
                                                $str_logs .= '<div class="log-detail"><div class="log-title"><span class="' . $log['class'] . '">' . $log['title'] . '</span></div><div class="log-info">' . $log['text'] . '</div></div>';
                                            }
                                            $str_logs .= '</div><div class="log-tool"><button class="btn btn-success">Hiện</button></div></div>';
                                            return $str_logs;
                                        }
                                    ],
                                    //'device_info:ntext',
                                    //'phone',
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
$url = Url::toRoute(['show-hide']);
$urlDelete = Url::toRoute(['delete']);
$urlChangePageSize = Url::toRoute(['perpage']);

$tit = Yii::t('backend', 'Notification');

$resultSuccess = Yii::$app->params['update-success'];
$resultDanger = Yii::$app->params['update-danger'];

$deleteSuccess = Yii::$app->params['delete-success'];
$deleteDanger = Yii::$app->params['delete-danger'];

$data_title = Yii::t('backend', 'Are you sure?');
$data_text = Yii::t('backend', 'If delete, you will not be able to recover!');

$script = <<< JS
var customPjax = new myGridView();
customPjax.init({
    pjaxId: '#custom-pjax',
    urlChangePageSize: '$urlChangePageSize',
});

$(document).ready(function () {
    $('body').on('change', '.check-toggle', function () {
        var id = $(this).val();
        $.post('$url', {id: id}, function (result) {
            if(result == 1) {
                toastr.success('$resultSuccess', '$tit');
            }
            if(result == 0) {
                toastr.error('$resultDanger', '$tit');
            }
        });
    }).on('click', '.confirm-color', function (e) {
        e.preventDefault();
        var id = JSON.parse($(this).attr("data-id"));
        var table = $(this).closest('tr');
        var currentUrl = $(location).attr('href');
        Swal.fire({
            title: "$data_title",
            text: "$data_text",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    cache: false,
                    data: {
                        "id": id
                    },
                    url: "$urlDelete",
                    dataType: "json",
                    success: function(data) {
                        if (data.status == 'success') {
                            toastr.success('$deleteSuccess', '$tit');
                            table.slideUp("slow");
                            $.pjax.reload({
                                url: currentUrl,
                                method: 'POST',
                                container: customPjax.options.pjaxId
                            });
                        }
                        if (data.status == 'failure' || data.status == 'exception')
                            toastr.error('Xoá không thành công', 'Thông báo');
                    }
                });
            }
        });
    }).on('click', '.log-tool .btn', function(){
        var btn = $(this),
            content = btn.closest('.log').find('.log-content');
        if(btn.text().trim() === 'Hiện') {
            btn.text('Ẩn').removeClass('btn-success').addClass('btn-warning');
            content.slideDown();
        } else {
            btn.text('Hiện').removeClass('btn-warning').addClass('btn-success');
            content.slideUp();
        }
    });
});
JS;
$this->registerJs($script, \yii\web\View::POS_END);
?>

