<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 22-Feb-19
 * Time: 10:59 AM
 */

$this->title = 'My Auris';
$script = <<< JS
    $(document).ready(function() {
        if($('.comingsoonVideo').length > 0){
            $('.comingsoonVideo').tubular({videoId: 'jM3LhfjAmjE'});
        }
        if(window.EventSource){
           source = new EventSource("/events/review/listener");
           source.addEventListener("message", function(event){
               console.log(JSON.parse(event.data));
               if(JSON.parse(event.data).id != 'null') {
                   source.close();
                   if(JSON.parse(event.data).type == 1){
                       setTimeout(function() {
                            window.location.href = "/danh-gia.html";
                        }, 2000);
                   }
                   
                   if(JSON.parse(event.data).type == 2){
                       setTimeout(function() {
                            window.location.href = "/khach-danh-gia.html";
                        }, 2000);
                   }
               };
           });
       } else {
           alert("Trình duyệt không thích hợp, vui lòng chọn trình duyệt khác");
       }
    });
JS;

$this->registerJs($script, \yii\web\View::POS_END);
