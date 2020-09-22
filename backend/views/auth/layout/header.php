<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 11-Dec-18
 * Time: 3:04 PM
 */

use yii\helpers\Url;
use yii\helpers\Html;
use common\models\UserProfile;

?>
<div id="user-profile">
    <div class="row">
        <div class="col-12">
            <div class="card profile-with-cover">
                <div class="card-img-top img-fluid bg-cover height-300"
                     style="background: url(<?= Url::to('@web/images/carousel/22.jpg'); ?>) 50%;"></div>
                <div class="media profil-cover-details w-100">
                    <div class="media-left pl-2 pt-2">
                        <a href="#" class="profile-image">
                            <img src="<?= UserProfile::getAvatar('200x200') ?>"
                                 class="rounded-circle img-border height-100"
                                 alt="Card image">
                        </a>
                    </div>
                    <div class="media-body pt-3 px-2">
                        <div class="row">
                            <div class="col">
                                <h3 class="card-title"><?= UserProfile::getFullName(); ?></h3>
                            </div>
                            <div class="col text-right">
                                <button type="button" class="btn btn-primary"><i class="fa fa-plus"></i> Follow
                                </button>
                                <div class="btn-group d-none d-md-block float-right ml-2" role="group"
                                     aria-label="Basic example">
                                    <button type="button" class="btn btn-success"><i class="fa fa-dashcube"></i>
                                        Message
                                    </button>
                                    <button type="button" class="btn btn-success"><i class="fa fa-cog"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <nav class="navbar navbar-light navbar-profile align-self-end">
                    <button class="navbar-toggler d-sm-none" type="button" data-toggle="collapse"
                            aria-expanded="false"
                            aria-label="Toggle navigation"></button>
                    <nav class="navbar navbar-expand-lg">
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav mr-auto">
                                <li class="nav-item active">
                                    <a class="nav-link" href="#"><i class="fa fa-line-chart"></i> Timeline <span
                                                class="sr-only">(current)</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?=Url::toRoute(['/auth/profile']); ?>"><i class="ft-user"></i> Thông tin cá nhân</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?=Url::toRoute(['/auth/change-pass-word']); ?>"><i class="ft-edit"></i> Thay đổi mật khẩu</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#"><i class="fa fa-bell-o"></i> Notifications</a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </nav>
            </div>
        </div>
    </div>
</div>
