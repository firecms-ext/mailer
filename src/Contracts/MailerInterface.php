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

use FirecmsExt\Mailer\PendingMail;

/**
 * 邮寄对象
 */
interface MailerInterface
{
    /**
     * 目标.
     */
    public function to(mixed $users): PendingMail;

    /**
     * 加密抄送
     */
    public function bcc(mixed $users): PendingMail;

    /**
     * 发送邮件.
     */
    public function send(MailableInterface $mailable, \Closure|string $callback = null): mixed;
}
