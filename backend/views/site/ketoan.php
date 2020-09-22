<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 04-Mar-19
 * Time: 3:03 PM
 */

$this->title = 'Kế toán';
$css = <<< CSS
.app-content.content, .content-wrapper, .content-body, #site-content { height: 100%; }
html body #site-content{ padding: 0; }
.app-content:before{ display: none!important; }
CSS;

$this->registerCss($css);
?>

<!--stats-->
<section id="site-content">
    <iframe src="<?php echo \yii\helpers\Url::toRoute('/screenonline/phong-kham') ?>" frameborder="0" style="width: 100%; height: 100%"></iframe>
</section>
<!--/stats-->
