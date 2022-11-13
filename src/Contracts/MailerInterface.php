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
 * 邮寄驱动.
 */
interface MailerInterface
{
    public function to(mixed $address): static;

    public function send(MailableInterface $mailable): void;
}
