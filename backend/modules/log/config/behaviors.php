<?php

return [
//    'class' => common\behaviors\GlobalAccessBehavior::class,
//    'class' =>backend\components\AccessBehavior::class,
    'class' => 'common\filters\MyAccessControl',
    'rules' => [
        [
            'controllers' => ['customer-online'],
            'allow' => true,
            'actions' => ['get-district'],
        ],

        [
            'allow' => true,
            'roles' => ['user_develop'],
        ],
    ],
];
