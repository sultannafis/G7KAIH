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
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
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

    /*
    |--------------------------------------------------------------------------
    | Aladhan API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Aladhan Prayer Times API
    | Documentation: https://aladhan.com/prayer-times-api
    |
    */

    'aladhan' => [
        'base_url' => env('ALADHAN_BASE_URL', 'https://api.aladhan.com/v1'),

        /*
         | Calculation Method
         | 0 - Shia Ithna-Ashari
         | 1 - University of Islamic Sciences, Karachi
         | 2 - Islamic Society of North America (ISNA)
         | 3 - Muslim World League (MWL)
         | 4 - Umm al-Qura, Makkah
         | 5 - Egyptian General Authority of Survey
         | 7 - Institute of Geophysics, University of Tehran
         | 8 - Gulf Region
         | 9 - Kuwait
         | 10 - Qatar
         | 11 - Majlis Ugama Islam Singapura, Singapore
         | 12 - Union Organization islamic de France
         | 13 - Diyanet İşleri Başkanlığı, Turkey
         | 14 - Spiritual Administration of Muslims of Russia
         | 15 - Moonsighting Committee Worldwide (Moonsighting.com)
         | 16 - Dubai (unofficial)
         | 17 - Tunisia
         | 18 - Algeria
         | 19 - KEMENAG - Kementerian Agama Republik Indonesia
         | 20 - Morocco
         | 21 - Comunidade Islamica de Lisboa
         | 23 - Ministry of Awqaf, Islamic Affairs and Holy Places, Jordan
         */
        'method' => env('ALADHAN_METHOD', 19), // Default: Kemenag RI

        /*
         | School of Jurisprudence
         | 0 - Shafi (or the standard way)
         | 1 - Hanafi
         */
        'school' => env('ALADHAN_SCHOOL', 0),

        'timezone' => env('ALADHAN_TIMEZONE', 'Asia/Jakarta'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Green API (WhatsApp) Configuration
    |--------------------------------------------------------------------------
    */

    'greenapi' => [
        'instance_id' => env('GREEN_API_INSTANCE_ID'),
        'token' => env('GREEN_API_TOKEN'),
        'api_url' => env('GREEN_API_API_URL', 'https://api.green-api.com'),
        'media_url' => env('GREEN_API_MEDIA_URL'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration
    |--------------------------------------------------------------------------
    */

    'gemini' => [
        'key' => env('GEMINI_API_KEY'),
    ],

    'groq' => [
        'key' => env('GROQ_API_KEY'),
    ],

    'cloudinary' => [
        'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
        'api_key' => env('CLOUDINARY_API_KEY'),
        'api_secret' => env('CLOUDINARY_API_SECRET'),
        'url' => env('CLOUDINARY_URL'),
    ],

];
