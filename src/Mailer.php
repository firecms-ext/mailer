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
    protected PHPMailer $drive;

    public function __construct(PHPMailer $drive)
    {
        $this->drive = $drive;
    }

    /**
     * @throws \Exception
     */
    public function to(mixed $address): static
    {
        if (empty($address)) {
            return $this;
        }

        try {
            if (is_string($address)) {
                $this->drive->addAddress($address);
            } elseif (is_array($address)) {
                foreach ($address as $item) {
                    if (is_array($item)) {
                        $this->drive->addAddress($item['address'] ?? $item['email'], $item['name'] ?? '');
                    } elseif (is_object($item)) {
                        $this->drive->addAddress(@$item->address ?: @$item->email, @$item->name ?: '');
                    } elseif (is_string($item)) {
                        $this->drive->addAddress($item);
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
    public function cc(mixed $address): static
    {
        if (empty($address)) {
            return $this;
        }

        try {
            if (is_string($address)) {
                $this->drive->addCC($address);
            } elseif (is_array($address)) {
                foreach ($address as $item) {
                    if (is_array($item)) {
                        $this->drive->addCC($item['address'] ?? $item['email'], $item['name'] ?? '');
                    } elseif (is_object($item)) {
                        $this->drive->addCC(@$item->address ?: @$item->email, @$item->name ?: '');
                    } elseif (is_string($item)) {
                        $this->drive->addCC($item);
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
    public function bcc(mixed $address): static
    {
        if (empty($address)) {
            return $this;
        }

        try {
            if (is_string($address)) {
                $this->drive->addBCC($address);
            } elseif (is_array($address)) {
                foreach ($address as $item) {
                    if (is_array($item)) {
                        $this->drive->addBCC($item['address'] ?? $item['email'], $item['name'] ?? '');
                    } elseif (is_object($item)) {
                        $this->drive->addBCC(@$item->address ?: @$item->email, @$item->name ?: '');
                    } elseif (is_string($item)) {
                        $this->drive->addBCC($item);
                    }
                }
            }
        } catch (Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $this;
    }

    protected function subject(string $subject): static
    {
        $this->drive->Subject = $subject;

        return $this;
    }

    protected function body(string $body): static
    {
        $this->drive->Body = $body;

        return $this;
    }

    /**
     * @throws \Exception
     */
    protected function fill(MailableInterface $mailable): static
    {
        try {
            $mailable->build();

            return $this->to($mailable->to)
                ->cc($mailable->cc)
                ->bcc($mailable->bcc)
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
                $this->drive->send();
            });

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

}
