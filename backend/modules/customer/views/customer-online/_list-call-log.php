<?php

use yii\helpers\Html;

?>
    <div class="call-log-view">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>STT</th>
                    <th>Hướng</th>
                    <th>Gọi từ số</th>
                    <th>Gọi đến số</th>
                    <th>Thời gian</th>
                    <th>Ghi âm</th>
                    <th>Bắt đầu</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (count($callLog) > 0) {
                    foreach ($callLog as $i => $log) {
                        ?>
                        <tr>
                            <th><?= $i + 1 ?></th>
                            <td>
                                <?php
                                if ($log->direction == 1) {
                                    echo 'Gọi vào';
                                } elseif ($log->direction == 3) {
                                    echo 'Gọi ra';
                                } else {
                                    echo '-';
                                } ?>
                            </td>
                            <td><?= $log->from_number ?></td>
                            <td><?= $log->to_number ?></td>
                            <td>
                                <?php
                                if ($log->duration == 0) {
                                    echo '-';
                                } else {
                                    echo \common\helpers\MyHelper::SecondsToTime($log->duration);
                                } ?>
                            </td>
                            <td>
                                <?php
                                if ($log->duration != 0) {
                                    ?>
                                    <button type="button" class="btn btn-sm btn-primary btn-popover"
                                            data-toggle="popover"
                                            data-content="<iframe src='<?= $log->recording_url ?>'></iframe>"
                                            data-html="true" data-placement="bottom">
                                        <i class="fa fa-play-circle"></i>
                                    </button>
                                <?php
                                } else {
                                    echo '-';
                                } ?>
                            </td>
                            <td><?= date('d-m-Y H:i:s', $log->time_started); ?></td>
                        </tr>
                    <?php
                    }
                } else { ?>
                    <tr>
                        <td colspan="8">
                            <div class="alert alert-warning">Không có danh sách cuộc gọi</div>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
<?php
$script = <<< JS
$('.btn-popover').popover({
    offset: 50,
});
JS;
$this->registerJs($script);
