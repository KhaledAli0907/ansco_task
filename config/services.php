<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'paymob' => [
        'api_key' => env('PAYMOB_API_KEY', "ZXlKaGJHY2lPaUpJVXpVeE1pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SmpiR0Z6Y3lJNklrMWxjbU5vWVc1MElpd2ljSEp2Wm1sc1pWOXdheUk2T1RZME56RTRMQ0p1WVcxbElqb2lhVzVwZEdsaGJDSjkuNDVmWnZKd0pmbjhhNy0zckZFZTBxREJnanVUTTMxUDVtdHJLVGgwUkJpeW1aVFBjVzFFSTlnLVJNelZsUGJvckJGeU53dk5WZlZlZUlrdU85d0JBSGc="),
        'base_url' => env('PAYMOB_BASE_URL', 'https://accept.paymob.com/'),
    ],

];
