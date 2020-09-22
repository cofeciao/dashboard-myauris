<?php
/**
 * Created by PhpStorm.
 * User: mongd
 * Date: 20-Jul-18
 * Time: 11:27 PM
 */

namespace frontend\components;

use frontend\models\Config;

class EmailComponent
{
    public static function sendEmail($subject, $content = '')
    {
        $email = Config::find()->one();
        $emailSend = $email->email == ''? \Yii::$app->params['adminEmail'] : $email->email;

        $content = self::renderTemplate($content);
        $emailSend = \Yii::$app->mailer->compose(['html' => 'layouts/html'], ['content' => $content])
            ->setFrom(["mongdao.wd@gmail.com"])
            ->setTo($emailSend)
            ->setSubject($subject);
        return $emailSend->send();
    }

    protected static function renderTemplate($content)
    {
        return '<div class="mj-container" style="background-color:#eceff4;"><!--[if mso | IE]>
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
                                        Thông báo từ Swagger.com.vn.
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="word-wrap:break-word;font-size:0px;padding:0px 30px 16px;" align="left">
                                    <div style="cursor:auto;color:#000000;font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;line-height:22px;text-align:left;">
                                        Chào <b>quản trị viên</b>, cảm ơn bạn vì đã đọc thư.
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="word-wrap:break-word;font-size:0px;padding:0px 30px 6px;" align="left">
                                    <div style="cursor:auto;color:#000000;font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;line-height:22px;text-align:left;">
                                        ' . $content . '
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
                                                        href="http://swagger.com.vn"
                                                        style="text-decoration:none;background:#00a8ff;color:white;font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;font-weight:400;line-height:120%;text-transform:none;margin:0px;"
                                                        target="_blank">Truy cập trang Swagger.com.vn</a></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="word-wrap:break-word;font-size:0px;padding:0px 30px 30px 30px;" align="left">
                                    <div style="cursor:auto;color:#000000;font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;line-height:22px;text-align:left;">
                                        Cảm ơn rất nhiều.
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
                                            <span style="border-bottom: solid 1px #b3bac1">mongdaovan86.wd@gmail.com</span>
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
    <![endif]--></div>';
    }

}