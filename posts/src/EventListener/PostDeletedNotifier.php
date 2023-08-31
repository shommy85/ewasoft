<?php

namespace App\EventListener;

use App\Event\PostDeletedEvent;
use App\Message\PostEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsEventListener(event: PostDeletedEvent::class, method: 'onPostDeleted')]
class PostDeletedNotifier
{
    public function __construct(private readonly MessageBusInterface $messageBus)
    {
    }

    public function onPostDeleted(PostDeletedEvent $postDeletedEvent): void
    {
        $this->messageBus->dispatch(
            new PostEvent(PostEvent::DELETE_ACTION, $postDeletedEvent->getPostId(), $postDeletedEvent->getAuthorId()),
            [new AmqpStamp('post.deleted')]
        );
    }
}