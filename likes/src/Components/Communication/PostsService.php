<?php

namespace App\Components\Communication;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PostsService
{
    private HttpClientInterface $client;
//    public function __construct(private HttpClientInterface $client, TokenStorageInterface $tokenStorage)
//    {
//        $this->client->withOptions([
//            'base_uri' => 'http://localhost:8083/',
//            'auth_bearer' => $tokenStorage->getToken()->getCredentials()
//        ]);
////        throw new \Exception($this->client->g);
//    }

    public function __construct(HttpClientInterface $client, TokenStorageInterface $tokenStorage)
    {
        $this->client = $client->withOptions([
            'base_uri' => 'http://posts/',
            'auth_bearer' => $tokenStorage->getToken()->getCredentials()
        ]);
//        throw new \Exception($this->client->g);
    }

    public function postExists(int $postId): bool
    {
        $response = $this->client->request(
            'GET',
            'posts/'.$postId
        );

        return $response->getStatusCode() == 200;
    }
}