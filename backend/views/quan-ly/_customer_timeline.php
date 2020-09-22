<?php
/* @var $timelines array */

use yii\helpers\Url;

?>
<div class="timeline-container">
    <?php
    $year = '';
    $align = 'left';
    foreach ($timelines as $key => $timeline) {
        $timelineYear = date('Y', $key);
        if ($year == '' || $timelineYear != $year) {
            $year = $timelineYear; ?>
            <div class="timeline-tag-title timeline-year" data-year="<?= $year ?>">
                <a href="javascript: void(0)" class="btn btn-primary">
                    <i class="fa fa-calendar-o"></i> <?= $year ?>
                </a>
            </div>
            <?php
        }
        if (!isset($timeline['center']) || $timeline['center'] != true) {
            $class = $align;
            $align = $align == 'left' ? 'right' : 'left';
        } else {
            $class = 'center';
        }
        if (!isset($timeline['icon-class'])) {
            $timeline['icon-class'] = 'default';
        } ?>
        <div class="timeline-tag <?= $class ?> timeline-<?= $year ?>">
            <div class="timeline-icon">
                <div class="btn-<?= $timeline['icon-class'] ?>"><?= $timeline['icon'] ?></div>
            </div>
            <div class="timeline-tag-content">
                <div class="timeline-title"><?= $timeline['title'] ?><?= $class != 'center' ? ' <span class="timeline-time">(' . date('d-m-Y', $key) . ')</span>' : '' ?></div>
                <?= $class == 'center' ? '<span class="timeline-time">(' . date('d-m-Y', $key) . ')</span>' : '' ?>
                <div class="timeline-content"><?= $timeline['content'] ?></div>
            </div>
        </div>
        <?php
    } ?>
</div>
<?php
$this->registerJsFile(Url::to('@web/js') . '/scripts/popover/popover.min.js', ['depends' => \yii\web\JqueryAsset::class]);
