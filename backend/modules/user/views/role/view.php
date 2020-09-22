<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\modules\user\models\RbacAuthItem;

/* @var $this yii\web\View */
/* @var $model backend\modules\user\models\RbacAuthItem */

$this->title = $model->description;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'User'), 'url' => ['/user']];
$this->params['breadcrumbs'][] = ['label' => 'Phân Quyền', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section id="dom">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><?= $this->title; ?></h4>
                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a class="block-page"
                                   onclick='window.location="<?= \Yii::$app->getRequest()->getUrl(); ?>"'><i
                                            class="ft-rotate-cw"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            <li><a data-action="close"><i class="ft-x"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body card-dashboard">

                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'name',
                                'description:html',
                                'ruleName',
                                'data',
                            ],
                        ]) ?>
                        <p>
                            <?php
                            if (in_array('loginToBackend', array_keys(Yii::$app->authManager->getPermissionsByRole($model->name)))) {
                                echo '<span style="color:red">' . $model->description . ': có quyền truy cập backend</span>';
                            } else {
                                echo '<span style="color:red">' . $model->description . ': không có quyền truy cập backend</span>';
                            }
                            ?>
                        </p>
                    </div>

                    <div class="card-body card-dashboard user-permission">
                        <section class="without-filter">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Phân Quyền</h4>
                                            <a class="heading-elements-toggle"><i
                                                        class="icon-ellipsis font-medium-3"></i></a>
                                            <div class="heading-elements">
                                                <ul class="list-inline mb-0">
                                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                                    <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                                    <li><a data-action="close"><i class="ft-x"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <?php
                                        $permission = Yii::$app->authManager->getPermissions();
                                        $permission_user = array_keys(Yii::$app->authManager->getPermissionsByRole($model->name));
                                        ?>
                                        <div class="card-content collapse show ">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <select multiple="multiple" size="20" class="duallistbox"
                                                            id="duallistbox"
                                                            data-name="<?php echo $model->name; ?>">
                                                    </select>
                                                </div>
                                                <div style="color: red" id="permission-rerult"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<?php
$urlPermissionForrole = \yii\helpers\Url::toRoute(['/user/role/permission-for-role']);
$urlPermissionChange = \yii\helpers\Url::toRoute(['/user/role/permission-change']);
$script = <<< JS
    var e = document.getElementById('duallistbox');
    var name = e.getAttribute('data-name');
    $('.user-permission').block({
            message: '<div class="semibold"><span class="ft-refresh-cw icon-spin text-left"></span> <br>Loading...</div>',
            overlayCSS: {
                backgroundColor: '#FFF',
                opacity: 1,
                cursor: 'wait'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'transparent'
            }
        });
    $.ajax({
        url: '$urlPermissionForrole',
        method: "GET",
        dataType: "json",
        data:{"name": name},
        success: function (data) {
            $.each(data, function (i, val) {
                var opt  = "<option value=\'" + val.name + "\' " + val.selected + ">" + val.description + "</option>";
                $(".duallistbox").append(opt);
            });
            
            $('.duallistbox').bootstrapDualListbox({
                moveOnSelect: false,
                infoTextEmpty: 'Danh sách rỗng',
                infoText: 'Hiện có {0} quyền',
                moveAllLabel: 'Chọn tất cả',
                removeAllLabel: 'Xóa bỏ tất cả',
                filterPlaceHolder: 'Tìm kiếm',
                nonSelectedListLabel: 'Quyền đang có',
                selectedListLabel: 'Quyền đã có',
            });
            $('.user-permission').unblock();
        }
    });
    $('.duallistbox').on('change', function(e){
        $('.user-permission').block({
            message: '<div class="semibold"><span class="ft-refresh-cw icon-spin text-left"></span> <br>Loading...</div>',
            overlayCSS: {
                backgroundColor: '#FFF',
                opacity: 1,
                cursor: 'wait'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'transparent'
            }
        });
        var item = $(this).val();
        var name = $(this).attr("data-name");
        var result = '';
        var options = $('#duallistbox').find('option');
        var c = true;
        if(item.length > 0 && item.length == options.length) c = confirm('Bạn muốn cấp toàn bộ quyền cho người dùng?');
        if(c){
            $.ajax({
                url: '$urlPermissionChange',
                method: "POST",
                dataType: "text",
                data:{"name": name, "item":item},
                success: function (data) {
                    if(data == 1) {
                        result = 'Thành công';
                    } else if(data == 0) {
                        result = "Hãy liên hệ ban quản trị";
                    }
                    $('#permission-rerult').html(result);
                    $('.user-permission').unblock();
                }
            }).fail(f => {
                $('#permission-rerult').html('Failed');
                $('.user-permission').unblock();
            });
        } else {
            window.location.reload();
        }
    });
JS;

$this->registerCssFile('/vendors/css/forms/listbox/bootstrap-duallistbox.min.css');
$this->registerCssFile('/css/plugins/forms/dual-listbox.css');

$this->registerJsFile('/vendors/js/forms/listbox/jquery.bootstrap-duallistbox.min.js');
$this->registerJs($script, \yii\web\View::POS_END);

?>

