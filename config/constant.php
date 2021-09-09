<?php
return [
    'app' => [
        'date_format'=> 'Y-m-d H:i:s',
        'date_format_without_time'=> 'Y-m-d',
    ],    
    'login_type'=>[
        'NATIVE_LOGIN'=>1,
        'TWITTER_LOGIN'=>2,
        'GMAIL_LOGIN'=>3,
    ],
    'header_code'=>[
        'ok'=>200,
        'unauthorize'=>401,
        'login_fail'=>401,
        'validaion_fail'=>422,
        'mail_fail'=>535,
        'exception'=>500,
        'notFound'=>200,
        'forbidden'=>403,
        'conflict'=>409,
        'transaction'=>560,
        'no_content'=>404,
        'HTTP_BAD_REQUEST'=>400,
        'no_content_found'=>204,
    ],
    'pagination' => [
        'per_page' => 10,
        'admin_per_page' => 10,
    ],
    'ADMIN_URL'=>'admin',
    'ACTIVE_STATUS'=>1,
    'DEACTIVATE_STATUS'=>0,
    'DELETED_STATUS'=>2,
    'privacy' => [
        'public' => ['key'=> 1, 'value'=> 'Public'],
        'private' => ['key'=> 2, 'value'=> 'Private'],
        'followers' => ['key'=> 3, 'value'=> 'Followers'],
    ],
    'comments' => [
        'allow' => ['key'=> 0, 'value'=> 'Allow comments on this post'],
        'notAllow' => ['key'=> 1, 'value'=> 'Disable comments on this post'],
    ],
    'license' => [
        'mammalzStandard' => ['key'=> 1, 'value'=> 'Mammalz Standard License'],
        'creativeCommons' => ['key'=> 2, 'value'=> 'Creative Commons License']
    ],
    'param' => [
        'following' => 'followings',
        'followers' => 'followers'
    ],
    'adult' => [
        'yes' => 1,
        'not' => 0
    ],
    'interest' => [
        'min' => 1,
        'max' => 5
    ],
    'paths'=>[
        'AVATARS' => 'galleries',
        'THUMBS'=> 'thumbnails',
        'VIDEOS' => 'videos',
    ],
    'minimumAge'=> 13,
    'adultAge'=> 18,
    'maxAge'=> 100,
    'minName'=> 1,
    'maxName'=> 30,
    'minUsername'=> 3,
    'maxUsername'=> 28,
    'minPhoneLength'=> 10,
    'maxPhoneLength'=> 16,
    'CONTENT_NOT_FOUND'=>'Content not found',
    'web_url' => [
        'basic_url' => env('WEB_URL'),
        'verify_success_url' => env('WEB_URL') . 'users/verification/success',
        'verify_exist_verified_url' => env('WEB_URL') . 'users/verification/alreadyVerified',
        'password_reset_url' => env('WEB_URL') . 'reset-password?token=',
    ],
    'admin_web_url' => [
        'basic_url' => env('ADMIN_WEB_URL'),
        'verify_success_url' => env('ADMIN_WEB_URL') . 'users/verification/success',
        'verify_exist_verified_url' => env('ADMIN_WEB_URL') . 'users/verification/alreadyVerified',
        'password_reset_url' => env('ADMIN_WEB_URL') . 'reset-password?token=',
    ],    
    'email_web_url' => [
        'basic_url' => env('WEB_URL'),
        'verify_url' => env('WEB_URL') . 'email-verification/verify',
        'verified_url' => env('WEB_URL') . 'email-verification/verified',
    ],
    
    'months' => [1,2,3,4,5,6,7,8,9,10,11,12],
    'userrole' => [
        'customer'=>0,
        'model' =>1,
        'admin' =>2,
    ],
    'ADMIN_MAIL' => env('ADMIN_MAIL', 'admin@desisexichat.com'),
    'description_max_length' =>1000,
    'Payment' => [
        'ApiKey' => 'sk_test_51HNjZOIX1zbhIrFL6muVKNBBJfvchF4GohriXZT4QavJegqyVZIiWtgY9ax63S5HPdbAagli8dPJF1iavYalzZiK00jadLtMAR',
        'publishableKey' => 'pk_test_51HNjZOIX1zbhIrFLSzy1OUP4WBdWJ3LQCUQJR4NbV5RM4P60lDsgmieKOrSPqPxTIPU9aYNCFMwnwOPz2jvvvqSb00QH4NMMe4',
        'Currency' => 'USD'
    ]
];