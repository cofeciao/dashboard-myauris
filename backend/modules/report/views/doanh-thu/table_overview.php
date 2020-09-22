<?php
/**
 * Date: 11/9/19
 * Time: 1:59 PM
 */

/*<pre>select p.name, sum(ph.thanh_tien-ph.chiet_khau) as thanh_tien, sum(tt.tien_thanh_toan) as tien_thuc_thu from dep365_customer_online d, province p, phong_kham_don_hang ph,phong_kham_don_hang_w_thanh_toan tt where tt.phong_kham_don_hang_id = ph.id and d.province=p.id and ph.customer_id=d.id and ph.thanh_tien > 0 and ph.ngay_tao between 1570467600 and 1570554000 group by p.name</pre><pre>Array
(
    [0] => Array
        (
            [name] => Bà Rịa - Vũng Tàu
            [thanh_tien] => 90000000
            [tien_thuc_thu] => 45000000
        )

    [1] => Array
        (
            [name] => Bình Dương
            [thanh_tien] => 32000000
            [tien_thuc_thu] => 15000000
        )
)
</pre>*/
//$dateRange[0]->getTimestamp();
//$dateRange[1]->getTimestamp();
$fomart_table = '<div id="overview-dimensionSummary-miniTable">
        <table class="table">
            <thead>
            <tr>
                <th>Tỉnh / Thành phố</th>
                <th>Tổng Tiền HĐ</th>
                <th>Tổng Thu HĐ</th>
                <th>Tổng Nợ HĐ</th>
                <th>Tiền Nợ HĐ Cũ</th>
                <th>Tiền Thực Thu</th>
            </tr>
            </thead>
            <tbody>
                %1$s
            </tbody>
        </table>
</div>';

$fomart_td = '<tr>
            <td>%1$s</td>
            <td>%2$s</td>
            <td>%3$s</td>
            <td>%4$s</td>
            <td>%5$s</td>
            <td>%6$s</td>
        </tr>';

$td_data = '';

//Tam's code
$tongThuHD = $tongThucThuHD = $tongNoHD = $tongNoCu = $tongThucThu = 0;

foreach ($doanhthuOutput as $item) {
    $tong_no_hd_cu = isset($item['tong_no_hd_cu']) ? $item['tong_no_hd_cu'] : 0;
    $tong_thuc_thu = isset($item['tong_thuc_thu']) ? $item['tong_thuc_thu'] : 0;
    $tongThuHD += $item['tong_thu_hd'];
    $tongThucThuHD += $item['thuc_thu_hd'];
    $tongNoHD += $item['tong_no_hd'];
    $tongNoCu += $tong_no_hd_cu;
    $tongThucThu += $tong_thuc_thu;

    $td_data .= sprintf(
        $fomart_td,
        $item['name'],
        number_format($item['tong_thu_hd'], 0, '', ','),
        number_format($item['thuc_thu_hd'], 0, '', ','),
        number_format($item['tong_no_hd'], 0, '', ','),
        number_format($tong_no_hd_cu, 0, '', ','),
        number_format($tong_thuc_thu, 0, '', ',')
    );
}

$str_coso = '';
if (isset($co_so) && !empty($co_so)) {
    $str_coso = ' cơ sở ' . $co_so . ' ';
}
echo '<div style="font-size:18px;font-weight:500;padding-bottom:20px;padding-top:20px">Tổng quan doanh thu ' . $str_coso . date('d/m/Y', $dateRange[0]->getTimestamp()) . ' - ' . date('d/m/Y', $dateRange[1]->getTimestamp()) . '</div>';
?>
<div id="overview-sparkline" style="margin:0 0 30px;text-align:left">
    <div class="sparkline-metric-analytics visits-group">
        <div class="sparkline-metric-analytics.visits">Tổng tiền HĐ</div>
        <div style="display:inline-block;margin:5px 0;text-align:left">
            <div><?= $tongThuHD <= 0 ? 0 : number_format($tongThuHD, 0, '', ',') ?></div>
            <div class="action-graph target-analytics.visits"></div>
        </div>
    </div>
    <div class="sparkline-metric-analytics visits-group">
        <div class="sparkline-metric-analytics.visits">Tổng thu HĐ</div>
        <div style="display:inline-block;margin:5px 0;text-align:left">
            <div><?= $tongThucThuHD <= 0 ? 0 : number_format($tongThucThuHD, 0, '', ',') ?></div>
            <div class="action-graph target-analytics.visits"></div>
        </div>
    </div>
    <div class="sparkline-metric-analytics visits-group">
        <div class="sparkline-metric-analytics.visits">Tổng nợ HĐ</div>
        <div style="display:inline-block;margin:5px 0;text-align:left">
            <div><?= $tongNoHD <= 0 ? 0 : number_format($tongNoHD, 0, '', ',') ?></div>
            <div class="action-graph target-analytics.visits"></div>
        </div>
    </div>
    <div class="sparkline-metric-analytics visits-group">
        <div class="sparkline-metric-analytics.visits">Tổng nợ HĐ cũ</div>
        <div style="display:inline-block;margin:5px 0;text-align:left">
            <div><?= $tongNoCu <= 0 ? 0 : number_format($tongNoCu, 0, '', ',') ?></div>
            <div class="action-graph target-analytics.visits"></div>
        </div>
    </div>
    <div class="sparkline-metric-analytics visits-group">
        <div class="sparkline-metric-analytics.visits">Tổng thực thu</div>
        <div style="display:inline-block;margin:5px 0;text-align:left">
            <div><?= $tongThucThu <= 0 ? 0 : number_format($tongThucThu, 0, '', ',') ?></div>
            <div class="action-graph target-analytics.visits"></div>
        </div>
    </div>
</div>


<?php
echo sprintf($fomart_table, $td_data);

?>


