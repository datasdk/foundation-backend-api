<?php

return [
    'default' => env('MAIL_MAILER', 'smtp'),

    'mailers' => [
        'smtp' => [
            'active' => env('MAIL_ACTIVE',true),
            'transport' => 'smtp',
            'host' => env('MAIL_HOST'),
            'port' => env('MAIL_PORT', 587),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'stream' => [
                'ssl' => [
                    'allow_self_signed' => true,
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ],
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path' => '/usr/sbin/sendmail -bs',
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],
    ],

    /*
    |----------------------------------------------------------------------
    | Global "From" Address
    |----------------------------------------------------------------------
    |
    | You may wish for all emails sent by your application to be sent from
    | the same address. Here you may specify a name and address that is
    | used globally for all emails that are sent by your application.
    |
    */

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', ''),
        'name' => env('MAIL_FROM_NAME', ''),
        'reply_address' => env('MAIL_FROM_ADDRESS', '')        
    ],

    'markdown' => [
        'theme' => 'default',
        'paths' => [
            resource_path('views/vendor/mail'),
            resource_path('views/emails'), // Tilføj denne, hvis dine mails ligger her
        ],
    ],



    /*
    |----------------------------------------------------------------------
    | ANDET
    |----------------------------------------------------------------------
    |
    | Here you can add more settings like mailable classes, email templates,
    | and default placeholders.
    |
    */

    'mailable' => \App\Mail\Standard::class,
    
   
    'default_placeholders' => [
        // Add any default placeholders you might want to use in your emails
    ],
];
