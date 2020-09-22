<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 03-Apr-19
 * Time: 6:38 PM
 */

use yii\helpers\Html;
use yii\helpers\Url;

?>

    <p class="<?php if ($model->id == $id) {
    echo 'active';
} ?> <?= $model->id == $first->id ? "current" : '' ?>">
        <?= Html::a($model->name, ['/testab/chien-dich', 'idCD' => $model->id], ['class' => 'campaign-info']); ?>
        <?= Html::a('<i class="ft-edit blue"></i>', 'javascript:void(0)', [
            'title' => 'Cập nhật chiến dich',
            'class' => 'campaign-edit',
            'data-pjax' => 0,
            'data-toggle' => 'modal',
            'data-backdrop' => 'static',
            'data-keyboard' => false,
            'data-target' => '#custom-modal',
            'onclick' => 'showModal($(this), "'.\yii\helpers\Url::toRoute(['/testab/chien-dich/update-ajax', 'id' => $model->id]).'");return false;',
        ])?>
        <?php
        $user = new \backend\modules\user\models\User();
        $roleUser = $user->getRoleName(Yii::$app->user->id);
        if ($roleUser == \common\models\User::USER_DEVELOP) {
            echo Html::a('<i class="ft-trash-2 red confirm-color" data-id = "' . json_encode([$model->id]) . '"></i>', '#', ['data-pjax' => 0, 'class' => 'campaign-delete']);
        } ?>
    </p>

<?php
$urlDelete = \yii\helpers\Url::toRoute(['/testab/chien-dich/delete']);
$tit = Yii::t('backend', 'Notification');

$deleteSuccess = Yii::$app->params['delete-success'];
$deleteDanger = Yii::$app->params['delete-danger'];

$data_title = Yii::t('backend', 'Are you sure?');
$data_text = Yii::t('backend', 'If delete, you will not be able to recover!');

$script = <<< JS
$(document).ready(function () {
    $('body').on('click', '.confirm-color', function (e) {
        e.preventDefault();
        var id = JSON.parse($(this).attr("data-id"));
        var table = $(this).parent().parent();
        try{
            swal({
                title: "$data_title",
                text: "$data_text",
                icon: "warning",
                showCancelButton: true,
                buttons: {
                    cancel: {
                        text: "No, cancel plx!",
                        value: null,
                        visible: true,
                        className: "btn-warning",
                        closeModal: true,
                    },
                    confirm: {
                        text: "Yes, delete it!",
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: true
                    }
                }
            }).then(isConfirm => {
                if (isConfirm) {
                    $.ajax({
                        type: "POST",
                        cache: false,
                        data:{"id":id},
                        url: "$urlDelete",
                        dataType: "json",
                        success: function(data){
                            if(data.status == 'success') {
                               toastr.success('$deleteSuccess', '$tit');
                               table.slideUp("slow");
                            }
                            if(data.status == 'failure')
                               swal("NotAllow", "$deleteDanger", "error");
                            if(data.status == 'exception')
                              swal("NotAllow", "$deleteDanger", "error");
                        }
                    });

                }

            });
        } catch(e)
        {
            alert(e); //check tosee any errors
        }
    });
    $('body').on('click', '.campaign-info', function(){
        var url = $(this).attr('href');
        window.history.pushState('', '', url);
    })
});
JS;

$this->registerJs($script, \yii\web\View::POS_END);
?>