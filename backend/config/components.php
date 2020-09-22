<?php
/**
 * Created by PhpStorm.
 * User: mongd
 * Date: 29-Jul-18
 * Time: 9:54 PM
 */

return [
    'geoip' => ['class' => 'lysenkobv\GeoIP\GeoIP'],
#    'elasticsearch' => [
#        'class' => 'yii\elasticsearch\Connection',
#        'nodes' => [
#            [
#                'http_address' => 'inet[/127.0.0.1:9200]',
#                'auth' => ['username' => 'kembi', 'password' => '120819860303+kb'],
#            ],
#        ],
#    ],
    'errorHandler' => [
        'errorAction' => 'site/error',
    ],
    'authManager' => [
        'class' => 'yii\rbac\DbManager',
        'defaultRoles' => ['user_users'],
    ],
    'request' => [
        'enableCookieValidation' => true,
//        'parsers' => [
//            'application/json' => 'yii\web\JsonParser',
//        ],
        'cookieValidationKey' => BACKEND_COOKIE_VALIDATION_KEY,
        'baseUrl' => ''
    ],
    'user' => [
        'class' => yii\web\User::class,
        'identityClass' => common\models\User::class,
        'loginUrl' => ['auth/login'],
        'enableAutoLogin' => true,
        'as afterLogin' => common\behaviors\LoginTimestampBehavior::class
    ],
    'assetsAutoCompress' =>
        [
            'class' => '\skeeks\yii2\assetsAuto\AssetsAutoCompressComponent',
            'enabled' => true,

            'readFileTimeout' => 3,           //Time in seconds for reading each asset file

            'jsCompress' => true,        //Enable minification js in html code
            'jsCompressFlaggedComments' => true,        //Cut comments during processing js

            'cssCompress' => true,        //Enable minification css in html code

            'cssFileCompile' => true,        //Turning association css files
            'cssFileRemouteCompile' => false,       //Trying to get css files to which the specified path as the remote file, skchat him to her.
            'cssFileCompress' => true,        //Enable compression and processing before being stored in the css file
            'cssFileBottom' => false,       //Moving down the page css files
            'cssFileBottomLoadOnJs' => false,       //Transfer css file down the page and uploading them using js

            'jsFileCompile' => true,        //Turning association js files
            'jsFileRemouteCompile' => false,       //Trying to get a js files to which the specified path as the remote file, skchat him to her.
            'jsFileCompress' => true,        //Enable compression and processing js before saving a file
            'jsFileCompressFlaggedComments' => true,        //Cut comments during processing js

            'noIncludeJsFilesOnPjax' => true,        //Do not connect the js files when all pjax requests

            'htmlFormatter' => [
                //Enable compression html
                'class' => 'skeeks\yii2\assetsAuto\formatters\html\TylerHtmlCompressor',
                'extra' => true,       //use more compact algorithm
                'noComments' => true,        //cut all the html comments
                'maxNumberRows' => 50000,       //The maximum number of rows that the formatter runs on

                //or

//                'class' => 'skeeks\yii2\assetsAuto\formatters\html\MrclayHtmlCompressor',

                //or any other your handler implements skeeks\yii2\assetsAuto\IFormatter interface

                //or false
            ],
        ],
    'assetManager' => [
        'appendTimestamp' => true,
        'bundles' => [
            'yii\web\JqueryAsset' => [
                'js' => [
                    '/vendors/js/vendors.min.js',
                ],
                'jsOptions' => ['position' => \yii\web\View::POS_HEAD],
            ],
            'yii\bootstrap\BootstrapPluginAsset' => [
                'js' => []
            ],
            'yii\bootstrap\BootstrapAsset' => ['css' => ['/css/vendors.css']],
//            'kartik\form\ActiveFormAsset' => [
//                'bsDependencyEnabled' => false // do not load bootstrap assets for a specific asset bundle
//            ],
        ],
    ],
    'urlManager' => require __DIR__ . '/_urlManager.php',
    'urlManagerFrontend' => [
        'class' => yii\web\UrlManager::class,
        'enablePrettyUrl' => true,
        'showScriptName' => false,
        'suffix' => '.html',
    ],
    'ga' => [
        'class' => 'baibaratsky\yii\google\analytics\MeasurementProtocol',
        'trackingId' => 'UA-123142073-1', // Put your real tracking ID here\

        // These parameters are optional:
        'useSsl' => true, // If you’d like to use a secure connection to Google servers
        'overrideIp' => false, // By default, IP is overridden by the user’s one, but you can disable this
        'anonymizeIp' => true, // If you want to anonymize the sender’s IP address
        'asyncMode' => true, // Enables the asynchronous mode (see below)
    ],
    'formatter' => [
        'class' => 'yii\i18n\Formatter',
        'nullDisplay' => '-',
        'dateFormat' => 'php:d-m-Y',
        'datetimeFormat' => 'php:d-m-Y H:i',
        'timeFormat' => 'php:H:i:s',
        'locale' => 'vi_VN',
        'decimalSeparator' => ',',
        'thousandSeparator' => ' ',
//        'currencyCode' => '₫',
    ],
    //https://www.yiiframework.com/extension/bot-telegram#installation
//    'telegram' => [
//        'class' => 'aki\telegram\Telegram',
//        'botToken' => '1180158304:AAHJz4xcvSfDQEkEtYMrVm-WoAGQASinAPM',
//        'botUsername' => 'Myauris_bot'
//    ]
];
