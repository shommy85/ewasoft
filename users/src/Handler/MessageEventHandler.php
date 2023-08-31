<?php

namespace App\Handler;

use App\Message\MessageEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class MessageEventHandler
{
    public function __construct(
        protected LoggerInterface $logger,
    ) {}

    public function __invoke(MessageEvent $statusUpdate): void
    {
        $content = $statusUpdate->getContent();

        $this->logger->debug('Message received: '.$content);

        // the rest of business logic, i.e. sending email to user
        // $this->emailService->email()
    }
}