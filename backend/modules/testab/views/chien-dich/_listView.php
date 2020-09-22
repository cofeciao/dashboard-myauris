<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 03-Apr-19
 * Time: 6:56 PM
 */

use yii\helpers\Html;

$this->registerCss('
.campaign-left .material-icons{font-size:14px}
.campaign-left .page-disabled,.campaign-left .page-link{padding:.55rem .5rem}
.campaign-left li.page-item.first.page-disabled,
.campaign-left li.page-item.last.page-disabled,
.campaign-left li.page-item.next.page-disabled,
.campaign-left li.page-item.prev.page-disabled,
.campaign-left li.page-item.first a,
.campaign-left li.page-item.last a,
.campaign-left li.page-item.next a,
.campaign-left li.page-item.prev a{padding:.5rem 0}
.campaign-left li.page-item.first.page-disabled>span,
.campaign-left li.page-item.last.page-disabled>span,
.campaign-left li.page-item.next.page-disabled>span,
.campaign-left li.page-item.prev.page-disabled>span,
.campaign-left li.page-item.first>a>span,
.campaign-left li.page-item.last>a>span,
.campaign-left li.page-item.next>a>span,
.campaign-left li.page-item.prev>a>span{padding:0 0.25rem}
');

$model = $dataProviderCD->getModels();
$first = reset($model);

echo \yii\widgets\ListView::widget([
    'dataProvider' => $dataProviderCD,
    'itemOptions' => [
        'tag' => false
    ],
    'options' => [
        'tag' => false,
        'id' => false
    ],
    'itemView' => '_item',
    'viewParams'=>['id'=>$id, 'first' => $first],
    'layout' => '<div class="campaign-list">{items}</div>{pager}',
    'pager' => [
        'firstPageLabel' => Html::tag('span', 'skip_previous', ['class' => 'material-icons']),
        'lastPageLabel' => Html::tag('span', 'skip_next', ['class' => 'material-icons']),
        'prevPageLabel' => Html::tag('span', 'play_arrow', ['class' => 'material-icons']),
        'nextPageLabel' => Html::tag('span', 'play_arrow', ['class' => 'material-icons']),
        'maxButtonCount' => 2,

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
]);
