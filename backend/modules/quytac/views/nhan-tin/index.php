<?php

$this->title = Yii::t('backend', 'Quy tắc gửi tin');
$this->params['breadcrumbs'][] = ['label' => 'Quy tắc', 'url' => ['/quytac']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section id="dom">
    <div class="row">
        <div class="col-12">
            <?php
            if (Yii::$app->session->hasFlash('alert')) {
                ?>
                <div class="alert <?= Yii::$app->session->getFlash('alert')['class']; ?> alert-dismissible"
                     role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <?= Yii::$app->session->getFlash('alert')['body']; ?>
                </div>
                <?php
            }
            ?>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Quy tắc gửi tin nhắn</h4>
                    <p>Quy tắc gửi tin dành riêng cho khách hàng đã đặt hẹn, có 3 chú ý cơ bản bạn cần nắm rõ sau:</p>
                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a class="block-page"
                                   onclick='window.location="<?= \Yii::$app->getRequest()->getUrl(); ?>"'><i
                                            class="ft-rotate-cw"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            <li><a data-action="close"><i class="ft-x"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body card-dashboard">
                        <p class="text-primary">1. Đối với khách hàng mới tạo và đặt hẹn trong ngày</p>
                        <p>Khi tạo mới khách hàng, mình sẽ gửi tin cho khách hàng ở dạng tin nhắn khác, nằm dưới cùng trong mục chọn gửi tin nhắn, nội dung có thể giống với lần gửi cách một ngày khi tạo tin nhắn, nhằm mục đích thông báo cho khách hàng ngày giờ lịch hẹn.</p>
                        <p class="text-primary">2. Đối với khách hàng có lịch hẹn cách 1,3,7 ngày</p>
                        <p>Chọn tin nhắn đã có sẵn trong danh mục chọn tin nhắn, kiểm tra lại tin nhắn xem có sai sót gì không, nếu có báo với kỹ thuật ngay và luôn. Nếu không, gửi tin nhắn bình thường.</p>
                        <p class="text-primary">3. Đối với khách hàng có lịch hẹn thay đổi đột xuất</p>
                        <p>Tạo mới tin nhắn ở dạng tin nhắn khác và viết nội dung cần gửi tin, gửi cho khách hàng</p>
                        <p class="text-primary">4. Đối với khách hàng là người nước ngoài.</p>
                        <p>Hiện tại, chưa cho phép nhắn tin với khách hàng này, nhân viên có thể bỏ qua phần gửi sms nếu có chữ màu đỏ</p>
                        <span class="text-danger">Chú ý:</span>Không nên hoặc tránh gửi tin nhắn cho khách hàng liên tục trong ngày hoặc 2 ngày liên tục, điều đó sẽ làm phiền khách hàng, không cần thiết.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
