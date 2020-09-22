<?php

use yii\helpers\Html;
use \yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $token string */

?>
<div class="mj-container" style="background-color:#eceff4;"><!--[if mso | IE]>
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="700" align="center"
           style="width:700px;">
        <tr>
            <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
    <![endif]-->
    <div style="margin:0px auto;max-width:700px;">
        <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center"
               border="0">
            <tbody>
            <tr>
                <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:20px 0px;padding-bottom:24px;padding-top:0px;"></td>
            </tr>
            </tbody>
        </table>
    </div>
    <!--[if mso | IE]>
    </td></tr></table>
    <![endif]-->
    <!--[if mso | IE]>
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="700" align="center"
           style="width:700px;">
        <tr>
            <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
    <![endif]-->
    <div style="margin:0px auto;max-width:700px;background:#d8e2e7;">
        <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;background:#d8e2e7;"
               align="center" border="0">
            <tbody>
            <tr>
                <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:1px;">
                    <!--[if mso | IE]>
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="vertical-align:top;width:700px;">
                    <![endif]-->
                    <div class="mj-column-per-100 outlook-group-fix"
                         style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
                        <table role="presentation" cellpadding="0" cellspacing="0" style="background:white;"
                               width="100%" border="0">
                            <tbody>
                            <tr>
                                <td style="word-wrap:break-word;font-size:0px;padding:30px 30px 18px;" align="left">
                                    <div style="cursor:auto;color:#000000;font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:24px;line-height:22px;text-align:left;">
                                        Thông báo từ <b>phần mềm dashboard MyAuris</b>.
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="word-wrap:break-word;font-size:0px;padding:0px 30px 16px;" align="left">
                                    <div style="cursor:auto;color:#000000;font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;line-height:22px;text-align:left;">
                                        Chào <b><?php
                                            if (empty($dexuat) || $dexuat->chosenHasOne == null) {
                                                echo 'Xin vui lòng cập nhật họ tên cá nhân trong dashboard myauris';
                                            } else {
                                                echo Html::encode($dexuat->chosenHasOne->userProfile->fullname);
                                            } ?></b>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="word-wrap:break-word;font-size:0px;padding:0px 30px 6px;" align="left">
                                    <div style="cursor:auto;color:#000000;font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;line-height:22px;text-align:left;">
                                        <br>
                                        <?php echo 'Nhân viên ' . ($dexuat->nguoidexuatHasOne != null && $dexuat->nguoidexuatHasOne->fullname != null ? $dexuat->nguoidexuatHasOne->fullname : '') . ' vừa tạo đề xuất chi!' ?>
                                        Đề xuất <a
                                                href="<?php echo FRONTEND_HOST_INFO . Url::to(['/chi/de-xuat-chi/update', 'id' => $dexuat->id]); ?>"><?php echo $dexuat->title ?></a>
                                        <br><br>
                                        Vui lòng xem qua và cập nhật lại đề xuất.
                                        <br>
                                        <br><br>
                                        <br>
                                        Cảm ơn bạn đã đọc.
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="word-wrap:break-word;font-size:0px;padding:8px 16px 10px;padding-bottom:16px;padding-right:30px;padding-left:30px;"
                                    align="left">
                                    <table role="presentation" cellpadding="0" cellspacing="0"
                                           style="border-collapse:separate;" align="left" border="0">
                                        <tbody>
                                        <tr>
                                            <td style="border:none;border-radius:25px;color:white;cursor:auto;padding:10px 25px; "
                                                align="center" valign="middle" bgcolor="#00a8ff"><a
                                                        href="<?= FRONTEND_HOST_INFO . Url::to(['/chi/de-xuat-chi']); ?>"
                                                        style="text-decoration:none;background:#00a8ff;color:white;font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;font-weight:400;line-height:120%;text-transform:none;margin:0px;"
                                                        target="_blank">Truy cập trang đề xuất chi</a></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="word-wrap:break-word;font-size:0px;padding:0px 30px 30px 30px;" align="left">
                                    <div style="cursor:auto;color:#000000;font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;line-height:22px;text-align:left;">
                                        Phòng công nghệ.
                                        <br>
                                        Email người hỗ trợ: dev.thang@myauris.vn
                                        <br>
                                        Hotline: 0935454336
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!--[if mso | IE]>
                    </td></tr></table>
                    <![endif]--></td>
            </tr>
            </tbody>
        </table>
    </div>
    <!--[if mso | IE]>
    </td></tr></table>
    <![endif]-->
    <!--[if mso | IE]>
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="700" align="center"
           style="width:700px;">
        <tr>
            <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
    <![endif]-->
    <div style="margin:0px auto;max-width:700px;">
        <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center"
               border="0">
            <tbody>
            <tr>
                <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:20px 0px 0px;">
                    <!--[if mso | IE]>
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="vertical-align:top;width:700px;">
                    <![endif]-->
                    <div class="mj-column-per-100 outlook-group-fix"
                         style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
                        <table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                            <tbody>
                            <tr>
                                <td style="word-wrap:break-word;font-size:0px;padding:0px;" align="center">
                                    <div style="cursor:auto;color:#6b7a85;font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;line-height:22px;text-align:center;">
                                        Liên hệ
                                        <a href="#" style="text-decoration: none; color: inherit;">
                                            <span style="border-bottom: solid 1px #b3bac1">dev.thang@myauris.vn</span>
                                        </a>
                                        nếu có vấn đề nào đó bạn không hiểu.
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!--[if mso | IE]>
                    </td></tr></table>
                    <![endif]--></td>
            </tr>
            </tbody>
        </table>
    </div>
    <!--[if mso | IE]>
    </td></tr></table>
    <![endif]-->
    <!--[if mso | IE]>
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="700" align="center"
           style="width:700px;">
        <tr>
            <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
    <![endif]-->
    <div style="margin:0px auto;max-width:700px;">
        <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center"
               border="0">
            <tbody>
            <tr>
                <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:20px 0px;padding-bottom:24px;padding-top:0px;"></td>
            </tr>
            </tbody>
        </table>
    </div>
    <!--[if mso | IE]>
    </td></tr></table>
    <![endif]-->
</div>



