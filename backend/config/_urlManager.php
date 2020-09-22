<?php
return [
    'class' => yii\web\UrlManager::class,
    'enablePrettyUrl' => true,
//    'enableStrictParsing' => true,
    'showScriptName' => false,
    'rules' => [
        ['pattern' => 'cam-on-khach-hang', 'route' => 'events/review/thank-you', 'suffix' => '.html'],
        ['pattern' => 'play-video', 'route' => '/events/review/video', 'suffix' => '.html'],
        ['pattern' => 'danh-gia', 'route' => 'events/review/danh-gia', 'suffix' => '.html'],
        ['pattern' => 'khach-danh-gia', 'route' => 'events/review/danh-gia-type', 'suffix' => '.html'],
    ],
];
