<?php

namespace FirecmsExt\Mailer\Contracts;

use FirecmsExt\Mailer\SentMessage;

/**
 * 邮寄邮件
 */
interface MailableInterface
{
    public function to(object|array|string $address, ?string $name = null): static;

    public function cc(object|array|string $address, ?string $name = null): static;

    public function bcc(object|array|string $address, ?string $name = null): static;

    public function send(MailerInterface $mailer);

}