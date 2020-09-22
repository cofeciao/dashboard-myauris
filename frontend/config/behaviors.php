<?php

return [
    'class' => 'frontend\filters\MyAccessControl',
    'rules' => [
        [
            'controllers' => ['site'],
            'allow' => true,
        ],

        [
            'allow' => true,
            'roles' => ['user_develop'],
        ],
    ]
];