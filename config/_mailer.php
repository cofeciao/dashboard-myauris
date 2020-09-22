<?php
return [
    'class' => 'yii\swiftmailer\Mailer',
    'viewPath' => '@backend/mail',
    'useFileTransport' => false,
    'transport' => [
        'class' => 'Swift_SmtpTransport',
        'host' => 'smtp.gmail.com',
        'username' => 'gmail',
        'password' => 'pass',
        'port' => '587',
        'encryption' => 'tls',
        'streamOptions' => [
            'ssl' => [
                'verify_peer' => false,
                'allow_self_signed' => true
            ],
        ]
    ],
];
