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
namespace FirecmsExt\Mailer\Contracts;

/**
 * 邮寄邮件.
 */
interface MailableInterface
{
    public function to(object|array|string $address, ?string $name = null): static;

    public function cc(object|array|string $address, ?string $name = null): static;

    public function bcc(object|array|string $address, ?string $name = null): static;

    public function send(MailerInterface $mailer);
}
