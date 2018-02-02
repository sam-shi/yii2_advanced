<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    /************邮箱******************/
    'mailer' =>
        [
            'class'         => 'yii\swiftmailer\Mailer',
            'transport'     => [
                'class'      => 'Swift_SmtpTransport',
                'host'       => 'smtp.163.com',
                //'username'   => 'test@zhux2.com',   //测试号  上线后请修改
                //'password'   => 'Zhuxun2017',
                'username'   => '********@163.com',   //测试号  上线后请修改
                'password'   => '******',//邮箱上设置的授权码
                'port'       => '25',
                'encryption' => 'tls',
            ],
            'messageConfig' => [
                'charset' => 'UTF-8',
                'from'    => ['********@163.com' => '测试邮箱发送']
            ],
        ],
];
