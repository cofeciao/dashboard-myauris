<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 23-Jan-19
 * Time: 3:41 PM
 */

use yii\helpers\Html;

?>
<section id="form-control-repeater">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title" id="tel-repeater">Đã xảy ra lỗi.</h4>
        </div>
        <div class="card-content collapse show">
            <div class="card-body" style="color: red">
                <form>
                    <?php
                    if ($error) {
                        echo $error;
                    }
                    ?>
                    <div class="form-actions center">
                        <?= Html::button('<i class="ft-x"></i> Close', ['class' =>
                            'btn btn-default mr-1 close-dieutri close-order', 'data-pjax' => 0]) ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
