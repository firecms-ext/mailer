<?php

namespace FirecmsExt\Mailer;

use FirecmsExt\Mailer\Contracts\MailableInterface;
use Hyperf\Utils\ApplicationContext;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * @method static PendingMail to(mixed $users)
 * @method static PendingMail cc(mixed $users)
 * @method static PendingMail bcc(mixed $users)
 * @method static null|int send(MailableInterface $mailable)
 */
abstract class Mail
{
    public static function __callStatic($method, $args)
    {
        $instance = static::getManager();

        return $instance->{$method}(...$args);
    }

    protected static function getManager(): ?PHPMailer
    {
        return ApplicationContext::getContainer()
            ->get(PHPMailer::class);
    }
}