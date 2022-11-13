<?php

namespace FirecmsExt\Mailer\Contracts;

use FirecmsExt\Mailer\PendingMail;
use FirecmsExt\Mailer\SentMessage;

/**
 * 邮寄对象
 */
interface MailerInterface
{
    /**
     * 目标
     */
    public function to(mixed $users): PendingMail;

    /**
     * 加密抄送
     */
    public function bcc(mixed $users): PendingMail;

    /**
     * 发送原文
     */
    public function raw(string $text, mixed $callback): ?SentMessage;

    /**
     * 发送邮件
     */
    public function send(MailableInterface $mailable, \Closure|string $callback = null): mixed;
}