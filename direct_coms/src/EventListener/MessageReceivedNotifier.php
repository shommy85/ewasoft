<?php

namespace App\EventListener;

use App\Entity\Message;
use App\Message\MessageEvent;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Message::class)]
class MessageReceivedNotifier
{
    public function __construct(private readonly MessageBusInterface $messageBus)
    {
    }

    public function postPersist(Message $message): void
    {
        $this->messageBus->dispatch(
            new MessageEvent('created', $message->getSenderId(), $message->getRecipientId(), $message->getContent()),
            [new AmqpStamp('message.published')]
        );
    }
}