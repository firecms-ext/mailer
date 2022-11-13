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
use FirecmsExt\Mailer\Contracts\MailManagerInterface;
use Hyperf\Contract\ConfigInterface;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class MailManager implements MailManagerInterface
{

    protected array $mailers = [];

    protected ConfigInterface $config;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    public function __call($method, $parameters)
    {
        return $this->mailer()->{$method}(...$parameters);
    }

    public function mailer(?string $name = null, ?array $config = null): MailerInterface
    {
        $name = $name ?: $this->getDefaultDriver();

        return new Mailer($config
            ? $this->resolve($name, array_merge($this->getConfig($name), $config))
            : $this->get($name)
        );
    }

    protected function get(string $name): PHPMailer
    {
        return $this->mailers[$name] ?? $this->resolve($name);
    }

    protected function resolve(string $name, ?array $config = null): PHPMailer
    {
        $config = $config ?: $this->getConfig($name);

        if (is_null($config)) {
            throw new \InvalidArgumentException("MailerInterface [{$name}] is not defined.");
        }

        $this->mailers[$name] = match ($name) {
            'mail' => $this->createMailTransport($config),
            'qmail' => $this->createQMailTransport($config),
            'sendmail' => $this->createSendMailTransport($config),
            default => $this->createSmtpTransport($config),
        };

        $this->mailers[$name]->setFrom($this->config->get('mailer.from.address'), $this->config->get('mailer.from.name'));

        return $this->mailers[$name];
    }

    public function getDefaultDriver(): string
    {
        return $this->config->get('mailer.driver', $this->config->get('mailer.default'));
    }

    public function setConfig(string $name, mixed $value): array
    {
        return $this->config->set("mailer.{$name}", $value);
    }

    protected function getConfig(string $name): ?array
    {
        return $this->config->get("mailer.mailers.{$name}");
    }

    protected function createSmtpTransport(array $config): PHPMailer
    {
        $mailer = new PHPMailer();

        $mailer->CharSet = $config['charset'] ?? PHPMailer::CHARSET_UTF8;
        $mailer->SMTPDebug = $config['debug'] ?? SMTP::DEBUG_SERVER;
        $mailer->isSMTP();
        $mailer->SMTPAuth = true;
        $mailer->Host = $config['host'] ?? env('MAIL_HOST');
        $mailer->Username = $config['username'] ?? env('MAIL_USERNAME');
        $mailer->Password = $config['password'] ?? env('MAIL_PASSWORD');
        $mailer->SMTPSecure = $config['encryption'] ?? PHPMailer::ENCRYPTION_SMTPS;
        $mailer->Port = $config['port'] ?? 465;
        $this->mailers['smtp'] = $mailer;

        return $mailer;
    }

    protected function createMailTransport(array $config): PHPMailer
    {
        $mailer = new PHPMailer();
        $mailer->isMail();

        $this->mailers['mail'] = $mailer;

        return $mailer;
    }

    protected function createQMailTransport(array $config): PHPMailer
    {
        $mailer = new PHPMailer();
        $mailer->isQmail();

        $this->mailers['qmail'] = $mailer;

        return $mailer;
    }

    protected function createSendMailTransport(array $config): PHPMailer
    {
        $mailer = new PHPMailer();
        $mailer->isSendmail();

        $this->mailers['sendmail'] = $mailer;

        return $mailer;
    }
}
