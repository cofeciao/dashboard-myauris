<?php
/**
 * Created by PhpStorm.
 * User: luken
 * Date: 8/13/2020
 * Time: 16:52
 */
$css = <<< CSS
.note{
color: #f00;
text-align: right;
font-style: italic;
}
CSS;
$this->registerCss($css);
?>
<?php echo $note != '' ? '<p class="note">' . $note . '</p>' : '' ?>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th colspan="2"></th>
            <th class="text-center">Doanh thu</th>
            <th class="text-center font-weight-bold"><?= number_format($doanh_thu, 0, '', '.') ?></th>
            <th class="text-center"></th>
            <th class="text-right"></th>
            <th class="text-right"></th>
            <th class="text-center"></th>
        </tr>
        <tr>
            <th colspan="3"></th>
            <th class="text-right font-weight-bold">Đã chi trong tháng</th>
            <th class="text-center font-weight-bold">%</th>
            <th class="text-right font-weight-bold">Chờ duyệt</th>
            <th class="text-right font-weight-bold">Sau duyệt</th>
            <th class="text-center font-weight-bold">% Sau duyệt</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($data as $dm_id => $dm_chi) {
            ?>
            <tr>
                <td class="text-center bg-yellow font-weight-bold"
                    colspan="3"><?= $dm_chi['name'] ?></td>
                <td colspan="5"></td>
            </tr>
            <?php
            foreach ($dm_chi['nhom_chi'] as $nhomchi_id => $nhom_chi) {
                ?>
                <tr>
                    <td class="text-left bg-blue bg-lighten-3"
                        colspan="3"><?= $nhom_chi['name'] ?></td>
                    <td class="text-right font-weight-bold">
                        <?= number_format($nhom_chi['da_chi'], 0, '', '.') ?>
                    </td>
                    <td class="text-center font-weight-bold"><?= $doanh_thu > 0 ? round($nhom_chi['da_chi'] / $doanh_thu * 100, 2) . '%' : 0 ?></td>
                    <td class="text-right font-weight-bold">
                        <?= number_format($nhom_chi['cho_duyet'], 0, '', '.') ?>
                    </td>
                    <td class="text-right font-weight-bold">
                        <?= number_format($nhom_chi['sau_duyet'], 0, '', '.') ?>
                    </td>
                    <td class="text-center font-weight-bold"><?= $doanh_thu > 0 ? round($nhom_chi['sau_duyet'] / $doanh_thu * 100, 2) . '%' : 0 ?></td>
                </tr>
                <?php
                foreach ($nhom_chi['khoan_chi'] as $khoanchi_id => $khoan_chi) {
                    ?>
                    <tr>
                        <td><?= $nhom_chi['code'] ?></td>
                        <td><?= $khoan_chi['code'] ?></td>
                        <td><?= $khoan_chi['name'] ?></td>
                        <td class="text-right">
                            <?= number_format($khoan_chi['da_chi'], 0, '', '.') ?>
                        </td>
                        <td class="text-center"><?= $doanh_thu > 0 ? round($khoan_chi['da_chi'] / $doanh_thu * 100, 2) . '%' : 0 ?></td>
                        <td class="text-right">
                            <?= number_format($khoan_chi['cho_duyet'], 0, '', '.') ?>
                        </td>
                        <td class="text-right">
                            <?= number_format($khoan_chi['sau_duyet'], 0, '', '.') ?>
                        </td>
                        <td class="text-center"><?= $doanh_thu > 0 ? round($khoan_chi['sau_duyet'] / $doanh_thu * 100, 2) . '%' : 0 ?></td>
                    </tr>
                    <?php
                }
                ?>
                <?php
            }
            ?>
            <?php
        }
        ?>
        </tbody>
    </table>
</div>
