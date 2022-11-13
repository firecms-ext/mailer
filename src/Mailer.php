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
use Hyperf\Contract\ConfigInterface;
use Hyperf\Utils\ApplicationContext;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Mailer implements MailerInterface
{
    protected PHPMailer $mailer;

    /**
     * 初始化
     * @param PHPMailer $mailer
     */
    public function __construct(PHPMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * mailer 其他方法
     */
    public function __call($method, $parameters)
    {
        return $this->mailer->{$method}(...$parameters);
    }

    /**
     * 目标
     * @throws \Exception
     */
    public function to(mixed $address): static
    {
        if (empty($address)) {
            return $this;
        }

        try {
            if (is_string($address)) {
                $this->mailer->addAddress($address);
            } elseif (is_array($address)) {
                foreach ($address as $item) {
                    if (is_array($item)) {
                        $this->mailer->addAddress($item['address'] ?? $item['email'], $item['name'] ?? '');
                    } elseif (is_object($item)) {
                        $this->mailer->addAddress(@$item->address ?: @$item->email, @$item->name ?: '');
                    } elseif (is_string($item)) {
                        $this->mailer->addAddress($item);
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
    public function cc(mixed $address): static
    {
        if (empty($address)) {
            return $this;
        }

        try {
            if (is_string($address)) {
                $this->mailer->addCC($address);
            } elseif (is_array($address)) {
                foreach ($address as $item) {
                    if (is_array($item)) {
                        $this->mailer->addCC($item['address'] ?? $item['email'], $item['name'] ?? '');
                    } elseif (is_object($item)) {
                        $this->mailer->addCC(@$item->address ?: @$item->email, @$item->name ?: '');
                    } elseif (is_string($item)) {
                        $this->mailer->addCC($item);
                    }
                }
            }
        } catch (Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $this;
    }

    /**
     * 抄送（加密）
     * @throws \Exception
     */
    public function bcc(mixed $address): static
    {
        if (empty($address)) {
            return $this;
        }

        try {
            if (is_string($address)) {
                $this->mailer->addBCC($address);
            } elseif (is_array($address)) {
                foreach ($address as $item) {
                    if (is_array($item)) {
                        $this->mailer->addBCC($item['address'] ?? $item['email'], $item['name'] ?? '');
                    } elseif (is_object($item)) {
                        $this->mailer->addBCC(@$item->address ?: @$item->email, @$item->name ?: '');
                    } elseif (is_string($item)) {
                        $this->mailer->addBCC($item);
                    }
                }
            }
        } catch (Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $this;
    }

    /**
     * 回复
     * @throws \Exception
     */
    public function replyTo(mixed $address): static
    {
        if (empty($address)) {
            return $this;
        }

        try {
            if (is_string($address)) {
                $this->mailer->addReplyTo($address);
            } elseif (is_array($address)) {
                foreach ($address as $item) {
                    if (is_array($item)) {
                        $this->mailer->addReplyTo($item['address'] ?? $item['email'], $item['name'] ?? '');
                    } elseif (is_object($item)) {
                        $this->mailer->addReplyTo(@$item->address ?: @$item->email, @$item->name ?: '');
                    } elseif (is_string($item)) {
                        $this->mailer->addReplyTo($item);
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
    public function setFrom(mixed $address): static
    {
        if (empty($address)) {
            return $this;
        }

        try {
            if (is_string($address)) {
                $this->mailer->setFrom($address);
            } elseif (is_array($address)) {
                foreach ($address as $item) {
                    if (is_array($item)) {
                        $this->mailer->setFrom($item['address'] ?? $item['email'], $item['name'] ?? '');
                    } elseif (is_object($item)) {
                        $this->mailer->setFrom(@$item->address ?: @$item->email, @$item->name ?: '');
                    } elseif (is_string($item)) {
                        $this->mailer->setFrom($item);
                    }
                }
            }
        } catch (Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $this;
    }

    /**
     * 主题
     */
    public function subject(string $subject): static
    {
        $this->mailer->Subject = $subject;

        return $this;
    }

    /**
     * 内容
     */
    public function body(string $body): static
    {
        $this->mailer->Body = $body;

        return $this;
    }

    /**
     * 填充
     * @throws \Exception
     */
    protected function fill(MailableInterface $mailable): static
    {
        try {
            // 绑定数据
            $mailable->build();
            //填充数据
            return $this->to($mailable->to)
                ->cc($mailable->cc)
                ->bcc($mailable->bcc)
                ->replyTo($mailable->replyTo)
                ->setFrom($mailable->from)
                ->subject($mailable->subject)
                ->body($mailable->body);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
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

}
