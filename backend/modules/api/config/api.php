<?php
/**
 * Created by PhpStorm.
 * User: Kem Bi
 * Date: 06-Jul-18
 * Time: 4:00 PM
 */
$config = [
    'modules' => [
        'v1' => [
            'class' => 'backend\modules\api\modules\v1\Module',
        ],
        'v2' => [
            'class' => 'backend\modules\api\modules\v2\Module',
        ],
        'v3' => [
            'class' => 'backend\modules\api\modules\v3\Module',
        ],
    ],
    'defaultRoute' => 'api/index',
    'components' => require __DIR__ . '/components.php',
    'params' => require __DIR__ . '/params.php',
];

return $config;
