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

namespace FirecmsExt\Mailer;

use FirecmsExt\Mailer\Contracts\MailableInterface;
use FirecmsExt\Mailer\Contracts\MailManagerInterface;
use Hyperf\Utils\ApplicationContext;

/**
 * @method static PendingMail to(mixed $users)
 * @method static PendingMail cc(mixed $users)
 * @method static PendingMail bcc(mixed $users)
 * @method static bool|int send(MailableInterface $mailable)
 */
abstract class Mail
{
    public static function __callStatic($method, $args)
    {
        $instance = static::getManager();

        return $instance->{$method}(...$args);
    }

    public static function mailer(string $name): PendingMail
    {
        return new PendingMail(static::getManager()->get($name));
    }

    protected static function getManager(): ?MailManagerInterface
    {
        return ApplicationContext::getContainer()
            ->get(MailManagerInterface::class);
    }
}
