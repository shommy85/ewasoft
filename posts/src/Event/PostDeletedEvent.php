<?php

namespace App\Event;



use Symfony\Contracts\EventDispatcher\Event;

class PostDeletedEvent extends Event
{
    public function __construct(
        protected int $postId,
        protected int $authorId,
    ) {
    }

    /**
     * @return int
     */
    public function getPostId(): int
    {
        return $this->postId;
    }

    /**
     * @return int
     */
    public function getAuthorId(): int
    {
        return $this->authorId;
    }
}