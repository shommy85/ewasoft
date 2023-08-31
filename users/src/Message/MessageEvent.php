<?php

namespace App\Message;

class MessageEvent
{
    const CREATED_ACTION = 'created';
    public function __construct(protected string $action, protected int $senderId, protected int $receiverId, protected string $content)
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
    public function getSenderId(): int
    {
        return $this->senderId;
    }

    /**
     * @return int
     */
    public function getReceiverId(): int
    {
        return $this->receiverId;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}