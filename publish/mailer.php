<?php

declare(strict_types=1);
/**
 * This file is part of FirecmsExt Mailer.
 *
 * @link     https://www.klmis.cn
 * @document https://www.klmis.cn
 * @contact  zhimengxingyun@klmis.cn
 * @license  https://github.com/firecms-ext/mailer/blob/master/LICENSE
 */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

return [
    'default' => env('MAIL_MAILER', 'smtp'),
    'charset' => env('MAIL_CHARSET', PHPMailer::CHARSET_UTF8),
    'debug' => env('MAIL_DEBUG', SMTP::DEBUG_OFF),

    'mailers' => [
        'smtp' => [
            'host' => env('MAIL_HOST', 'smtp.mailgun.org'),
            'port' => (int) env('MAIL_PORT', 465),
            'encryption' => strtolower(env('MAIL_ENCRYPTION', PHPMailer::ENCRYPTION_SMTPS)),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
        ],

        'mail' => [
        ],

        'sendmail' => [
        ],

        'qmail' => [
        ],
    ],

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name' => env('MAIL_FROM_NAME', 'Example'),
    ],
];
