<?php
$config = [
    'id' => 'frontend',
    'basePath' => dirname(__DIR__),
    'homeUrl' => Yii::getAlias('@frontendUrl'),
    'controllerNamespace' => 'frontend\controllers',
//    'aliases' => [
//        '@webroot' => dirname(dirname(__FILE__)) . '/web',
//    ],
    'defaultRoute' => 'site/index',
    'bootstrap' => ['log'],
    'sourceLanguage' => 'en-US',
    'language' => 'vi',
    'modules' => require __DIR__ . '/modules.php',
    'components' => require __DIR__ . '/components.php',
    'as globalAccess' => require __DIR__ . '/behaviors.php',
    'params' => require __DIR__ . '/params.php',
];

if (YII_ENV_DEV) {
    $config['modules']['gii'] = [
        'class' => yii\gii\Module::class,
        'generators' => [
            'crud' => [
                'class' => yii\gii\generators\crud\Generator::class,
                'messageCategory' => 'frontend'
            ]
        ]
    ];
}

return $config;
