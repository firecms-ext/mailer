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
use Hyperf\Utils\Traits\Conditionable;
use PHPMailer\PHPMailer\PHPMailer;

class PendingMail
{
    use Conditionable;

    /**
     * 邮寄者的实例。
     */
    protected PHPMailer $mailer;

    /**
     * 邮件的“收件人”。
     */
    protected mixed $to = [];

    /**
     * 邮件的“抄送人”。
     */
    protected mixed $cc = [];

    /**
     *  邮件的“秘密抄送人”。
     */
    protected mixed $bcc = [];

    /**
     * 创建一个新的可发送邮件实例。
     */
    public function __construct(PHPMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * 设置邮件的收件人。
     *
     * @return $this
     */
    public function to(mixed $users): static
    {
        $this->to = $users;

        return $this;
    }

    /**
     * 设置邮件的抄送人。
     *
     * @return $this
     */
    public function cc(mixed $users): static
    {
        $this->cc = $users;

        return $this;
    }

    /**
     * 设置邮件的秘密抄送人。
     *
     * @return $this
     */
    public function bcc(mixed $users): static
    {
        $this->bcc = $users;

        return $this;
    }

    /**
     * 发送一个新的邮件消息实例。
     */
    public function send(MailableInterface $mailable): mixed
    {
        $this->fill($mailable);

        return $this->mailer->send();
    }

    /**
     * 在可邮寄邮件中填写地址。
     */
    protected function fill(MailableInterface $mailable)
    {
        foreach ($this->to as $item) {
            if (is_array($item)) {
                $this->mailer->addAddress($item['address'], $item['name'] ?? '');
            } elseif (is_string($item) && $item) {
                $this->mailer->addAddress($item);
            }
        }
        foreach ($this->cc as $item) {
            if (is_array($item)) {
                $this->mailer->addCC($item['address'], $item['name'] ?? '');
            } elseif (is_string($item) && $item) {
                $this->mailer->addCC($item);
            }
        }

        foreach ($this->bcc as $item) {
            if (is_array($item)) {
                $this->mailer->addBCC($item['address'], $item['name'] ?? '');
            } elseif (is_string($item) && $item) {
                $this->mailer->addBCC($item);
            }
        }

        return $this;
    }
}
