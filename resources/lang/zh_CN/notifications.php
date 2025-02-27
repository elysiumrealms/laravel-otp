<?php

return [
    'mail' => [
        'subject' => '验证码: :otp',
        'content' => '请使用以下验证码验证您的电子邮件地址，并在 :validity 分钟内完成验证。',
        'title' => '验证码',
        'subtitle' => '验证您的电子邮件地址',
        'paragraph' => '请使用以下验证码验证您的电子邮件地址，并在 :validity 分钟内完成验证。',
    ],
    'sms' => [
        'content' => ':name 验证码: :otp, 请在 :validity 分钟内完成验证。',
    ],
];
