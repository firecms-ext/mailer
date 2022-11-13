<?php

namespace FirecmsExt\Mailer;

use FirecmsExt\Mailer\Contracts\MailManagerInterface;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\ContainerInterface;

class MailManager implements MailManagerInterface
{
    protected ContainerInterface $container;

    protected ConfigInterface $config;

    public function __construct(ContainerInterface $container, ConfigInterface $config)
    {
        $this->container = $container;
        $this->config = $config;
    }

    public function __call($method, $parameters)
    {
        return $this->mailer()->{$method}(...$parameters);
    }


    public function mailer(?string $name = null): Mailer
    {
        $name = $name ?: $this->getDefaultDriver();

        return $this->mailers[$name] = $this->get($name);
    }

    public function getDefaultDriver(): string
    {
        return $this->config->get('mailer.driver', $this->config->get('mailer.default'));
    }

    protected function get(string $name): Mailer
    {
        return $this->mailers[$name] ?? $this->resolve($name);
    }

    protected function resolve(string $name): Mailer
    {
        $config = $this->getConfig($name);

        if (is_null($config)) {
            throw new \InvalidArgumentException("MailerInterface [{$name}] is not defined.");
        }

        // 一旦我们创建了邮件实例，我们将设置一个容器实例
        // 发送邮件。这允许我们通过容器解析 mailer 类
        // 最大可测试性的类，而不是传递闭包。
        $mailer = new Mailer(
            $name,
            $this->createSymfonyTransport($config),
            $this->container->get(EventDispatcherInterface::class)
        );

        // 接下来我们将设置这个邮件上的所有全局地址，这允许
        // 方便统一所有"from"地址以及方便调试
        // 发送的消息，因为这些将发送到一个单一的电子邮件地址。

        foreach (['from', 'reply_to', 'to', 'return_path'] as $type) {
            $this->setGlobalAddress($mailer, $config, $type);
        }

        return $mailer;
    }
}