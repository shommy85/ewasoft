<?php

namespace App\Handler;


use App\Message\PostEvent;
use App\Repository\PostUserLikesRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class PostEventHandler
{
    public function __construct(
        protected LoggerInterface $logger,
        protected PostUserLikesRepository $repository
    ) {}

    public function __invoke(PostEvent $postEvent): void
    {
        if($postEvent->getAction() != PostEvent::DELETE_ACTION) {
            return;
        }

        $this->repository->deleteLikesForPost($postEvent->getPostId());
    }
}