<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 21-May-19
 * Time: 9:36 AM
 */

use backend\modules\clinic\models\search\CustomerDoanhThuSearch;
use yii\helpers\Html;
use yii\helpers\Url;
use backend\components\MyComponent;

$this->registerCss('
button.dropbox-button {
    border: 1px solid #a5a5a5;
    padding: 7px 20px;
    border-radius: 5px;
}
ul.dropbox-content {
    bottom: 100%;
    width: 170px;
    margin: 0;
}
ul.dropbox-content .form-group {
    margin-bottom: 0rem;
}
');
$arr = CustomerDoanhThuSearch::getlistField();
foreach ($arr as $key => $val) {
    $$key = MyComponent::getCookies($key) !== false ? MyComponent::getCookies($key) : 1;
}
?>
    <div class="pull-right mr-1">
        <div class="dropbox-widget">
            <button type="button" class="dropbox-button btn btn-block">Tùy chỉnh</button>
            <ul class="dropbox-content">
                <?php
                foreach ($arr as $key => $item) {
                    ?>
                    <li class="form-group">
                        <label>
                            <input type="checkbox" class="custom-field" name="<?= $key; ?>"
                                <?= $$key == 1 ? 'checked' : '' ?>>
                            <?= $item; ?>
                        </label>
                    </li>
                    <?php
                }
                ?>
                <li class="text-center">
                    <button class="btn btn-danger btn-sm dropbox-submit">Close</button>
                </li>
            </ul>
        </div>
    </div>

<?php
$script = <<< JS
$('.dropbox-button').unbind('click').bind('click', function(){
   $(this).closest('.dropbox-widget').children('.dropbox-content').slideToggle();
});
$('.dropbox-submit, .dropbox-default').unbind('click').bind('click', function(){
   $(this).closest('.dropbox-widget').children('.dropbox-content').slideUp();
});

JS;

$this->registerJs($script, \yii\web\View::POS_END);
