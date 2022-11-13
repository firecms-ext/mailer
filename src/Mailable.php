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

class Mailable implements MailableInterface
{
    /**
     * 寄件地址
     */
    public array $from = [];

    /**
     * 收件人.
     */
    public array $to = [];

    /**
     * 抄送人.
     */
    public array $cc = [];

    /**
     * 秘密抄送人.
     */
    public array $bcc = [];

    /**
     * 回复收件人.
     */
    public array $replyTo = [];

    /**
     * 邮件主题.
     */
    public string $subject = '';

    /**
     * 邮件内容.
     */
    public string $body = '';

    public function to(object|array|string $address, ?string $name = null): static
    {
        return $this;
    }

    public function cc(object|array|string $address, ?string $name = null): static
    {
        return $this;
    }

    public function bcc(object|array|string $address, ?string $name = null): static
    {
        return $this;
    }

    public function send(MailerInterface $mailer)
    {
        // TODO: Implement send() method.
    }
}
