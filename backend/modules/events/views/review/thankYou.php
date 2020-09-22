<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 22-Feb-19
 * Time: 9:37 AM
 */

$this->title = 'Cảm ơn khách hàng';
?>
    <div class="app-content content hide">
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- coming soon bg video -->
                <section class="flexbox-container">
                    <div class="col-12">
                        <div class="card card-transparent box-shadow-0 border-0 coming-soon-content">
                            <div class="card-content">
                                <div class="text-center custom-thank">
                                    <h3 class="card-text pt-1 text-uppercase">Cảm ơn bạn đã đánh giá dịch vụ của
                                        Myauris.</h3>
                                    <div class="col-12">
                                        <p class="card-text lead">Chúng tôi đánh giá cao về điều đó, chúc bạn một ngày tốt lành.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!--/ coming soon bg video -->
            </div>
        </div>
    </div>

<?php
$script = <<< JS
$(document).ready(function() {
    setTimeout(function() {
        window.location.href = '/play-video.html';
    }, 5000);
});
JS;

$this->registerJs($script, \yii\web\View::POS_END);
