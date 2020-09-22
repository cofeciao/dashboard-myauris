<?php

return [
//    'class' => common\behaviors\GlobalAccessBehavior::class,
//    'class' =>backend\components\AccessBehavior::class,
    'class' => 'common\filters\MyAccessControl',
    'rules' => [

        [
            'allow' => true,
            'roles' => ['user_develop'],
        ],
    ],
];
