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

class Mailable implements MailableInterface
{
    /**
     * 来源.
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
     * 主题.
     */
    public string $subject = '';

    /**
     * 内容.
     */
    public string $body = '';


    public function subject(string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function body(string $body): static
    {
        $this->body = $body;

        return $this;
    }

    public function build(): void
    {

    }
}
