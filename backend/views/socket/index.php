<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 24-Jan-19
 * Time: 2:14 PM
 */

?>
abc
<?php

$script = <<< JS
    // if (!!window.EventSource) {
        var source = new EventSource('/socket/stream');
        source.addEventListener('message', function(e) {
            alert(e.id);
        });
    // }
JS;
$this->registerJs($script, \yii\web\View::POS_END);
