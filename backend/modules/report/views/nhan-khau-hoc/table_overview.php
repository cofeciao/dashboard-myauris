<?php
/*
$tr = '';

foreach ($data['filter']['data'] as $value) {
    $tr_temp = '<tr>
<td>%1$s</td>
<td>%2$s</td>
<td>%3$s</td>
<td>%4$s</td>
<td>%5$s</td>
<td>%6$s</td>
<td>%7$s</td>
<td>%8$s</td>
</tr>';
    $res = $this->context->getDataTableOverview($value['id']);
    $tr .= sprintf($tr_temp, $value['name'], $res['dathen'], $res['khongdat'], $res['den'], $res['khongden'], $res['lam'], $res['khonglam'], $res['doanhthu']);
}*/

?>


<form action="">
    <h2>Chi Tiết Thống Kê <?php echo date('d/m/Y', $this->context->datetimeStamp['startDate']) ?>
        đến <?php echo date('d/m/Y', $this->context->datetimeStamp['endDate']) ?> </h2>
    <div class="main_dimension">

        <?php
        $arr = ['thanhpho' => 'Thành Phố', 'dotuoi' => 'Độ Tuổi', 'gioitinh' => 'Giới Tính', 'dichvu' => 'Dịch Vụ', 'sanpham' => 'Sản Phẩm', 'coso' => 'Cơ Sở', 'fanpage' => 'Fanpage'];
        foreach ($arr as $key => $value) {
            $checked = $data['filter_dimension'] == $key ? 'checked' : '';
            if ($key == 'thanhpho' && empty($data['filter_dimension'])) {
                $checked = 'checked';
            }
            echo '     <span> <input class="form-check-input" id="' . $key . '" type="radio" name="filter_dimension" value="' . $key . '" ' . $checked . '>  <label  class="form-check-label" for="' . $key . '">' . $value . '</label></span>';
        } ?>
        <!--<label>Độ Tuổi<input type="radio" name="filter_dimension" value="dotuoi"></label>
        <label>Giới Tính<input type="radio" name="filter_dimension" value="gioitinh"></label>
        <label>Thành Phố<input type="radio" name="filter_dimension" value="thanhpho"></label>
        <label>Sản Phẩm<input type="radio" name="filter_dimension" value="sanpham"></label>
        <label>Cơ Sở<input type="radio" name="filter_dimension" value="coso"></label>
        <label>Fanpage<input type="radio" name="filter_dimension" value="fanpage"></label>
-->
    </div>

    <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
        <thead>
        <tr>

            <th class="th-sm">Name
            </th>
            <th class="th-sm">
                Đặt Hẹn
            </th>
            <th class="th-sm">Không Đặt</th>
            <th class="th-sm">
                Đến
            </th>
            <th class="th-sm">Không Đến</th>
            <th class="th-sm">
                Làm
            </th>
            <th class="th-sm">Không Làm</th>
            <th class="th-sm">
                Doanh Thu
            </th>

        </tr>
        </thead>
        <tbody>
        <?php //echo $tr?>

        </tbody>
        <tfoot>
        <tr>
            <th>Name
            </th>
            <th>Đặt Hẹn
            </th>
            <th>Không Đặt</th>
            <th>Đến
            </th>
            <th>Không Đến</th>
            <th>Làm
            </th>
            <th>Không Làm</th>
            <th>Doanh Thu
            </th>
        </tr>
        </tfoot>
    </table>

</form>












