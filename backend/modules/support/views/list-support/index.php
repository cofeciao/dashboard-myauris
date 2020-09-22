<?php

use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\Url;

$this->title = Yii::t('backend', 'Hỗ trợ - ' . $catagory->name);
?>

<section id="list-support">

    <div class="card">
        <?php
        echo $this->render('_search', ['model' => $searchModel]);
        ?>
    </div>

    <div class="col-12 mt-2 mb-1">
        <h4 class="text-uppercase text-bold-600 font-size-large"><?= $catagory->name ?></h4>
    </div>

    <?php Pjax::begin([
        'id' => 'list-support-ajax', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'POST']
    ]) ?>

    <?= ListView::widget([
        'dataProvider' => $listDataProvider,
        'options' => [
            'tag' => 'div',
            'class' => 'col-12',
            'id' => 'list-sp'
        ],
        'layout' => '<div class="row">{items}</div>{summary}{pager}',
        'itemView' => '_listSupportGrid',
        'itemOptions' => [
            'tag' => false,
        ],
    ]);
    ?>

    <?php Pjax::end() ?>
</section>

<?php
$url = Url::toRoute(['/support/list-support/search']);
$urlToRoute = Url::toRoute(['view']);

$script = <<< JS
    $('body').on('click', '#btnSubmit', function() {
        var text = $(this).closest('#filter').find('.input-filter').val();
        var c_id = $catagory->id;
        
        $(window).ajaxStart(function(){
            $('body').myLoading({fixed: true});
        });
        
        $.post("$url", {id: c_id, text: text}, function(data) {
            $('body').myUnloading();
            $('#list-sp').html(data);
        })
    });
    
    $('body').on('change paste', '.input-filter', function() {
       $('#btnSubmit').trigger('click'); 
    });
    
    $('body').on('keyup', '.input-filter', function(e) {
        var code = e.keyCode || e.which;
        
        if (code == 13) {
            $('#btnSubmit').trigger('click');
        }
    })
JS;
//$this->registerJs($script, \yii\web\View::POS_END);
?>
<style>
.form-control{padding:.75rem 1rem}
p.card-text{display:-webkit-box;-webkit-box-orient:vertical;overflow:hidden;text-overflow:ellipsis;white-space:normal;-webkit-line-clamp:4;max-height:6rem}
.btn.btn-primary{padding:.5rem}
</style>