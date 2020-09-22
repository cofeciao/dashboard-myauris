<?php
/**
 * Created by PhpStorm.
 * User: luken
 * Date: 6/18/2020
 * Time: 11:15
 */
?>
<div class="card overflow-hide">
    <div class="card-header p-0">
        <div class="nav nav-tabs nav-light nav-justified" id="dash-tab" role="tablist">
            <a class="d-flex align-items-center justify-content-center nav-item nav-link active" id="dash-tab-1"
               data-toggle="tab" href="#nav-dash-1" role="tab" aria-selected="true">
                <span class="d-block"><?= $title ?></span>
            </a>
            <a class="d-none align-items-center justify-content-center nav-item nav-link" id="dash-tab-2"
               data-toggle="tab" href="#nav-dash-2" role="tab" aria-selected="false">
                <span class="d-block">Tỉ lệ khách chốt/khách tư vấn tháng</span>
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-dash-1" role="tabpanel" aria-labelledby="dash-tab-1">
                <div class="table-wrap">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th class="text-center" style="width:60px">STT</th>
                                <th>Tên nhân viên</th>
                                <th class="text-center">Khách tư vấn</th>
                                <th class="text-center">Khách chốt</th>
                                <th class="text-center">%</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $i = 1;
                            foreach ($khachTuVanDirectSaleNgayResultFinal as $key => $item) {
                                ?>
                                <tr>
                                    <td class="text-center"><?= $i ?></td>
                                    <td><?= $item['name']; ?></td>
                                    <td class="text-center"><?= $item['khachDirectSaleTuVan'] ?></td>
                                    <td class="text-center"><?= $item['khachDirectSaleChot'] ?></td>
                                    <td class="text-center">
                                        <span class="d-block"><?= $item['phantram'] ?>%</span>
                                        <span class="d-block">
                                    <div class="progress progress-xs mb-0">
                                        <div class="progress-bar bg-danger bg-darken-4" role="progressbar"
                                             style="width: <?= $item['phantram'] ?>%" aria-valuenow="25"
                                             aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </span>
                                    </td>
                                </tr>
                                <?php
                                $i++;
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade d-none" id="nav-dash-2" role="tabpanel" aria-labelledby="dash-tab-2">
                <div class="table-wrap">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th class="text-center" style="width:60px">STT</th>
                                <th>Tên nhân viên</th>
                                <th class="text-center">Khách tư vấn</th>
                                <th class="text-center">Khách chốt</th>
                                <th class="text-center">%</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $i = 1;
                            foreach ($khachTuVanDirectSaleThangResultFinal as $key => $item) {
                                ?>
                                <tr>
                                    <td class="text-center"><?= $i ?></td>
                                    <td><?= $item['name']; ?></td>
                                    <td class="text-center"><?= $item['khachDirectSaleTuVan'] ?></td>
                                    <td class="text-center"><?= $item['khachDirectSaleChot'] ?></td>
                                    <td class="text-center">
                                        <span class="d-block"><?= $item['phantram'] ?>%</span>
                                        <span class="d-block">
                                                <div class="progress progress-xs mb-0">
                                                    <div class="progress-bar bg-blue bg-accent-3" role="progressbar"
                                                         style="width: <?= $item['phantram'] ?>%" aria-valuenow="25"
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
            </div>
        </div>
    </div>
</div>