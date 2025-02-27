<?php

return [
    'mail' => [
        'subject' => '驗證碼: :otp',
        'content' => '請使用以下驗證碼驗證您的電子郵件地址，並於 :validity 分鐘內完成驗證。',
        'title' => '驗證碼',
        'subtitle' => '驗證您的電子郵件地址',
        'paragraph' => '請使用以下驗證碼驗證您的電子郵件地址，並於 :validity 分鐘內完成驗證。',
    ],
    'sms' => [
        'content' => ':name 驗證碼: :otp, 請於 :validity 分鐘內完成驗證。',
    ],
];
