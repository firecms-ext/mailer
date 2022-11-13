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
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer implements MailerInterface
{
    protected PHPMailer $mailer;

    /**
     * 初始化.
     */
    public function __construct(PHPMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * mailer 其他方法.
     * @param mixed $method
     * @param mixed $parameters
     */
    public function __call($method, $parameters)
    {
        return $this->mailer->{$method}(...$parameters);
    }

    /**
     * 收件人.
     * @throws \Exception
     */
    public function to(mixed $address, string $name = ''): static
    {
        if (empty($address)) {
            return $this;
        }

        try {
            if (is_string($address)) {
                $this->mailer->addAddress($address, $name);
            } elseif (is_array($address)) {
                foreach ($address as $item) {
                    if (is_array($item)) {
                        $this->mailer->addAddress($item['address'] ?? $item['email'] ?? reset($item), $item['name'] ?? $name);
                    } elseif (is_object($item)) {
                        $this->mailer->addAddress(@$item->address ?: @$item->email, @$item->name ?: $name);
                    } elseif (is_string($item)) {
                        $this->mailer->addAddress($item, $name);
                    }
                }
            }
        } catch (Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $this;
    }

    /**
     * 抄送
     * @throws \Exception
     */
    public function cc(mixed $address, string $name = ''): static
    {
        if (empty($address)) {
            return $this;
        }

        try {
            if (is_string($address)) {
                $this->mailer->addCC($address, $name);
            } elseif (is_array($address)) {
                foreach ($address as $item) {
                    if (is_array($item)) {
                        $this->mailer->addCC($item['address'] ?? $item['email'] ?? reset($item), $item['name'] ?? $name);
                    } elseif (is_object($item)) {
                        $this->mailer->addCC(@$item->address ?: @$item->email, @$item->name ?: $name);
                    } elseif (is_string($item)) {
                        $this->mailer->addCC($item, $name);
                    }
                }
            }
        } catch (Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $this;
    }

    /**
     * 抄送（加密）.
     * @throws \Exception
     */
    public function bcc(mixed $address, string $name = ''): static
    {
        if (empty($address)) {
            return $this;
        }

        try {
            if (is_string($address)) {
                $this->mailer->addBCC($address, $name);
            } elseif (is_array($address)) {
                foreach ($address as $item) {
                    if (is_array($item)) {
                        $this->mailer->addBCC($item['address'] ?? $item['email'] ?? reset($item), $item['name'] ?? $name);
                    } elseif (is_object($item)) {
                        $this->mailer->addBCC(@$item->address ?: @$item->email, @$item->name ?: $name);
                    } elseif (is_string($item)) {
                        $this->mailer->addBCC($item, $name);
                    }
                }
            }
        } catch (Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $this;
    }

    /**
     * 回复.
     * @throws \Exception
     */
    public function replyTo(mixed $address, string $name = ''): static
    {
        if (empty($address)) {
            return $this;
        }

        try {
            if (is_string($address)) {
                $this->mailer->addReplyTo($address, $name);
            } elseif (is_array($address)) {
                foreach ($address as $item) {
                    if (is_array($item)) {
                        $this->mailer->addReplyTo($item['address'] ?? $item['email'] ?? reset($item), $item['name'] ?? $name);
                    } elseif (is_object($item)) {
                        $this->mailer->addReplyTo(@$item->address ?: @$item->email, @$item->name ?: $name);
                    } elseif (is_string($item)) {
                        $this->mailer->addReplyTo($item, $name);
                    }
                }
            }
        } catch (Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $this;
    }

    /**
     * @throws \Exception
     */
    public function from(mixed $address, string $name = ''): static
    {
        if (empty($address)) {
            return $this;
        }

        try {
            if (is_string($address)) {
                $this->mailer->setFrom($address, $name);
            } elseif (is_array($address)) {
                $this->mailer->setFrom($address['address'] ?? $address['email'] ?? reset($address), $address['name'] ?? $name);
            }
        } catch (Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $this;
    }

    /**
     * 主题.
     */
    public function subject(string $subject): static
    {
        $this->mailer->Subject = $subject;

        return $this;
    }

    /**
     * 内容.
     */
    public function body(string $body): static
    {
        $this->mailer->Body = $body;

        return $this;
    }

    /**
     * @throws \Exception
     */
    public function send(MailableInterface $mailable): void
    {
        try {
            go(function () use ($mailable) {
                $this->fill($mailable);
                $this->mailer->send();
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 填充.
     * @throws \Exception
     */
    protected function fill(MailableInterface $mailable): static
    {
        try {
            // 绑定数据
            if (method_exists($mailable, 'build')) {
                $mailable->build();
            }
            // 填充数据
            return $this->to($mailable->to)
                ->cc($mailable->cc)
                ->bcc($mailable->bcc)
                ->replyTo($mailable->replyTo)
                ->from($mailable->from)
                ->subject($mailable->subject)
                ->body($mailable->body);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
