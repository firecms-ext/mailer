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

use FirecmsExt\Mailer\Contracts\MailerInterface;
use FirecmsExt\Mailer\Contracts\MailManagerInterface;
use Hyperf\Contract\ConfigInterface;
use PHPMailer\PHPMailer\Exception;
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

        return new Mailer(
            $config
            ? $this->resolve($name, array_merge($this->getConfig($name), $config))
            : $this->get($name)
        );
    }

    public function getDefaultDriver(): string
    {
        return $this->config->get('mailer.driver', $this->config->get('mailer.default'));
    }

    protected function get(string $name): PHPMailer
    {
        return $this->mailers[$name] ?? $this->resolve($name);
    }

    /**
     * @throws \Exception
     */
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

        // 全局设置
        try {
            $this->mailers[$name]->setFrom(
                $this->config->get('mailer.from.address'),
                $this->config->get('mailer.from.name')
            );
            $this->mailers[$name]->CharSet = $this->config->get('mailer.charset', PHPMailer::CHARSET_UTF8);
            $this->mailers[$name]->SMTPDebug = $this->config->get('mailer.debug', SMTP::DEBUG_SERVER);
        } catch (Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $this->mailers[$name];
    }

    protected function getConfig(string $name): ?array
    {
        return $this->config->get("mailer.mailers.{$name}");
    }

    protected function createSmtpTransport(array $config): PHPMailer
    {
        $mailer = new PHPMailer();

        $mailer->isSMTP();
        $mailer->SMTPAuth = true;
        $mailer->Host = $config['host'];
        $mailer->Username = $config['username'];
        $mailer->Password = $config['password'];
        $mailer->SMTPSecure = $config['encryption'];
        $mailer->Port = $config['port'];

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
