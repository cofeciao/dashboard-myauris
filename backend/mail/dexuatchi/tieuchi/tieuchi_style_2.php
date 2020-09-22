<?php
/**
 */
$format = '<div class="">Tiêu chí:
        %1$s
</div>';
$items = '';
if (!empty($data)) {
    foreach ($data as $value) {
        $thoigian_batdau = !empty($value['thoi_gian_bat_dau']) ? Yii::$app->formatter->asDatetime($value['thoi_gian_bat_dau'], 'php:d:m:Y') : '';
        $thoigian_ketthuc = !empty($value['thoi_gian_ket_thuc']) ? Yii::$app->formatter->asDatetime($value['thoi_gian_ket_thuc'], 'php:d:m:Y') : '';
        $thoigian = $thoigian_batdau . '----' . $thoigian_ketthuc;
        /*switch ($value['status']) {
            case 1:
                $status = 'Hoàn Thành';
                break;
            default:
                $status = 'Chưa Hoàn Thành';
        }*/
        $items .= sprintf('
                    <p class="nd_hoanthanh">+ %1$s</p>
                    <p class="nd_hoanthanh">Deadline: %2$s</p>
               ', $value['tieu_chi'], $thoigian);
    }
}
printf($format, $items);

