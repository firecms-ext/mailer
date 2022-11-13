<?php

namespace FirecmsExt\Mailer\Transport;

use FirecmsExt\Mailer\Contracts\TransportInterface;
use Psr\Log\LoggerInterface;

class LogTransport implements TransportInterface
{
    protected LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __toString(): string
    {
        return 'log';
    }

    public function send(RawMessage $message, Envelope $envelope = null): ?SentMessage
    {
        $this->logger->debug($message->toString());

        return new SentMessage($message, $envelope ?? Envelope::create($message));
    }

    public function logger(): LoggerInterface
    {
        return $this->logger;
    }
}