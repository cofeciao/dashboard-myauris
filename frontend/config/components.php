<?php
/**
 * Created by PhpStorm.
 * User: mongd
 * Date: 29-Jul-18
 * Time: 9:54 PM
 */

return [
//    'geoip' => ['class' => 'lysenkobv\GeoIP\GeoIP'],
    'errorHandler' => [
        'errorAction' => 'site/error'
    ],
    'request' => [
        'enableCookieValidation' => true,
        'cookieValidationKey' => FRONTEND_COOKIE_VALIDATION_KEY,
        'baseUrl' => '',
    ],
    'assetManager' => [
        'appendTimestamp' => true,
        'bundles' => [
            'yii\web\JqueryAsset' => [
                'js' => [],
                'jsOptions' => ['position' => \yii\web\View::POS_END],
            ],
            'yii\bootstrap\BootstrapPluginAsset' => [
                'js' => []
            ],
            'yii\bootstrap\BootstrapAsset' => ['css' => ['/css/bootstrap.min.css']],
        ],
    ],

    'authManager' => [
        'class' => 'yii\rbac\DbManager',
        'defaultRoles' => ['user_users'],
    ],

    'user' => [
        'class' => yii\web\User::class,
        'identityClass' => common\models\User::class,
        'loginUrl' => ['auth/login'],
        'enableAutoLogin' => true,
        'as afterLogin' => common\behaviors\LoginTimestampBehavior::class
    ],

    'mailer' => [
        'class' => 'yii\swiftmailer\Mailer',
        'viewPath' => '@frontend/mail',
        'useFileTransport' => false,
        'transport' => [
            'class' => 'Swift_SmtpTransport',
            'host' => 'smtp.gmail.com',
            'username' => 'mongdao.wd@gmail.com',
            'password' => 'qtlqfvknyclgfwbv',
            'port' => '587',
            'encryption' => 'tls',
            'streamOptions' => [
                'ssl' => [
                    'verify_peer' => false,
                    'allow_self_signed' => true
                ],
            ]
        ],
    ],
    'urlManager' => require(__DIR__ . '/_urlManager.php'),
    'cache' => require(__DIR__ . '/_cache.php'),
];