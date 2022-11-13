<?php

namespace FirecmsExt\Mailer;

class Mailable
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
}