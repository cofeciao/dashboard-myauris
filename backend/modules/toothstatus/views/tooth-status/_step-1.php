<?php

use yii\helpers\Url;

$css = <<< CSS
.tooth-status-col{margin-bottom:20px}
.tooth-status{height:100%;padding:6px 6px 12px;border:solid 2px #84077f;border-radius:10px;background:#fff;}
.tooth-status.active{box-shadow:1px 1px 2px #666}
.tooth-status-outer{display:flex;height:100%;flex-direction:column;justify-content:center;text-align:center;border:solid 1px #ccc;border-radius:5px;cursor:pointer}
.tooth-status-content{cursor:pointer}
.tooth-status-tools{text-align:center;display:flex;align-items:center;margin:0 -10px;padding:10px 10px 0}
.tooth-status-tools > .btn{font-size:11pt;width:100%;padding:6px;-webkit-border-radius:50px;-moz-border-radius:50px;border-radius:50px}
.tooth-status-tools > .btn:first-child{margin-right:1%}
.tooth-status-tools > .btn:last-child{margin-left:1%}
.status-name{font-weight:600;font-size:22pt;text-transform:uppercase}
.tooth-status:hover .status-name,.tooth-status.active .status-name{font-weight:700}
.status-image{margin-bottom:10px;padding:0;position:relative;overflow:hidden;padding-top:66.66%;width:100%;-webkit-border-radius: 10px;-moz-border-radius: 10px;border-radius: 10px;}
.status-image img{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);max-width:100%}
CSS;

$this->registerCss($css);
?>
    <div class="tooth-step step-1">
        <input type="hidden" id="ipt-tooth-status" value="">
        <div class="container">
            <div class="row align-items-center justify-content-center position-relative">
                <div class="logo"><img src="/images/logo-new.png" alt=""></div>
                <div class="step-title">Chọn tình trạng răng</div>
            </div>
            <div class="row tooth-status-container">
                <?php
                if (isset($listTinhTrangRang) && is_array($listTinhTrangRang)) {
                    foreach ($listTinhTrangRang as $tinhTrangRang) {
                        ?>
                        <div class="col-4 tooth-status-col">
                            <div class="tooth-status" data-status="<?= $tinhTrangRang->primaryKey ?>">
                                <div class="tooth-status-info">
                                    <div class="tooth-status-content text-center">
                                        <div class="tooth-status-inner">
                                            <?php if ($tinhTrangRang->image != null) { ?>
                                                <div class="status-image">
                                                    <img class="img-fluid"
                                                         src="<?=
                                                         $tinhTrangRang->image != null ?
                                                             Yii::getAlias('@frontendUrl') . '/uploads/rang/tinh-trang-rang/300x300/' . $tinhTrangRang->image :
                                                             Yii::getAlias('@frontendUrl') . '/images/ico/favicon.png' ?>"
                                                         alt="<?= $tinhTrangRang->name ?>"/>
                                                </div>

                                                <div class="status-name"><?= $tinhTrangRang->name ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="tooth-status-tools">
                                    <div class="btn btn-pink next-step" data-step="2">Xem kỹ thuật</div>
                                    <?php if ($tinhTrangRang->count > 0) { ?>
                                        <div class="btn btn-pink next-step next-to-step-3" data-step="3">Tiếp theo
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                } ?>
            </div>
        </div>
        <div class="step-1-button-group text-center mt-2 mb-4">
            <button type="button" class="btn btn-primary next-step d-none" data-step="2">Xem kỹ thuật</button>
            <button type="button" class="btn btn-success next-step next-to-step-3" style="display: none;" data-step="3">
                Tiếp theo
            </button>
        </div>
    </div>
<?php
$script = <<< JS
    data.step = 1;
    dataStep.push(1);
    localStorage.setItem('data', JSON.stringify(data));
    if(![null, undefined].includes(data.status)){
        $('#ipt-tooth-status').val(data.status);
        $('.tooth-status[data-status="'+ data.status +'"]').addClass('active');
    }
    $('.tooth-step').on('click tap', '.tooth-status-info', function(){
        $(this).closest('.tooth-status').find('.tooth-status-tools > .btn:last-child').trigger('click');
    }).on('click tap', '.next-step', function(){
        var data_status = $(this).closest('.tooth-status').attr('data-status') || null,
            data_step = $(this).attr('data-step') || 2;
        if(data_status !== null){
            if(data.status !== data_status){
                data.treatment = null;
                data.age = null;
                data.choose = null;
                data.service = null;
            }
            data.prevStep = data.step;
            data.status = data_status;
            localStorage.setItem('data', JSON.stringify(data));
            loadStep(data_step, {status: data_status});
            return false;
        }
        toastr.error('Vui lòng chọn tình trạng');
        return false;
    });
JS;
$this->registerJs($script);
