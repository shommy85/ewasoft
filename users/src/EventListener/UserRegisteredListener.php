<?php

namespace App\EventListener;

use App\Entity\User;
use App\Message\UserEvent;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: User::class)]
class UserRegisteredListener
{
    public function __construct(private readonly MessageBusInterface $messageBus)
    {
    }

    public function postPersist(User $user): void
    {
        $this->messageBus->dispatch(
            new UserEvent(UserEvent::REGISTERED_ACTION, $user->getId(), $user->getName(), $user->getEmail()),
            [new AmqpStamp('user.registered')]
        );
    }
}