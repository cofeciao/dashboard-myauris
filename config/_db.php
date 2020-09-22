<?php
if (CONSOLE_HOST == 1) {
    return [
        'class' => yii\db\Connection::class,
        'dsn' => 'mysql:host=localhost;dbname=dbname',
        'username' => 'user',
        'password' => 'pass',
        'tablePrefix' => '',
        'charset' => 'utf8mb4',
        'enableSchemaCache' => YII_ENV_PROD,
    ];
} elseif (CONSOLE_HOST == 2) {
    return [
        'class' => yii\db\Connection::class,
        'dsn' => 'mysql:host=localhost;dbname=dbname',
        'username' => 'user',
        'password' => 'pass',
        'tablePrefix' => '',
        'charset' => 'utf8mb4',
        'enableSchemaCache' => YII_ENV_PROD,
    ];
} else {
    return [
        'class' => yii\db\Connection::class,
        'dsn' => 'mysql:host=localhost;dbname=dbname',
        'username' => 'user',
        'password' => 'pass',
        'tablePrefix' => '',
        'charset' => 'utf8mb4',
        'enableSchemaCache' => YII_ENV_PROD,
    ];
}
