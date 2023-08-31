<?php

namespace App\Message;

class PostEvent
{
    const DELETE_ACTION = 'delete';

    public function __construct(protected string $action, protected int $postId, protected int $authorId)
    {
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
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