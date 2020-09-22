<?php
/**
 * Created by PhpStorm.
 * User: Kem Bi
 * Date: 17-Oct-18
 * Time: 10:52 AM
 */

use yii\helpers\Url;
use common\models\User;
use backend\models\SiteModel;

//Tính số sms chưa gửi
$total = 0;
if (Yii::$app->user->can(User::USER_NHANVIEN_ONLINE)) {
    $siteModel = new SiteModel();
    $total = $siteModel->getMissSmsNumber(date('d-m-Y'));
}

\yii\widgets\Pjax::begin([
    'id' => 'pjax-left-widget',
    'timeout' => false,
    'enablePushState' => false,
    'clientOptions' => ['method' => 'GET']
])
?>
<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

            <li class="nav-item block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'site') {
                echo ' active';
            } ?>">
                <a href="<?= Url::toRoute(['/site']); ?>">
                    <i class="ft-home"></i>
                    <span class="menu-title" data-i18n=""><?= Yii::t('backend', 'Trang chủ'); ?></span>
                </a>
            </li>
            <?php
            if (Yii::$app->user->can('toothstatusDich-vu') ||
                Yii::$app->user->can('toothstatusLua-chon') ||
                Yii::$app->user->can('toothstatusTinh-trang-rang') ||
                Yii::$app->user->can('toothstatusTooth-status') ||
                Yii::$app->user->can('toothstatus') ||
                Yii::$app->user->can(User::USER_DEVELOP)) {
                ?>
                <li class="nav-item">
                    <a href="#">
                        <i class="fa fa-search-plus"></i>
                        <span class="menu-title" data-i18n="">Sales Răng</span>
                    </a>
                    <ul class="menu-content">
                        <?php
                        if (Yii::$app->user->can('toothstatusTooth-status') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'toothstatus') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/toothstatus/tooth-status']); ?>">
                                    Màn hình tư vấn
                                </a>
                            </li>
                            <?php

                        }
                        ?>
                        <?php
                        if (Yii::$app->user->can('toothstatusTinh-trang-rang') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'tinh-trang-rang') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/toothstatus/tinh-trang-rang']); ?>">
                                    Tình trạng răng
                                </a>
                            </li>
                            <?php

                        }
                        ?>
                        <?php
                        if (Yii::$app->user->can('toothstatusKy-thuat') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->module) && Yii::$app->controller->module->id == 'toothstatus' && isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'ky-thuat') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/toothstatus/ky-thuat']); ?>">
                                    Kỹ thuật
                                </a>
                            </li>
                            <?php

                        }
                        ?>
                        <?php
                        if (Yii::$app->user->can('toothstatusDo-tuoi') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'do-tuoi') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/toothstatus/do-tuoi']); ?>">
                                    Độ tuổi
                                </a>
                            </li>
                            <?php

                        }
                        ?>
                        <?php
                        if (Yii::$app->user->can('toothstatusLua-chon') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'lua-chon') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/toothstatus/lua-chon']); ?>">
                                    Lựa chọn
                                </a>
                            </li>
                            <?php

                        }
                        ?>
                        <?php
                        if (Yii::$app->user->can('toothstatusDich-vu') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'dich-vu') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/toothstatus/dich-vu']); ?>">
                                    Dịch vụ
                                </a>
                            </li>
                            <?php

                        }
                        ?>
                    </ul>
                </li>
                <?php
            }
            ?>
            <?php
            if (Yii::$app->user->can('directsaleDirect-sale') ||
                Yii::$app->user->can('directsaleRemind-call') ||
                Yii::$app->user->can(User::USER_DEVELOP)) {
                ?>
                <li class="nav-item">
                    <a href="#">
                        <i class="ft-award"></i>
                        <span class="menu-title" data-i18n=""><?= Yii::t('backend', 'Direct Sale'); ?></span>
                    </a>
                    <ul class="menu-content">
                        <?php
                        if (Yii::$app->user->can('directsaleDirect-sale') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'direct-sale') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/directsale/direct-sale']); ?>">
                                    Khách hàng
                                </a>
                            </li>
                            <?php

                        }
                        ?>
                        <?php
                        if (Yii::$app->user->can('directsaleDirect-sale-remind-call') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'direct-sale-remind-call') {
                                echo ' active';
                            } ?>"
                                style="line-height:1.6">
                                <a href="<?= Url::toRoute(['/directsale/direct-sale-remind-call']); ?>">
                                    <span>Nhắc lịch</span>
                                    <span class="badge badge-pill badge-danger float-right"><?= isset($directsaleRemind) && $directsaleRemind != null ? $directsaleRemind : 0 ?></span>
                                </a>
                            </li>
                            <?php

                        }
                        ?>
                    </ul>
                </li>
                <?php
            }
            ?>

            <?php
            if (Yii::$app->user->can('helperLink-face') ||
                Yii::$app->user->can('helperHelper') ||
                Yii::$app->user->can('helperSend-sms') ||
                Yii::$app->user->can('helperCache') ||
                Yii::$app->user->can(User::USER_DEVELOP)) {
                ?>
                <li class="nav-item">
                    <a href="#">
                        <i class="ft-help-circle"></i><span class="menu-title" data-i18n="">Helper</span>
                    </a>
                    <ul class="menu-content">
                        <?php
                        if (Yii::$app->user->can('helperHelper') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'helper') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute('/helper/helper'); ?>">
                                    Helper
                                </a>
                            </li>
                            <?php

                        }
                        ?>
                        <?php
                        if (Yii::$app->user->can('helperLink-face') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'link-face') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute('/helper/link-face'); ?>">
                                    Tìm link face
                                </a>
                            </li>
                            <?php

                        }
                        ?>

                        <?php
                        if (Yii::$app->user->can('helperDel-customer') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'del-customer') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute('/helper/del-customer'); ?>">
                                    Del Customer
                                </a>
                            </li>
                            <?php

                        }
                        ?>
                        <?php
                        if (Yii::$app->user->can('helperPancake') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'pancake') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute('/helper/pancake'); ?>">
                                    Pancake Label
                                </a>
                            </li>
                            <?php

                        }
                        ?>
                        <?php
                        if (Yii::$app->user->can('helperSend-sms') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->module->id == 'helper' && Yii::$app->controller->id == 'send-sms') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute('/helper/send-sms'); ?>">
                                    Gửi tin nhắn
                                </a>
                            </li>
                            <?php

                        }
                        ?>
                        <?php
                        if (Yii::$app->user->can('helperCache') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left">
                                <a href="<?= Url::toRoute('/helper/cache'); ?>">
                                    Cache
                                </a>
                            </li>
                            <?php
                        }
                        ?>

                        <?php
                        if (Yii::$app->user->can('helperConsole') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left">
                                <a href="<?= Url::toRoute('/helper/console'); ?>">
                                    Console
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </li>
                <?php
            }
            ?>


            <?php
            if (Yii::$app->user->can(User::USER_DEVELOP) ||
                Yii::$app->user->can('appmyaurisContent')
            ) {
                ?>
                <li class="nav-item">
                    <a href="#">
                        <i class="ft-monitor"></i><span class="menu-title" data-i18n="">APP Myauris</span>
                    </a>
                    <ul class="menu-content">

                        <?php
                        if (Yii::$app->user->can(User::USER_DEVELOP) ||
                            Yii::$app->user->can('recommendRecommend')
                        ) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'recommend') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/recommend/recommend']); ?>">
                                    Gợi ý giải pháp
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can(User::USER_DEVELOP) ||
                            Yii::$app->user->can('appmyaurisApp-myauris-customer-log')
                        ) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'app-myauris-customer-log') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/appmyauris/app-myauris-customer-log']); ?>">
                                    App Customer Log
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can(User::USER_DEVELOP) ||
                            Yii::$app->user->can('appmyaurisTable-temp')
                        ) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'table-temp') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/appmyauris/table-temp']); ?>">
                                    Table-temp
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can(User::USER_DEVELOP) ||
                            Yii::$app->user->can('appmyaurisList-chup-focus-face')
                        ) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'list-chup-focus-face') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/appmyauris/list-chup-focus-face']); ?>">
                                    List Focus Face
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can(User::USER_DEVELOP) ||
                            Yii::$app->user->can('appmyaurisTinhtrangrang-dotuoi-hasmany')
                        ) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'tinhtrangrang-dotuoi-hasmany') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/appmyauris/tinhtrangrang-dotuoi-hasmany']); ?>">
                                    Tinhtrangrang-dotuoi-hasmany
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can(User::USER_DEVELOP) ||
                            Yii::$app->user->can('appmyaurisApp-myauris-group-san-pham')
                        ) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'app-myauris-group-san-pham') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/appmyauris/app-myauris-group-san-pham']); ?>">
                                    Group Sản Phẩm
                                </a>
                            </li>
                            <?php
                        } ?>

                    </ul>
                </li>
                <?php
            } ?>

            <?php if (Yii::$app->user->can(User::USER_BAC_SI) ||
                Yii::$app->user->can(User::USER_DEVELOP)) {
                ?>
                <li class="nav-item block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'bacsi') {
                    echo ' active';
                } ?>">
                    <a href="<?= Url::toRoute(['/bacsi']); ?>">
                        <i class="fa fa-user-md"></i>
                        <span class="menu-title" data-i18n=""><?= Yii::t('backend', 'Bác sĩ'); ?></span>
                    </a>
                </li>
                <?php
            }
            ?>

            <?php
            if (Yii::$app->user->can('quan-ly') ||
                Yii::$app->user->can(User::USER_DEVELOP)) {
                ?>
                <li class="nav-item block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'quan-ly') {
                    echo ' active';
                } ?>">
                    <a href="<?= Url::toRoute(['/quan-ly']); ?>">
                        <i class="fa fa-user-secret"></i>
                        <span class="menu-title" data-i18n=""><?= Yii::t('backend', 'Quản lý KH'); ?></span>
                    </a>
                </li>
                <?php
            }
            ?>

            <?php
            if (Yii::$app->user->can('social') ||
                Yii::$app->user->can(User::USER_DEVELOP)) {
                ?>
                <li class="nav-item">
                    <a href="#">
                        <i class="fa fa-globe"></i><span class="menu-title" data-i18n="">Social</span>
                    </a>
                    <ul class="menu-content">
                        <?php
                        if (Yii::$app->user->can('social') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'social-facebook') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute('/social/social-facebook'); ?>"
                                   title="Xuất danh sách khách hàng">
                                    Export Data
                                </a>
                            </li>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'analysis-customer') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute('/social/analysis-customer'); ?>"
                                   title="Phân tích khách hàng">
                                    Phân tích KH
                                </a>
                            </li>
                            <?php
                        } ?>

                    </ul>

                </li>
                <?php
            }
            ?>

            <?php
            if (Yii::$app->user->can('testab') ||
                Yii::$app->user->can('testabKy-thuat') ||
                Yii::$app->user->can('testabCampaign') ||
                Yii::$app->user->can('testabChien-dich') ||

                Yii::$app->user->can(User::USER_DEVELOP)) {
                ?>
                <li class="nav-item">
                    <a href="#">
                        <i class="ft-share-2"></i><span class="menu-title" data-i18n="">Test A/B</span>
                    </a>
                    <ul class="menu-content">
                        <?php
                        if (Yii::$app->user->can('testabChien-dich') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'chien-dich') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute('/testab/chien-dich'); ?>">
                                    Chiến dịch
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('testabKy-thuat') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->module) && Yii::$app->controller->module->id == 'testab' && isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'ky-thuat') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute('/testab/ky-thuat'); ?>">
                                    Kỹ thuật
                                </a>
                            </li>
                            <?php
                        } ?>
                    </ul>
                </li>
                <?php
            }
            ?>

            <?php
            if (Yii::$app->user->can('baocao') ||
                Yii::$app->user->can('baocaoBaocao-chay-ads-face') ||
                Yii::$app->user->can('baocaoBaocao-chay-ads-adsword') ||
                Yii::$app->user->can('baocaoBaocao-location') ||
                Yii::$app->user->can('baocaoHieu-suat-online') ||
                Yii::$app->user->can('baocaoBao-cao-online') ||

                Yii::$app->user->can(User::USER_DEVELOP)) {
                ?>
                <li class="nav-item">
                    <a href="#">
                        <i class="ft-printer"></i><span class="menu-title" data-i18n="">Báo cáo</span>
                    </a>
                    <ul class="menu-content">
                        <?php
                        if (Yii::$app->user->can('baocaoBaocao-chay-ads-face') ||
                            Yii::$app->user->can('baocao') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'baocao-chay-ads-face') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute('/baocao/baocao-chay-ads-face'); ?>">
                                    Chạy ads face
                                </a>
                            </li>
                            <?php
                        } ?>
                        <?php
                        if (Yii::$app->user->can('baocaoBao-cao-facebook') ||
                            Yii::$app->user->can('baocao') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'bao-cao-facebook') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute('/baocao/bao-cao-facebook'); ?>">
                                    Biểu đồ Facebook
                                </a>
                            </li>
                            <?php
                        } ?>
                        <?php
                        if (Yii::$app->user->can('baocaoBaocao-chay-ads-adswords') ||
                            Yii::$app->user->can('baocao') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'baocao-chay-ads-adswords') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute('/baocao/baocao-chay-ads-adswords'); ?>">
                                    Chạy adsword
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('baocaoBao-cao-adswords') ||
                            Yii::$app->user->can('baocao') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'bao-cao-adswords') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute('/baocao/bao-cao-adswords'); ?>">
                                    Biểu đồ Adsword
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('baocaoBao-cao-online') ||
                            Yii::$app->user->can('baocao') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'bao-cao-online') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute('/baocao/bao-cao-online'); ?>">
                                    O - Overview
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('baocaoHieu-suat-online') ||
                            Yii::$app->user->can('baocao') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'hieu-suat-online') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute('/baocao/hieu-suat-online'); ?>">
                                    O - Hiệu suất
                                </a>
                            </li>
                            <?php
                        } ?>
                        <?php
                        if (Yii::$app->user->can('baocaoBaocao-location') ||
                            Yii::$app->user->can('baocao') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'baocao-location') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute('/baocao/baocao-location'); ?>">
                                    Khu vực
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('clinicClinic-doanh-thu') ||
                            Yii::$app->user->can('baocao') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'clinic-doanh-thu') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/clinic/clinic-doanh-thu']); ?>">
                                    BC Tổng Hợp
                                </a>
                            </li>
                            <?php
                        } ?>

                    </ul>
                </li>
                <?php
            }
            ?>

            <?php
            if (Yii::$app->user->can('report') ||
                Yii::$app->user->can(User::USER_DEVELOP) ||
                Yii::$app->user->can(User::USER_MANAGER_KE_TOAN) ||
                Yii::$app->user->can(User::USER_KE_TOAN)) {
                ?>
                <li class="nav-item">
                    <a href="#">
                        <i class="ft-repeat"></i><span class="menu-title" data-i18n=""
                                                       title="Report khách hàng">Report</span>
                    </a>
                    <ul class="menu-content">
                        <?php
                        if (Yii::$app->user->can('reportNhan-khau-hoc') ||
                            Yii::$app->user->can('report') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'nhan-khau-hoc') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute('/report/nhan-khau-hoc'); ?>">
                                    Nhân khẩu học
                                </a>
                            </li>
                            <?php
                        } ?>
                        <?php
                        if (Yii::$app->user->can('reportDoanh-thu') ||
                            Yii::$app->user->can('report') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'doanh-thu') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute('/report/doanh-thu'); ?>">
                                    Doanh Thu
                                </a>
                            </li>
                            <?php
                        } ?>
                        <?php
                        if (Yii::$app->user->can('reportKhach-hang-fail') ||
                            Yii::$app->user->can('report') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'khach-hang-fail') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute('/report/khach-hang-fail'); ?>"
                                   title="Khách hàng fail đặt lịch">
                                    Khách hàng fail đặt lịch
                                </a>
                            </li>
                            <?php
                        } ?>
                        <?php
                        if (Yii::$app->user->can('reportDia-ly') ||
                            Yii::$app->user->can('report') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'dia-ly') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute('/report/dia-ly'); ?>">
                                    Địa lý
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('reportLich-moi') ||
                            Yii::$app->user->can('report') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'lich-moi') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute('/report/lich-moi'); ?>">
                                    Lịch mới
                                </a>
                            </li>
                            <?php
                        } ?>
                        <?php
                        if (Yii::$app->user->can('reportLich-hen') ||
                            Yii::$app->user->can('report') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'lich-hen' && Yii::$app->controller->module->id == 'report') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute('/report/lich-hen'); ?>">
                                    Lịch hẹn
                                </a>
                            </li>
                            <?php
                        } ?>
                        <?php
                        if (Yii::$app->user->can('reportKhach-den') ||
                            Yii::$app->user->can('report') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'khach-den') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute('/report/khach-den'); ?>">
                                    Khách đến
                                </a>
                            </li>
                            <?php
                        } ?>
                        <?php
                        if (Yii::$app->user->can('reportLydo-khongden') ||
                            Yii::$app->user->can('report') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'lydo-khongden') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute('/report/lydo-khongden'); ?>" title="Lý do khách không đến">
                                    Lý do khách không đến
                                </a>
                            </li>
                            <?php
                        } ?>
                        <?php
                        if (Yii::$app->user->can('reportKhach-chot') ||
                            Yii::$app->user->can('report') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'khach-chot') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute('/report/khach-chot'); ?>">
                                    Khách chốt
                                </a>
                            </li>
                            <?php
                        } ?>
                        <?php
                        if (Yii::$app->user->can('reportLydo-chotfail') ||
                            Yii::$app->user->can('report') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'lydo-chotfail') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute('/report/lydo-chotfail'); ?>" title="Lý do chốt fail">
                                    Lý do chốt fail
                                </a>
                            </li>
                            <?php
                        } ?>
                        <?php
                        if (Yii::$app->user->can('reportSan-pham') ||
                            Yii::$app->user->can('report') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'san-pham') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute('/report/san-pham'); ?>">
                                    Dịch vụ
                                </a>
                            </li>
                            <?php
                        } ?>
                        <?php
                        if (Yii::$app->user->can('reportProduct') ||
                            Yii::$app->user->can('report') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'product') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute('/report/product'); ?>">
                                    Sản phẩm
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('reportTuong-tac') ||
                            Yii::$app->user->can('report') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'tuong-tac') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute('/report/tuong-tac'); ?>">
                                    Tương tác
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('reportSmart-report') ||
                            Yii::$app->user->can('report') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'smart-report') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute('/report/smart-report'); ?>">
                                    Smart Report
                                </a>
                            </li>
                            <?php
                        } ?>
                    </ul>
                </li>
                <?php
            }
            ?>

            <?php
            if (Yii::$app->user->can(User::USER_DEVELOP) ||
                Yii::$app->user->can('screenonlineOnline') ||
                Yii::$app->user->can('screenonlinePhong-kham') ||
                Yii::$app->user->can('screenonlineCanh-bao') ||
                Yii::$app->user->can('eventsReview')
            ) {
                ?>
                <li class="nav-item">
                    <a href="#">
                        <i class="ft-monitor"></i><span class="menu-title" data-i18n="">Screen</span>
                    </a>
                    <ul class="menu-content">
                        <?php
                        if (Yii::$app->user->can(User::USER_DEVELOP) ||
                            Yii::$app->user->can('screenonlineOnline')
                        ) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'online') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/screenonline/online']); ?>">
                                    Online
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can(User::USER_DEVELOP) ||
                            Yii::$app->user->can('screenonlinePhong-kham')
                        ) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'phong-kham') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/screenonline/phong-kham']); ?>">
                                    Phòng khám
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can(User::USER_DEVELOP) ||
                            Yii::$app->user->can('screenonlineCanh-bao')
                        ) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'canh-bao') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/screenonline/canh-bao']); ?>">
                                    Cảnh báo
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can(User::USER_DEVELOP) ||
                            Yii::$app->user->can('eventsReview')
                        ) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->action->id == 'video') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/events/review/video']); ?>">
                                    Đánh giá
                                </a>
                            </li>
                            <?php
                        } ?>
                    </ul>
                </li>
                <?php
            } ?>

            <?php
            //TODO::
            $dexuat_role = true;
            /* $curr_user = \common\models\User::getUserOne(Yii::$app->user->id);
             if (empty($curr_user->subroleHasOne) || empty($curr_user->subroleHasOne->role)) {
                 $dexuat_role = false;
             }*/

            if ($dexuat_role ||
                Yii::$app->user->can(User::USER_DEVELOP)
                || Yii::$app->user->can(User::USER_ADMINISTRATOR)
            ) {
                ?>
                <li class="nav-item">
                    <a href="#">
                        <i class="fa fa-usd"></i><span class="menu-title" data-i18n="">Chi</span>
                    </a>
                    <ul class="menu-content">
                        <?php
                        if (\backend\modules\user\models\UserSubRole::is_current_user_is_ketoan() || Yii::$app->user->can(User::USER_DEVELOP)
                            || Yii::$app->user->can(User::USER_ADMINISTRATOR)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'danh-muc-chi') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/chi/danh-muc-chi']); ?>">
                                    Danh mục chi
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                        <?php
                        if (\backend\modules\user\models\UserSubRole::is_current_user_is_ketoan() || Yii::$app->user->can(User::USER_DEVELOP)
                            || Yii::$app->user->can(User::USER_ADMINISTRATOR)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'nhom-chi') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/chi/nhom-chi']); ?>">
                                    Nhóm chi
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                        <?php
                        if (\backend\modules\user\models\UserSubRole::is_current_user_is_ketoan() || Yii::$app->user->can(User::USER_DEVELOP)
                            || Yii::$app->user->can(User::USER_ADMINISTRATOR)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'khoan-chi') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/chi/khoan-chi']); ?>">
                                    Khoản chi
                                </a>
                            </li>
                            <?php
                        }
                        ?>

                        <?php
                        if ($dexuat_role || Yii::$app->user->can(User::USER_DEVELOP)
                            || Yii::$app->user->can(User::USER_ADMINISTRATOR)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'de-xuat-chi') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/chi/de-xuat-chi']); ?>">
                                    Đề xuất chi
                                </a>
                            </li>
                            <?php
                        }
                        ?>

                        <?php
                        //                        if (Yii::$app->user->can(User::USER_DEVELOP) || Yii::$app->user->can(User::USER_ADMINISTRATOR) || \backend\modules\user\models\UserSubRole::is_current_user_is_ketoan()) {
                        if (false) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'deadline') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/chi/phieu-thu']); ?>">
                                    Phiếu thu
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                        <?php
                        if (\backend\modules\user\models\UserSubRole::is_current_user_is_ketoan() || Yii::$app->user->can(User::USER_DEVELOP)
                            || Yii::$app->user->can(User::USER_ADMINISTRATOR) || Yii::$app->user->can('chiBao-cao-chi')) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'bao-cao-chi') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/chi/bao-cao-chi']); ?>">
                                    Báo cáo chi
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </li>
            <?php } ?>

            <?php
            if (Yii::$app->user->can('clinic') ||
                Yii::$app->user->can('clinicClinic') ||
                Yii::$app->user->can('clinicClinicIndex') ||
                Yii::$app->user->can('clinicPhong-kham-khuyen-mai') ||
                Yii::$app->user->can('clinicClinic-order') ||
                Yii::$app->user->can('clinicClinic-dieu-tri') ||
                Yii::$app->user->can('clinicClinic-ekip-bacsi') ||
                Yii::$app->user->can('clinicClinic-san-pham') ||
                Yii::$app->user->can('clinicClinic-color') ||
                Yii::$app->user->can('clinicClinic-dich-vu') ||
                Yii::$app->user->can('clinicClinic-loai-thanh-toan') ||
                Yii::$app->user->can('clinicChup-hinh') ||
                Yii::$app->user->can('clinicChup-banh-moi') ||
                Yii::$app->user->can('clinicChup-cui') ||
                Yii::$app->user->can('clinicChup-final') ||
                Yii::$app->user->can('clinicTknc') ||
                Yii::$app->user->can('clinicList-chup-hinh') ||
                Yii::$app->user->can('clinicBao-hanh') ||
                Yii::$app->user->can('labo') ||
                Yii::$app->user->can('laboLabo-don-hang') ||
                Yii::$app->user->can('clinicClinic-checkIndex') ||
                Yii::$app->user->can(User::USER_DEVELOP)) {
                ?>
                <li class="nav-item">
                    <a href="#">
                        <i class="fa fa-medkit"></i><span class="menu-title" data-i18n="">Phòng Khám</span>
                    </a>
                    <ul class="menu-content">

                        <?php
                        if (Yii::$app->user->can('clinicChup-hinh') ||
                            Yii::$app->user->can('clinic') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'chup-hinh') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/clinic/chup-hinh']); ?>">
                                    Chụp hình
                                </a>
                            </li>
                            <?php
                        } ?>
                        <?php
                        /*if (Yii::$app->user->can('clinicChup-banh-moi') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'chup-banh-moi') echo ' active'; ?>">
                                <a href="<?= Url::toRoute(['/clinic/chup-banh-moi']); ?>">
                                    Chụp Banh Môi
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                        <?php
                        if (Yii::$app->user->can('clinicChup-cui') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'chup-cui') echo ' active'; ?>">
                                <a href="<?= Url::toRoute(['/clinic/chup-cui']); ?>">
                                    Chụp Cùi
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                        <?php
                        if (Yii::$app->user->can('clinicChup-final') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'chup-final') echo ' active'; ?>">
                                <a href="<?= Url::toRoute(['/clinic/chup-final']); ?>">
                                    Chụp kết thúc
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                        <?php
                        if (Yii::$app->user->can('clinicTknc') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'tknc') echo ' active'; ?>">
                                <a href="<?= Url::toRoute(['/clinic/tknc']); ?>">
                                    Thiết kế nụ cười
                                </a>
                            </li>
                            <?php
                        }*/
                        ?>
                        <?php
                        /*if (Yii::$app->user->can('clinicClinicImageBeforeAfter') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            */ ?><!--
                            <li class="block-menu-left<?php /*if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'clinic-image-before-after') echo ' active'; */ ?>">
                                <a href="<?/*= Url::toRoute(['/clinic/clinic-image-before-after']); */ ?>">
                                    Before - After
                                </a>
                            </li>
                            --><?php
                        /*                        }*/
                        ?>

                        <?php
                        if (Yii::$app->user->can('clinicLich-hen') ||
                            Yii::$app->user->can('clinic') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'lich-hen' && Yii::$app->controller->module->id == 'clinic') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/clinic/lich-hen']); ?>">
                                    Lịch hẹn
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('clinicClinic') ||
                            Yii::$app->user->can('clinicClinicIndex') ||
                            Yii::$app->user->can('clinic') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'clinic') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/clinic/clinic']); ?>">
                                    Khách hàng
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('clinicClinic-order') ||
                            Yii::$app->user->can('clinicClinic-orderIndex') ||
                            Yii::$app->user->can('clinic') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'clinic-order') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/clinic/clinic-order']); ?>">
                                    Đơn hàng
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('clinicClinic-dieu-tri') ||
                            Yii::$app->user->can('clinicClinic-dieu-triIndex') ||
                            Yii::$app->user->can('clinic') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'clinic-dieu-tri') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/clinic/clinic-dieu-tri']); ?>">
                                    Lịch điều trị
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('clinicClinic-payment') ||
                            Yii::$app->user->can('clinicClinic-paymentIndex') ||
                            Yii::$app->user->can('clinic') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'clinic-payment') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/clinic/clinic-payment']); ?>">
                                    Thanh toán
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('clinicClinic-hoan-coc') ||
                            Yii::$app->user->can('clinicClinic-hoan-cocIndex') ||
                            Yii::$app->user->can('clinic') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'clinic-hoan-coc') {
                                echo ' active';
                            } ?>"
                                style="line-height:1.6">
                                <a href="<?= Url::toRoute(['/clinic/clinic-hoan-coc']); ?>">
                                    <span>Hoàn cọc</span>
                                    <span class="badge badge-pill badge-danger float-right"><?= isset($returnDeposit) ? $returnDeposit : 0 ?></span>
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('clinicClinic-san-pham') ||
                            Yii::$app->user->can('clinic') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'clinic-san-pham') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/clinic/clinic-san-pham']); ?>">
                                    Sản phẩm
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('clinicClinic-dich-vu') ||
                            Yii::$app->user->can('clinic') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'clinic-dich-vu') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/clinic/clinic-dich-vu']); ?>">
                                    Dịch vụ
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('clinicPhong-kham-khuyen-mai') ||
                            Yii::$app->user->can('clinic') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'phong-kham-khuyen-mai') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/clinic/phong-kham-khuyen-mai']); ?>">
                                    Khuyến mãi
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('clinicCheckcode-bao-hanh') ||
                            Yii::$app->user->can('clinic') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'checkcode-bao-hanh') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/clinic/checkcode-bao-hanh']); ?>">
                                    Code Bảo hành
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('laboLabo-don-hang') ||
                            Yii::$app->user->can('labo') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'labo-don-hang') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/labo/labo-don-hang']); ?>">
                                    Phiếu Labo
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        /*if (Yii::$app->user->can('clinicClinic-color') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'clinic-color') echo ' active'; ?>">
                                <a href="<?= Url::toRoute(['/clinic/clinic-color']); ?>">
                                    Màu sắc
                                </a>
                            </li>
                            <?php
                        }*/
                        ?>
                        <?php
                        if (Yii::$app->user->can('clinicClinic-loai-thanh-toan') ||
                            Yii::$app->user->can('clinic') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'clinic-loai-thanh-toan') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/clinic/clinic-loai-thanh-toan']); ?>">
                                    Loại thanh toán
                                </a>
                            </li>
                            <?php
                        } ?>
                        <?php
                        if (Yii::$app->user->can('clinicList-chup-hinh') ||
                            Yii::$app->user->can('clinic') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'list-chup-hinh') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/clinic/list-chup-hinh']); ?>">
                                    Loại chụp hình
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('clinicPhong-kham-pki') ||
                            Yii::$app->user->can('clinic') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'phong-kham-kpi') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/clinic/phong-kham-kpi']); ?>">
                                    KPI
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('clinicClinic-checkIndex') ||
                            Yii::$app->user->can('clinic') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'clinic-check' && Yii::$app->controller->module->id == 'clinic') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/clinic/clinic-check/index']); ?>">
                                    Check Doanh Số
                                </a>
                            </li>
                            <?php
                        } ?>

                    </ul>
                </li>
                <?php
            }
            ?>

            <?php
            if (Yii::$app->user->can('customerCustomer-onlineIndex') ||
                Yii::$app->user->can('customerAffiliate-customer-contact') ||
                Yii::$app->user->can('bookingCustomer-online-booking') ||
                Yii::$app->user->can('customerCustomer-feedback') ||
                Yii::$app->user->can(User::USER_DEVELOP)) {
                ?>
                <li class="nav-item">
                    <a href="#">
                        <i class="ft-users"></i><span class="menu-title" data-i18n="">Online</span>
                    </a>
                    <ul class="menu-content">
                        <?php
                        if (Yii::$app->user->can('customerCustomer-onlineImport-customer') || Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'customer-online' && Yii::$app->controller->action->id == 'import-customer') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/customer/customer-online/import-customer']); ?>">
                                    Khách hàng cũ
                                </a>
                            </li>
                            <?php
                        } ?>
                        <?php
                        if (Yii::$app->user->can('customerCustomer-onlineIndex') || Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'customer-online' && Yii::$app->controller->action->id == 'index') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/customer/customer-online']); ?>">
                                    Khách hàng
                                </a>
                            </li>
                            <?php
                        } ?>
                        <?php
                        if (Yii::$app->user->can('customerCustomer-online-remind-call') || Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'customer-online-remind-call' && Yii::$app->controller->action->id == 'index') {
                                echo ' active';
                            } ?>"
                                style="line-height:1.6">
                                <a href="<?= Url::toRoute(['/customer/customer-online-remind-call']); ?>">
                                    <span>Nhắc lịch</span>
                                    <span class="badge badge-pill badge-danger float-right"><?= isset($customerRemind) && $customerRemind != null ? $customerRemind : 0 ?></span>
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('customerAffiliate-customer-contact') || Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'affiliate-customer-contact' && Yii::$app->controller->action->id == 'index') {
                                echo ' active';
                            } ?>"
                                style="line-height:1.6">
                                <a href="<?= Url::toRoute(['/customer/affiliate-customer-contact']); ?>">
                                    <span>Website</span>
                                    <span class="badge badge-pill badge-danger float-right"><?= isset($affiliateCustomerContact) && $affiliateCustomerContact != null ? $affiliateCustomerContact : 0 ?></span>

                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('bookingCustomer-online-booking') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'customer-online-booking') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/booking/customer-online-booking']) ?>">
                                    <span>Đặt lịch</span>
                                    <span class="badge badge badge-pill badge-danger float-right"><?= isset($totalBooking) ? $totalBooking : 0; ?></span>
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('customerCustomer-onlineCustomer-online-sms-send') || Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->action->id) && Yii::$app->controller->action->id == 'customer-online-sms-send') {
                                echo ' active';
                            } ?>"
                                style="line-height:1.6">
                                <a href="<?= Url::toRoute(['/customer/customer-online/customer-online-sms-send']); ?>">
                                    <span>Gửi SMS</span>
                                    <span class="badge badge badge-pill badge-danger float-right"><?= $total; ?></span>
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'customer-token') {
                                echo ' active';
                            } ?>"
                                style="line-height:1.6">
                                <a href="<?= Url::toRoute(['/customer/customer-token']); ?>">
                                    <span>Token khách hàng</span>
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('customerCustomer-feedback') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'customer-feedback') {
                                echo ' active';
                            } ?>"
                                style="line-height:1.6">
                                <a href="<?= Url::toRoute(['/customer/customer-feedback']); ?>">
                                    <span>Feedback khách hàng</span>
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('customerCustomer-onlineCustomer-online-dich-vu') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'customer-online-dich-vu') {
                                echo ' active';
                            } ?>"
                                style="line-height:1.6">
                                <a href="<?= Url::toRoute(['/customer/customer-online-dich-vu']); ?>">
                                    <span>Dịch vụ</span>
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('customerImport-data') || Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->action->id) && Yii::$app->controller->action->id == 'import-pancake') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/customer/import-data/import-pancake']); ?>">
                                    Nhập Pancake
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('customerImport-dataImport-pancake-new') || Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->action->id) && Yii::$app->controller->action->id == 'import-pancake-new') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/customer/import-data/import-pancake-new']); ?>">
                                    Nhập Tương Tác
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('customer') ||
                            Yii::$app->user->can('customerCustomer-online-nguon') ||
                            Yii::$app->user->can('customerCustomer-online-nguonIndex') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'customer-online-nguon') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/customer/customer-online-nguon']); ?>">
                                    Nguồn Khách hàng
                                </a>
                            </li>
                            <?php
                        } ?>
                        <?php
                        if (Yii::$app->user->can('customerCustomer-online-agency') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'customer-online-agency') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/customer/customer-online-agency']); ?>">
                                    Agency
                                </a>
                            </li>
                            <?php
                        } ?>
                        <?php
                        if (Yii::$app->user->can('customer') ||
                            Yii::$app->user->can('customerCustomer-online-fanpage') ||
                            Yii::$app->user->can('customerCustomer-online-fanpageIndex') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'customer-online-fanpage') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/customer/customer-online-fanpage']); ?>">
                                    Fanpage
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('customerCustomer-online-genitive') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'customer-online-genitive') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/customer/customer-online-genitive']); ?>">
                                    Nhóm tính cách
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('customerCustomer-online-fail-status') ||
                            Yii::$app->user->can(User::USER_ADMINISTRATOR) ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'customer-online-fail-status') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/customer/customer-online-fail-status']); ?>">
                                    Trạng thái Fail
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('customerCustomer-online-fail-dathen') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'customer-online-fail-dathen') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/customer/customer-online-fail-dathen']); ?>">
                                    Đặt hẹn Fail
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('customer') ||
                            Yii::$app->user->can('ustomerCustomer-online-status') ||
                            Yii::$app->user->can('customerCustomer-online-statusIndex') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'customer-online-status') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/customer/customer-online-status']); ?>">
                                    Trạng thái khách
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('customer') ||
                            Yii::$app->user->can('customerCustomer-online-dathen-status') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'customer-online-dathen-status') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/customer/customer-online-dathen-status']); ?>">
                                    Trạng thái đặt hẹn
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('customer') ||
                            Yii::$app->user->can('customerCustomer-online-come') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'customer-online-come') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/customer/customer-online-come']); ?>">
                                    Trạng thái khách đến
                                </a>
                            </li>
                            <?php
                        } ?>
                    </ul>
                </li>
                <?php
            }
            ?>

            <?php
            if (Yii::$app->user->can(User::USER_DEVELOP) ||
                Yii::$app->user->can('seo') ||
                Yii::$app->user->can('seoMyauris-analytics-log') ||
                Yii::$app->user->can('seoMyauris-analytics-logIndex')) {
                ?>
                <li class="nav-item">
                    <a href="#">
                        <i class="fa fa-calendar"></i><span class="menu-title" data-i18n="">SEO</span>
                    </a>
                    <ul class="menu-content">
                        <?php
                        if (Yii::$app->user->can('seoMyauris-analytics-log') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'myauris-analytics-log') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/seo/myauris-analytics-log']) ?>">
                                    My Auris Analytics
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>

            <?php
            if (Yii::$app->user->can(User::USER_DEVELOP) ||
                Yii::$app->user->can('booking') ||
                Yii::$app->user->can('bookingTime-work') ||
                Yii::$app->user->can('bookingUser-register')) {
                ?>
                <li class="nav-item">
                    <a href="#">
                        <i class="fa fa-calendar"></i><span class="menu-title" data-i18n="">Lịch khám</span>
                    </a>
                    <ul class="menu-content">
                        <?php
                        if (Yii::$app->user->can('bookingTime-work') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'time-work') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/booking/time-work']) ?>">
                                    Thời gian làm việc
                                </a>
                            </li>
                            <?php
                        } ?>
                        <?php
                        if (Yii::$app->user->can('bookingUser-register') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'user-register') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/booking/user-register']) ?>">
                                    Khách hàng
                                </a>
                            </li>
                            <?php
                        } ?>
                    </ul>
                </li>
                <?php
            } ?>

            <?php
            if (Yii::$app->user->can('affiliate') ||
                Yii::$app->user->can('affiliateAffiliate-templates') ||
                Yii::$app->user->can('affiliateAffiliate-customer') ||
                Yii::$app->user->can(User::USER_DEVELOP)) {
                ?>
                <li class="nav-item">
                    <a href="#">
                        <i class="ft-link"></i><span class="menu-title" data-i18n="">Affiliate</span>
                    </a>
                    <ul class="menu-content">
                        <?php
                        if (Yii::$app->user->can('affiliateAffiliate-templates') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'affiliate-templates') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/affiliate/affiliate-templates']); ?>">
                                    Mẫu trang landing
                                </a>
                            </li>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'customer-contact') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/affiliate/customer-contact']); ?>">
                                    Supports
                                </a>
                            </li>
                            <?php
                        } ?>
                        <?php
                        if (Yii::$app->user->can('affiliateAffiliate-customer') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'affiliate-customer') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/affiliate/affiliate-customer']); ?>">
                                    Khách hàng
                                </a>
                            </li>
                            <?php
                        } ?>
                    </ul>
                </li>
                <?php
            }
            ?>

            <?php
            if (Yii::$app->user->can('general') ||
                Yii::$app->user->can('generalNotification') ||
                Yii::$app->user->can('generalContact-phone') ||
                Yii::$app->user->can(User::USER_DEVELOP)) {
                ?>
                <li class="nav-item">
                    <a href="#">
                        <i class="ft-globe"></i><span class="menu-title" data-i18n="">General</span>
                    </a>
                    <ul class="menu-content">
                        <?php
                        if (Yii::$app->user->can('generalNotification') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'notification') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/general/notification']); ?>">
                                    Thông báo
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('generalContact-phone') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'contact-phone') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/general/contact-phone']); ?>">
                                    Danh bạ
                                </a>
                            </li>
                            <?php
                        }
                        ?>

                    </ul>
                </li>
                <?php
            }
            ?>

            <?php if (Yii::$app->user->can(User::USER_DEVELOP)) {
                ?>
                <li class="nav-item block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'file') {
                    echo ' active';
                } ?>">
                    <a href="<?= Url::toRoute(['/filemanager']); ?>">
                        <i class="fa fa-paperclip"></i>
                        <span class="menu-title" data-i18n=""><?= Yii::t('backend', 'File Manager'); ?></span>
                    </a>
                </li>
                <?php
            }
            ?>

            <?php if (Yii::$app->user->can(User::USER_DEVELOP) ||
                Yii::$app->user->can('issue')) {
                ?>
                <li class="nav-item block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'issue') {
                    echo ' active';
                } ?>">
                    <a href="<?= Url::toRoute(['/issue']); ?>">
                        <i class="fa fa-bug"></i>
                        <span class="menu-title" data-i18n=""><?= Yii::t('backend', 'Issue'); ?></span>
                    </a>
                </li>
                <?php
            }
            ?>

            <?php
            if (Yii::$app->user->can('userUser') ||
                Yii::$app->user->can('userUserIndex') ||
                Yii::$app->user->can('userPhong-banIndex') ||
                Yii::$app->user->can(User::USER_DEVELOP)) {
                ?>
                <li class="nav-item">
                    <a href="#">
                        <i class="ft-users"></i><span class="menu-title" data-i18n="">Nhân viên</span>
                    </a>
                    <ul class="menu-content">
                        <?php
                        if (Yii::$app->user->can('userUserIndex')) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'user') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/user/user']); ?>">
                                    Nhân viên
                                </a>
                            </li>
                            <?php
                        }
                        ?>

                        <?php
                        if (Yii::$app->user->can('user') ||
                            Yii::$app->user->can('userRole') ||
                            Yii::$app->user->can('userRoleIndex') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'role') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/user/role']); ?>">
                                    Phân quyền
                                </a>
                            </li>
                            <?php
                        }
                        ?>

                        <?php
                        if (Yii::$app->user->can('user') ||
                            Yii::$app->user->can('userPermission') ||
                            Yii::$app->user->can('userPermissionIndex') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'permission') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/user/permission']); ?>">
                                    Permission
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                        <?php
                        if (Yii::$app->user->can('user') ||
                            Yii::$app->user->can('userPhong-ban') ||
                            Yii::$app->user->can('userPhong-banIndex') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'phong-ban') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/user/phong-ban']); ?>">
                                    Phòng ban
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </li>
                <?php
            }
            ?>

            <?php
            if (Yii::$app->user->can('log') ||
                Yii::$app->user->can('logCall-log') ||
                Yii::$app->user->can('logVht-call-log') ||
                Yii::$app->user->can('logSystem-log') ||
                Yii::$app->user->can(User::USER_DEVELOP)) {
                ?>
                <li class="nav-item">
                    <a href="#">
                        <i class="ft-aperture"></i><span class="menu-title"
                                                         data-i18n=""><?= Yii::t('backend', 'Log'); ?></span>
                    </a>
                    <ul class="menu-content">
                        <?php
                        if (Yii::$app->user->can('logVht-call-log') || Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'vht-call-log') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/log/vht-call-log']); ?>">
                                    Vht Call Log
                                </a>
                            </li>
                            <?php
                        }
                        ?>

                        <?php
                        /*if (Yii::$app->user->can('logCall-log') || Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'call-log') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/log/call-log']); ?>">
                                    Call Log
                                </a>
                            </li>
                            <?php
                        }*/
                        ?>

                        <?php
                        if (Yii::$app->user->can('logSystem-log') || Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'system-log') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/log/system-log']); ?>">
                                    System Log
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                        <?php
                        if (Yii::$app->user->can('logSend-sms') || Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->module->id == 'log' && Yii::$app->controller->id == 'send-sms') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/log/send-sms']); ?>">
                                    Sms Log
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </li>
                <?php
            }
            ?>

            <?php
            if (Yii::$app->user->can('location') ||
                Yii::$app->user->can(User::USER_DEVELOP)) {
                ?>
                <li class="nav-item">
                    <a href="#">
                        <i class="ft-map-pin"></i><span class="menu-title"
                                                        data-i18n=""><?= Yii::t('backend', 'Địa danh'); ?></span>
                    </a>
                    <ul class="menu-content">
                        <?php
                        if (Yii::$app->user->can('locationLocation') || Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'location') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/location']); ?>">
                                    <?= Yii::t('backend', 'Địa danh'); ?>
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                        <?php
                        if (Yii::$app->user->can('locationWard') || Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'ward') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/location/ward']); ?>">
                                    Phường/Xã
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                        <?php
                        if (Yii::$app->user->can('locationDistrict') || Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'district') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/location/district']); ?>">
                                    Quận/Huyện
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                        <?php
                        if (Yii::$app->user->can('locationProvince') || Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'province') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/location/province']); ?>">
                                    Tỉnh Thành
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                        <?php
                        if (Yii::$app->user->can('locationCountry') || Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'country') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/location/country']); ?>">
                                    Quốc Gia
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </li>
                <?php
            }
            ?>

            <?php
            if (Yii::$app->user->can('event') ||
                Yii::$app->user->can('review') ||
                Yii::$app->user->can(User::USER_DEVELOP)) {
                ?>
                <li class="nav-item">
                    <a href="#">
                        <i class="fa fa-cogs"></i><span class="menu-title" data-i18n="">Event Source</span>
                    </a>
                    <ul class="menu-content">
                        <?php
                        if (Yii::$app->user->can('event') ||
                            Yii::$app->user->can('review') ||
                            Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'review') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/events/review']); ?>">
                                    Khách đánh giá
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </li>
                <?php
            }
            ?>


            <?php
            if (Yii::$app->user->can('settingCo-so') ||
                Yii::$app->user->can('setting') ||
                Yii::$app->user->can('settingSetting-key-value') ||
                Yii::$app->user->can('settingSetting-sms-send') ||
                Yii::$app->user->can(User::USER_DEVELOP)) {
                ?>
                <li class="nav-item">
                    <a href="#">
                        <i class="ft-settings"></i><span class="menu-title" data-i18n="">Cài đặt</span>
                    </a>
                    <ul class="menu-content">
                        <?php
                        if (Yii::$app->user->can('setting') || Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'setting') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/setting/setting']); ?>">
                                    Cấu hình
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('setting-key-value') || Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'setting-key-value') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/setting/setting-key-value']); ?>">
                                    Key - Value
                                </a>
                            </li>
                            <?php
                        } ?>

                        <?php
                        if (Yii::$app->user->can('settingCo-so') || Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'co-so') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/setting/co-so']); ?>">
                                    Cơ sở
                                </a>
                            </li>
                            <?php
                        } ?>
                        <?php
                        if (Yii::$app->user->can('settingSetting-sms-send') || Yii::$app->user->can(User::USER_DEVELOP)) {
                            ?>
                            <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'setting-sms-send') {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute(['/setting/setting-sms-send']); ?>">
                                    Cài đặt Sms
                                </a>
                            </li>
                            <?php
                        } ?>
                    </ul>
                </li>
                <?php
            }
            ?>

            <li class="nav-item">
                <a href="#">
                    <i class="ft-hash"></i><span class="menu-title" data-i18n="">Hỗ trợ</span>
                </a>
                <ul class="menu-content">
                    <?php
                    if (Yii::$app->user->can(User::USER_DEVELOP) ||
                        Yii::$app->user->can('supportSupport')) {
                        ?>
                        <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'support') {
                            echo ' active';
                        } ?>">
                            <a href="<?= Url::toRoute(['/support/support']); ?>">
                                Đặt câu hỏi
                            </a>
                        </li>
                        <?php
                    }
                    ?>

                    <?php
                    if (Yii::$app->user->can(User::USER_DEVELOP) ||
                        Yii::$app->user->can('supportSupport-catagory')) {
                        ?>
                        <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'support-catagory') {
                            echo ' active';
                        } ?>">
                            <a href="<?= Url::toRoute(['/support/support-catagory']); ?>">
                                Danh mục
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                    <?php
                    if (isset($supportCatagories)) {
                        foreach ($supportCatagories as $catagory) { ?>
                            <li class="block-menu-left<?php if (Yii::$app->request->get('catagory_id') !== null && Yii::$app->request->get('catagory_id') == $catagory->id) {
                                echo ' active';
                            } ?>">
                                <a href="<?= Url::toRoute([
                                    '/support/list-support',
                                    'catagory_id' => $catagory->id
                                ]); ?>">
                                    <?= $catagory->name; ?>
                                </a>
                            </li>
                        <?php }
                    } ?>
                </ul>
            </li>
            <?php
            if (Yii::$app->user->can(User::USER_DEVELOP)) {
                ?>
                <li class="nav-item">
                    <a href="#">
                        <i class="ft-check-circle"></i><span class="menu-title" data-i18n="">Tester</span>
                    </a>
                    <ul class="menu-content">
                        <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'google-driver-api') {
                            echo ' active';
                        } ?>">
                            <a href="<?= Url::toRoute(['/test/google-driver-api']); ?>">
                                Google driver Api
                            </a>
                        </li>

                        <li class="block-menu-left<?php if (isset(Yii::$app->controller->id) && Yii::$app->controller->id == 'pngtree') {
                            echo ' active';
                        } ?>">
                            <a href="<?= Url::toRoute(['/test/pngtree']); ?>">
                                Png tree
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="<?= Url::to('https://docs.myauris.vn'); ?>" target="_blank">
                        <i class="fa fa-gear"></i>
                        <span class="menu-title" data-i18n=""><?= Yii::t('backend', 'Api Docs'); ?></span>
                    </a>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
</div>
<?php \yii\widgets\Pjax::end() ?>
