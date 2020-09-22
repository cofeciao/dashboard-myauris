<?php
/**
 * Created by PhpStorm.
 * User: luken
 * Date: 6/18/2020
 * Time: 14:48
 */
?>

<div class="table-wrap">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
            <tr>
                <th>STT</th>
                <th>Fanpage</th>
                <th>Lịch hẹn</th>
                <th>Tỉ lệ</th>
            </tr>
            </thead>
            <tbody>
            <?php $i = 1;
            $percent = 0;
            foreach ($lichHenFanpage as $key => $value) {
                $percent = $tongLichHenFanpage == 0 ? 0 : $value['value'] / $tongLichHenFanpage * 100;
                if ($percent <= 10) $bg_color = 'bg-danger';
                elseif ($percent > 10 && $percent <= 50) $bg_color = 'bg-warning';
                elseif ($percent > 50 && $percent <= 70) $bg_color = 'bg-info';
                elseif ($percent > 70) $bg_color = 'bg-success';
                ?>
                <tr>
                    <td><?= $i ?></td>
                    <td><?= $value['name'] ?></td>
                    <td><?= $value['value'] ?></td>
                    <td>
                        <span class="d-block"><?= number_format($percent, 2) . '%' ?></span>
                        <span class="d-block">
                            <div class="progress progress-xs mb-0">
                                <div class="progress-ba <?= $bg_color ?>"
                                     role="progressbar"
                                     style="width: <?= number_format($percent, 2) . '%' ?>"
                                     aria-valuenow="25"
                                     aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </span>
                    </td>
                </tr>
                <?php
                $i++;
            } ?>
            </tbody>
        </table>
    </div>
</div>
