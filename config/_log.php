<?php
return [
    'traceLevel' => YII_DEBUG ? 3 : 0,
    'targets' => [
        'db' => [
            'class' => 'yii\log\DbTarget',
            'levels' => ['error', 'warning'],
            'except' => ['yii\web\HttpException:*', 'yii\i18n\I18N\*'],
            'prefix' => function () {
                $url = !Yii::$app->request->isConsoleRequest ? Yii::$app->request->getUrl() : null;
                return sprintf('[%s][%s]', Yii::$app->id, $url);
            },
            'logVars' => [],
            'logTable' => '{{%system_log}}'
        ],
        'email' => [
            'class' => 'yii\log\EmailTarget',
            'levels' => ['error'],
            'message' => [
                'from' => 'mail',
                'to' => 'mail',
                'subject' => 'Message',
            ],
        ],
    ],
];
