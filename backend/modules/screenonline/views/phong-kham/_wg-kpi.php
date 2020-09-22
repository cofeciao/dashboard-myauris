<?php
/**
 * Created by PhpStorm.
 * User: luken
 * Date: 6/18/2020
 * Time: 11:07
 */
?>
<div class="card">
    <div class="card-body p-0">
        <div class="table-wrap">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped mb-0">
                    <thead>
                    <tr>
                        <th class="text-center bg-blue-grey red-lighten-2 text-white"
                            style="width:15%">KPI
                        </th>
                        <th class="text-center bg-blue-grey red-lighten-2 text-white"
                            style="width:17%">Tương tác
                        </th>
                        <th class="text-center bg-blue-grey red-lighten-2 text-white"
                            style="width:17%">Lịch mới
                        </th>
                        <th class="text-center bg-blue-grey red-lighten-2 text-white"
                            style="width:17%">Lịch hẹn
                        </th>
                        <th class="text-center bg-blue-grey red-lighten-2 text-white"
                            style="width:17%">Khách đến
                        </th>
                        <th class="text-center bg-blue-grey red-lighten-2 text-white"
                            style="width:17%">Khách làm
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $symbol = $progress = '';
                    foreach ($kpiData as $serviceId => $serviceData) {
                        ?>
                        <tr>
                            <td class="bg-blue-grey red-lighten-2 text-white font-weight-600"><?= $serviceData['name'] ?></td>
                            <?php $text_color = $icon = '';
                            foreach ($serviceData as $key => $value) {
                                if (is_array($value)) {
                                    if ($value['phan_tram'] <= 10) $text_color = 'text-danger';
                                    elseif ($value['phan_tram'] > 10 && $value['phan_tram'] <= 50) $text_color = 'text-warning';
                                    elseif ($value['phan_tram'] > 50 && $value['phan_tram'] <= 70) $text_color = 'blue';
                                    elseif ($value['phan_tram'] > 70) $text_color = 'text-primary';
                                    ?>
                                    <td>
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <sup><?= $value['thuc_te'] ?></sup>
                                                <sub><?= $value['kpi'] ?></sub>
                                            </div>
                                            <div>
                                                <span class="font-13 font-weight-600 <?= $text_color ?>">
                                                    <?= $value['phan_tram'] . '%' ?>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <?php
                                }
                            } ?>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
