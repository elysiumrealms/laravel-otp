<?php

return [
    'mail' => [
        'subject' => 'Verification Code: :otp',
        'content' => 'Please use the following verification code to verify your email address. Complete the verification within :validity minutes.',
        'title' => 'Verification Code',
        'subtitle' => 'Verify Your Email Address',
        'paragraph' => 'Please use the following verification code to verify your email address. Complete the verification within :validity minutes.',
    ],
    'sms' => [
        'content' => ':name Verification Code: :otp, please complete verification within :validity minutes.',
    ],
];
