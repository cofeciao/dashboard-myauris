<?php
$config = [
    'name' => WEB_NAME,
    'vendorPath' => __DIR__ . '/../../vendor',
    'extensions' => require(__DIR__ . '/../../vendor/yiisoft/extensions.php'),
    'sourceLanguage' => 'en-US',
    'language' => 'vi',
//    'bootstrap' => ['devicedetect', 'assetsAutoCompress'],
    'bootstrap' => ['log', 'devicedetect'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'controllerMap' => [
    ],
    'timeZone' => 'Asia/Ho_Chi_Minh',
    'modules' => require __DIR__ . '/modules.php',
    'components' => require __DIR__ . '/components.php',
    'params' => require __DIR__ . '/params.php',
];

if (YII2_DEBUG && ISCLI == false) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => yii\debug\Module::class,
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.33.1', '172.17.42.1', '172.17.0.1', '192.168.99.1'],
    ];
}

if (YII2_MAIL) {
    $config['components']['mailer'] = require __DIR__ . '/_mailer.php';
    $config['components']['log'] = require __DIR__ . '/_log.php';
}

if (YII2_LOG) {
    $config['components']['log']['traceLevel'] = YII2_DEBUG ? 3 : 0;
    $config['components']['log']['targets']['db'] = [
        'class' => 'yii\log\DbTarget',
        'levels' => ['error', 'warning'],
        'except' => ['yii\web\HttpException:*', 'yii\i18n\I18N\*'],
        'prefix' => function () {
            $url = !Yii::$app->request->isConsoleRequest ? Yii::$app->request->getUrl() : null;
            return sprintf('[%s][%s]', Yii::$app->id, $url);
        },
        'logVars' => [],
        'logTable' => '{{%system_log}}',
    ];
}

if (YII2_ENV_DEV) {
    $config['components']['cache'] = [
        'class' => yii\caching\FileCache::class,
        'cachePath' => '@backend/runtime/cache'
    ];
}

if (YII2_CACHE) {
    $config['components']['redis'] = [
        'class' => 'yii\redis\Connection',
        'hostname' => 'localhost',
        'port' => 6379,
        'database' => 0,
    ];
    $config['components']['session'] = [
        'class' => 'yii\redis\Session',
        'keyPrefix' => $_SERVER['HTTP_HOST']
    ];
    $config['components']['cache'] = [
        'class' => 'yii\redis\Cache',
        'keyPrefix' => $_SERVER['HTTP_HOST']
    ];
}


return $config;
