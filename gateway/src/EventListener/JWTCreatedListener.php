<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Bundle\SecurityBundle\Security;

class JWTCreatedListener
{
    public function __construct(
        private readonly Security $security,
    ){
    }

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $user = $this->security->getUser();
        $payload       = $event->getData();
        $payload['id'] = $user->getId();

        $event->setData($payload);
    }
}