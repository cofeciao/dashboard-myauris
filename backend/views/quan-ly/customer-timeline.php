<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tran
 * Date: 20-04-2019
 * Time: 09:23 AM
 */

/* @var $timelines array */

use backend\modules\customer\models\Dep365CustomerOnline;
use yii\helpers\Html;
use yii\helpers\Url;

$customer_name = $customer->full_name == null ? $customer->name : $customer->full_name;
$customer_avatar = $customer->avatar == null ? '/local/default/avatar-default.png' : '/uploads/avatar/70x70/' . $customer->avatar;

?>
    <section id="dom">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content collapse show customer-index ">
                        <div class="card-content collapse show search-clinic">
                            <div class="customer-container">
                                <div class="customer-header">
                                    <div class="ch-col">
                                        <div class="c-avatar">
                                            <?= Html::img($customer_avatar, []) ?>
                                        </div>
                                    </div>
                                    <div class="ch-col">
                                        <div class="c-name">Khách hàng: <span><?= $customer_name ?></span></div>
                                        <div class="c-email">Id: <span><?= $customer->id ?></span></div>
                                    </div>
                                    <div class="ch-col">
                                        <div class="c-phone">Điện thoại: <span><?= $customer->phone ?: '-' ?></span>
                                        </div>
                                        <div class="c-code">Mã khách hàng:
                                            <span><?= $customer->customer_code ?: '-' ?></span></div>
                                        <div class="c-code">Direct sale:
                                            <span><?= $customer->directsale ? Dep365CustomerOnline::getDirectSale($customer->directsale) : '-' ?></span>
                                        </div>
                                    </div>
                                    <div class="ch-col">
                                        <div class="c-timeline">
                                            <a class="btn btn-success"
                                               href="<?= Url::toRoute(['customer-view', 'id' => $customer->id]) ?>"><span>Thông tin</span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body card-dashboard">
                            <div class="customer-content">
                                <?= $this->render('_customer_timeline', [
                                    'timelines' => $timelines
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
$this->registerCssFile('/modules/customer/timeline/timeline.css', ['depends' => [\yii\bootstrap\BootstrapAsset::class]]);
$this->registerJsFile(Url::to('@web/modules') . '/customer/timeline/timeline.js', ['depends' => \yii\web\JqueryAsset::class]);
