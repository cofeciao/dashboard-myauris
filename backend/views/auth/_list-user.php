<?php

use yii\helpers\Url;

?>
<ul class="list-user">
    <?php
    if (isset($params['listUser']) && is_array($params['listUser'])) {
        foreach ($params['listUser'] as $username => $user) {
            ?>
            <li>
                <a href="<?= Url::toRoute(['login', 'step' => 'login', 'user' => $username]) ?>">
                    <div class="user">
                        <div class="user-avatar">
                            <span class="avatar"><?= strtoupper(substr($user['name'], 0, 1)) ?></span>
                        </div>
                        <div class="user-info">
                            <div class="user-name"><?= $user['name'] ?></div>
                            <div class="user-email"><?= $user['email'] ?></div>
                        </div>
                    </div>
                </a>
            </li>
        <?php
        }
    } ?>
    <li>
        <a href="<?= Url::toRoute(['login', 'step' => 'login']) ?>">
            <div class="user">
                <div class="user-avatar user-avatar-default">
                    <span class="avatar"><i class="fa fa-user-o"></i></span>
                </div>
                <div class="user-info">
                    <div class="user-name">Đăng nhập bằng tài khoản khác</div>
                </div>
            </div>
        </a>
    </li>
</ul>