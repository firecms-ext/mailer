<?php

namespace FirecmsExt\Mailer\Contracts;

use FirecmsExt\Mailer\SentMessage;

/**
 * 邮寄邮件
 */
interface MailableInterface
{
    /**
     * 发送邮件
     */
    public function send(MailerInterface $mailer): ?SentMessage;

    /**
     * 队列
     */
    public function queue(?string $queue = null): mixed;

    /**
     * 抄送
     */
    public function cc(object|array|string $address, ?string $name = null): static;

    /**
     * 抄送（加密）
     */
    public function bcc(object|array|string $address, ?string $name = null): static;

    /**
     * 目标
     */
    public function to(object|array|string $address, ?string $name = null): static;

    /**
     * 邮递员
     */
    public function mailer(string $mailer): static;
}