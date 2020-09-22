<?php

use \yii\helpers\Html;
use \backend\modules\customer\models\Dep365CustomerOnlineFanpage;

?>
    <div id="mainView">
        <div id="view">
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
                                                        <span>Tổng quan về đối tượng</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end #reportHeader-toolbarSection -->
                                    <div id="reportHeader-customSection">

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
                                        <div id="overview-graphOptions" style="clear:both;padding:10px 10px 0 10px;">
                                            <form id="sub_filter">
                                                <div class="submain_filter hidden">
                                                    <div class="item-subfilter">
                                                        <div class="input-group">
                                                            <?php $arr = array(
                                                                'Người < 18 tuổi',
                                                                '19 tuổi < Người < 36 tuổi',
                                                                'Người > 36 tuổi',

                                                            ); ?>
                                                            <?= Html::dropDownList('age', '', $arr, [
                                                                'prompt' => 'Độ tuổi',
                                                                'class' => 'subfilter dropdown ui search',
                                                            ]) ?>
                                                            <span class="input-group-addon clear-option"><span
                                                                        class="fa fa-times"></span></span>
                                                        </div>
                                                    </div>
                                                    <div class="item-subfilter">
                                                        <div class="input-group">
                                                            <?php $arr = array(
                                                                '0' => 'Nam',
                                                                '1' => 'Nữ'
                                                            ); ?>
                                                            <?= Html::dropDownList('sex', '', $arr, [
                                                                'prompt' => 'Giới tính',
                                                                'class' => 'subfilter dropdown ui search',
                                                            ]) ?>
                                                            <span class="input-group-addon clear-option"><span
                                                                        class="fa fa-times"></span></span>
                                                        </div>
                                                    </div>

                                                    <div class="item-subfilter">
                                                        <div class="input-group">
                                                            <?= Html::dropDownList(
                                                                'fanpage',
                                                                '',
                                                                Dep365CustomerOnlineFanpage::getListFanpageArray(),
                                                                [
                                                                    'prompt' => 'Fanpage',
                                                                    'class' => 'subfilter dropdown ui search',
                                                                ]
                                                            ) ?>
                                                            <span class="input-group-addon clear-option"><span
                                                                        class="fa fa-times"></span></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="reportHeader-dateControl" class="report-header-datepicker"
                                                     style="display:flex;align-items:center;margin-top:10px;margin-right:10px;">
                                                    <div class="input-group">
                                                        <input type="text" id="datepicker-container"
                                                               class="form-control">
                                                        <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <span class="fa fa-calendar"></span>
                                                        </span>
                                                        </div>
                                                    </div>
                                                </div>

                                            </form>

                                            <div style="clear:both"></div>
                                        </div>
                                        <!-- end #overview-graphOptions -->
                                        <div id="overview-graph">
                                            <canvas id="overview-graph-lineChart"></canvas>
                                        </div>
                                        <!-- end #overview-graph -->

                                        <div class="row" style="margin:20px 0 0;">
                                            <div class="col-8" id="table_overview">

                                            </div>
                                            <div class="col-4">
                                                <span style="display:block">Tổng số đóng góp:</span>
                                                <select class="form-control" name="percent_listing" id="percent_listing">
                                                    <!--                                                    <option value="dathen">Đặt Hẹn</option>-->
                                                    <!--                                                    <option value="khongdathen">Không Đặt Hẹn</option>-->
                                                    <!--                                                    <option value="den">Đến</option>-->
                                                    <!--                                                    <option value="khongden">Không Đến</option>-->
                                                    <!--                                                    <option value="lam">Làm</option>-->
                                                    <!--                                                    <option value="khonglam">Không Làm</option>-->
                                                    <option value="doanhthu" selected>Doanh Thu</option>
                                                </select>
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
$url_ajax = \yii\helpers\Url::toRoute('get-data');
$script = <<<JS
    report_url_ajax="{$url_ajax}";
JS;

$this->registerJs($script, \yii\web\View::POS_HEAD);

\backend\modules\report\assets\NhankhauhocAssets::register($this);


?>