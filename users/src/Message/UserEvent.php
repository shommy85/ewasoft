<?php

namespace App\Message;

class UserEvent
{
    const REGISTERED_ACTION = 'registered';
    public function __construct(protected string $action, protected int $userId, protected string $name, protected string $email)
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
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

}