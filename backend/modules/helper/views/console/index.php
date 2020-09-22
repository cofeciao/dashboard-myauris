<?php
/**
 * Created by PhpStorm.
 * User: abc
 * Date: 3/26/2020
 * Time: 5:28 PM
 */


$css = <<<CSS
.app-content:before{
content:unset !important;
}
textarea[name="textarea"]{
width:100%;
height: 100px;
}
CSS;

$this->registerCss($css);

echo 'exec';
echo \yii\helpers\Html::textInput('exec', '');
echo \yii\helpers\Html::button('submit', ['class' => 'btn btn-success btn-submit']);
echo \yii\helpers\Html::textarea('textarea', '', ['row' => 10, 'cols' => 10]);
?>
    <table>
        <tbody id="tbody"></tbody>
    </table>
<?php

$url_to = \yii\helpers\Url::to('/helper/console/getdata');
$js = <<<JS
jQuery(function(){
    $('.btn-submit').on('click',function(e){
        e.preventDefault();
        var data=$('input[name="exec"]').val();
        console.log(data);
        if(data !=''){
        $.ajax({
        url:'$url_to',
        method:'POST',
        data:{
            data:data
        }
        }).done(function(res){
            console.log(res);
            var tbody = document.getElementById('tbody');
            var tr=""
            /* data*/
            for(var i=0; i< Object.keys(res.data).length; i++){
                    var trhead="<tr>";
                    var trHeadRaw=Object.keys(res.data[i]);
                    for(var k=0;k<trHeadRaw.length;k++){
                    
                        trhead+="<td>"+trHeadRaw[k]+"</td>";
                    }
                    trhead+="</tr>";
                   let tdValue = Object.values(res.data[i]);
                   tr+="<tr>";
                   var td="";
                   for(var j=0; j<tdValue.length;j++){
                       td+="<td>"
                           td+=tdValue[j];
                       td+="</td>"
                   }
                   tr+=td;
                   tr+="</tr>";
            }
            $('#tbody').html(trhead+tr);
        })    
        }
    })  
});


JS;

$this->registerJs($js);

