<table class="table table-fixed">
    <thead>
    <tr>
        <th>Tỉnh / Thành phố</th>
        <th>Khách đặt hẹn</th>
        <th>Đến / Không đến</th>
        <th>Tỷ lệ đến</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if ($data_report == null) {
        ?>
        <tr>
            <td class="text-center font-small-2" colspan="4">
                <p>Không có dữ liệu</p>
            </td>
        </tr>
        <?php
    } else {
        foreach ($data_report as $item) {
            ?>
            <tr>
                <td><?= $item['province']; ?></td>
                <td><?= $item['total']; ?></td>
                <td><?= $item['come'] . ' / ' . $item['not_come'];
            ; ?></td>
                <td class="text-center font-small-2"><?= $item['per_come']; ?> %
                    <div class="progress progress-md mt-0 mb-0">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: <?= $item['per_come']; ?>%"
                             aria-valuenow="25"
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </td>
            </tr>
        <?php
        }
    }
    ?>
    </tbody>
</table>
