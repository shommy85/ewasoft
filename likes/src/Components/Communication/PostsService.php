<?php

namespace App\Components\Communication;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Used for communication with post microservice
 */
class PostsService
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client, TokenStorageInterface $tokenStorage)
    {
        $this->client = $client->withOptions([
            'base_uri' => 'http://posts/',//TODO: Put this in config
            'auth_bearer' => $tokenStorage->getToken()->getCredentials()
        ]);
    }

    /**
     * Check if post with given id exists
     * @param int $postId
     * @return bool
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function postExists(int $postId): bool
    {
        $response = $this->client->request(
            'GET',
            'posts/'.$postId
        );

        return $response->getStatusCode() == 200;
    }
}