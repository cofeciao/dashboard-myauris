
<div id="new-customer-chart" style="height: 300px"> </div>
<?php
$data = json_encode($data_report);
$script = <<< JS
Morris.Area({
  element: 'new-customer-chart',
  data: $data,
  xkey: 'date',
  ykeys: ['total'],
  labels: ['Khách mới'],
  xLabels: 'day',
});
JS;
$this->registerJs($script);
?>
