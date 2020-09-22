<?php

use yii\widgets\Breadcrumbs;

/**
 * @var $this yii\web\View
 */
?>
<?php $this->beginContent('@backend/views/layouts/common.php'); ?>
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header">
                <div class="content-header-left">
                    <div class="breadcrumbs-top">
                        <div class="breadcrumb-wrapper">
                            <?php
                            echo Breadcrumbs::widget([
                                'itemTemplate' => '<li class="breadcrumb-item block-page">{link}</li>',
                                'activeItemTemplate' => '<li class="breadcrumb-item active">{link}</li>',
                                'tag' => 'ol',
                                'homeLink' => [
                                    'label' => Yii::t('yii', 'Home'),
                                    'url' => Yii::$app->homeUrl,
                                ],
                                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                                'options' => [
                                    'class' => 'breadcrumb'
                                ]
                            ])
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <?php echo $content ?>
            </div>
        </div>
    </div>
<?php $this->endContent(); ?>