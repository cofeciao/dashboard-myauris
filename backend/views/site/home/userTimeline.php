<?php

use backend\modules\user\models\UserTimelineModel;

?>
<div class="dashboard-list">
    <ul>
        <?php
        foreach ($data as $item) {
            if ($item != null) {
                ?>
                <li class="dashboard-Invoices">
            <span class="dash_icon ng-scope">
                <i class="icon-user-follow"></i>
            </span>

                    <a href="#"
                       class="ng-binding"><?= $item->nameUserHasOne == null ? "Không tồn tại" : $item->nameUserHasOne->fullname ?></a>
                    <span class="ng-binding">vừa</span>
                    <a href="#" class="ng-binding">
                        <?php

                        if ($item->action != null) {
                            $str = '';
                            if (is_array($item->action)) {
                                foreach ($item->action as $i) {
                                    $str .= UserTimelineModel::LIST[$i] . ' ';
                                }
                            } else {
                                $str .= UserTimelineModel::LIST[$item->action] . ' ';
                            }
                            echo $str;
                        } ?>
                    </a>
                    <span class="ng-binding">khách hàng</span>
                    <a class="ng-binding"
                       href="#"> <?= $item->nameCustomerHasOne == null ? "Không tồn tại" : $item->nameCustomerHasOne->name; ?>
                        <span class="txtB fs12 ng-binding"></span></a>
                    <span class="ng-binding"><?php
                        if (isset($item->nameCustomerHasOne) && $item->nameCustomerHasOne != null) {
                            if ($item->nameCustomerHasOne->is_customer_who == 2) {
                                echo '';
                            }
                        } ?></span>
                    <span class="dpb fs11 txtI date ng-binding"><?php echo \common\helpers\MyHelper::time_elapsed_string('@' . $item->created_at); ?></span>
                </li>
            <?php
            }
        } ?>
    </ul>
</div>
