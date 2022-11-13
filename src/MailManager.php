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
use FirecmsExt\Mailer\Contracts\MailerInterface;
use FirecmsExt\Mailer\Contracts\MailManagerInterface;

class MailManager implements MailManagerInterface
{
    public function __call($method, $parameters)
    {
        return $this->mailer()->{$method}(...$parameters);
    }

    public function mailer(?string $name = null, ?array $config = null): MailerInterface
    {
        return new Mailer();
    }

    public function to(mixed $address): static
    {
        return $this;
    }

    public function cc(mixed $address): static
    {
        return $this;
    }

    public function bcc(mixed $address): static
    {
        return $this;
    }

    public function send(MailableInterface $mailable): void
    {
        // 填充数据
    }
}
