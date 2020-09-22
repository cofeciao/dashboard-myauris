<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 23-Jan-19
 * Time: 3:41 PM
 */

use yii\helpers\Html;

?>
<div class="modal-header bg-blue-grey bg-lighten-2 white">
    <h4 class="modal-title">
        Đã xảy ra lỗi.
    </h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <?php
    if ($error) {
        echo $error;
    }
    ?>
</div>