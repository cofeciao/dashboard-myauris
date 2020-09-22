<?php

use yii\helpers\Html;
use common\models\User;
use yii\helpers\ArrayHelper;
use backend\modules\setting\models\Dep365CoSo;
use backend\modules\clinic\models\PhongKhamDichVu;
use backend\modules\clinic\models\PhongKhamSanPham;
use backend\modules\customer\models\Dep365CustomerOnlineFanpage;

$urlGetData      = \yii\helpers\Url::toRoute('getdata');
//echo '<pre>';
//print_r($urlGetData);
//echo '</pre>';
$urlGetSubfilter = \yii\helpers\Url::toRoute('getsubfilter');
//echo '<pre>';
//print_r($urlGetSubfilter);
//echo '</pre>';
$style = <<<CSS
form#sub_filter .item-subfilter{
	display:inline-block;
}
.reload-data {
    width: 30px;
    text-align: center;
    cursor: pointer;
}
CSS;

$this->registerCss($style);


?>
<div id="mainView">
    <div id="view" data-url="<?php echo $urlGetData ?>">
        <div style="background-color:#fff;min-width:600px;vertical-align:top;width:100%;border-radius:2px;">
            <div id="reportContainer">
                <div id="reportLoading" style="display:none"></div>
                <div id="report">
                    <div class="reportContent">
                        <div style="margin-bottom:5px;min-height:50px;">
                            <div id="reportHeader">
                                <div id="reportHeader-toolbarSection">
                                    <div id="reportHeader-reportToolbar" class="report-header-toolbar-simple">
                                        <div class="reporttoolbar-simple">
                                            <div style="align-items:center;display:flex;justify-content:flex-start">
                                                <div id="reportHeader-reportToolbar-title">
                                                    <span>Tổng quan đề xuất chi</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end #reportHeader-toolbarSection -->
                                <div id="reportHeader-customSection">
                                    <div class="report-header-widget">
                                        <div class="report-header-widget-left">
                                            <div class="segmentContext">
                                                <div id="reportHeader-segmentHeader">
                                                    <div class="segmentHeader selectSegments">
                                                        <div class="viewRoot" style="display:inline-block">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="report-header-widget-right">
                                            <div id="reportHeader-dateControl" class="report-header-datepicker"
                                                 style="display:flex;align-items:center;margin-top:10px;margin-right:10px;">
                                                <div class="input-group" style="width: 30px">
                                                    <div class="reload-data">
                                                        <i class="fa fa-refresh"></i>
                                                    </div>
                                                </div>
                                                <div class="input-group">
                                                    <input type="text" id="datepicker-container"
                                                           class="form-control">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <span class="fa fa-calendar"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="date-compare-selector"
                                                     style="float:left;display:flex;align-items:center;width:100%">
                                                    <label style="margin:0 10px;white-space:nowrap">
                                                        <input type="checkbox" name="" class="date-compare-mode">
                                                        So sánh với
                                                    </label>
                                                    <select name="datecontrol-compare-selector"
                                                            class="ui dropdown form-control" disabled
                                                            style="line-height:1.25">
                                                        <option value="0">---------</option>
                                                        <option value="1">Kỳ trước đó</option>
                                                        <option value="2">Tháng trước đó</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end #reportHeader-customSection -->
                            </div>
                            <!-- end #reportHeader -->
                        </div>

                        <div id="tabControl">
                            <div style="border-bottom:1px solid #ccc;clear:both;margin-top:3px;">
                                <div style="padding-left:10px">
                                    <span class="overview action-change">
                                        <span style="color:#222;cursor:auto">Tổng quan</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="tabContainer">
                            <div id="tab">

                                <div style="clear:both;padding:0 10px 10px">
                                    <div id="overview-graphOptions"
                                         style="display:flex;clear:both;padding:10px 10px 0 10px;">
                                        <!--<form id="sub_filter">
                                            <div class="item-subfilter">
                                                <div class="input-group">
                                                    <?/*= Html::dropDownList(
    'fanpage',
    '',
    Dep365CustomerOnlineFanpage::getListFanpageArray(),
    [
                                                            'prompt' => 'Fanpage',
                                                            'class'  => 'subfilter dropdown ui search',
                                                        ]
) */?>
                                                    <span class="input-group-addon clear-option"><span
                                                                class="fa fa-times"></span></span>
                                                </div>
                                            </div>
                                            <div class="item-subfilter">
                                                <div class="input-group">
                                                    <?/*= Html::dropDownList('coso', '', Dep365CoSo::getCoSoArray(), [
                                                        'prompt' => 'Cơ Sở',
                                                        'class'  => 'subfilter dropdown ui search',
                                                    ]) */?>
                                                    <span class="input-group-addon clear-option"><span
                                                                class="fa fa-times"></span></span>
                                                </div>
                                            </div>
                                            <div class="item-subfilter">
                                                <div class="input-group">
                                                    <?/*= Html::dropDownList(
                                                        'sanpham',
                                                        '',
                                                        ArrayHelper::map(PhongKhamSanPham::getSanPham(), 'id', 'name'),
                                                        [
                                                            'prompt' => 'Sản Phẩm',
                                                            'class'  => 'subfilter dropdown ui search',
                                                        ]
                                                    ) */?>
                                                    <span class="input-group-addon clear-option"><span
                                                                class="fa fa-times"></span></span>
                                                </div>
                                            </div>
                                            <div class="item-subfilter">
                                                <div class="input-group">
                                                    <?/*= Html::dropDownList(
                                                            'dichvu',
                                                            '',
                                                            ArrayHelper::map(PhongKhamDichVu::getDichVu(), 'id', 'name'),
                                                            [
                                                            'prompt' => 'Dịch Vụ',
                                                            'class'  => 'subfilter dropdown ui search',
                                                        ]
                                                        ) */?>
                                                    <span class="input-group-addon clear-option"><span
                                                                class="fa fa-times"></span></span>
                                                </div>
                                            </div>
                                            <div class="item-subfilter">
                                                <div class="input-group">
                                                    <?/*= Html::dropDownList(
                                                            'direct_sale',
                                                            '',
                                                            ArrayHelper::map(
                                                            User::getNhanVienTuDirectSale(),
                                                            'id',
                                                            'fullname'
                                                        ),
                                                            [
                                                            'prompt' => 'Direct Sales',
                                                            'class'  => 'subfilter dropdown ui search',
                                                        ]
                                                        ) */?>
                                                    <span class="input-group-addon clear-option"><span
                                                                class="fa fa-times"></span></span>
                                                </div>
                                            </div>
                                            <div class="item-subfilter">
                                                <div class="input-group">
                                                    <?/*= Html::dropDownList(
                                                            'online_sale',
                                                            '',
                                                            ArrayHelper::map(
                                                            User::getNhanVienTuVanOnline([User::STATUS_ACTIVE]),
                                                            'id',
                                                            'fullname'
                                                        ),
                                                            [
                                                            'prompt' => 'Online Sales',
                                                            'class'  => 'subfilter dropdown ui search',
                                                        ]
                                                        ) */?>
                                                    <span class="input-group-addon clear-option"><span
                                                                class="fa fa-times"></span></span>
                                                </div>
                                            </div>
                                        </form>-->
                                        <div style="clear:both"></div>

                                    </div>
                                    <!-- end #overview-graphOptions -->
                                    <div id="overview-graph">
                                        <canvas id="overview-graph-lineChart"></canvas>
                                        <!--<div id="overview-graph-lineChart"></div>-->
                                    </div>
                                    <!-- end #overview-graph -->
                                    <div class="row" style="margin:20px 0 0;">
                                        <div class="col-8">
                                            <div id="overview-dimensionSummary">
                                                <!-- end #overview-dimensionSummary-miniTable -->
                                            </div>
                                            <!-- end #overview-dimensionSummary -->
                                        </div>
                                        <div class="col-4">
                                            <h4>Biểu Đồ Phần Trăm Top 5 Các Khu Vực</h4>
                                            <canvas id="overview-miniPie"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$url_ajax = \yii\helpers\Url::toRoute('getsubfilter');
$script   = <<<JS
    report_url_ajax="{$url_ajax}";
JS;

$this->registerJs($script, \yii\web\View::POS_HEAD);

\backend\modules\chi\assets\BaoCaoChiAssets::register($this);
?>
