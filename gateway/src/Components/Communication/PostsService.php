<?php

namespace App\Components\Communication;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class PostsService extends BaseCommunicationService
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client, TokenStorageInterface $tokenStorage)
    {
        $this->client = $client->withOptions([
            'base_uri' => 'http://posts/',
            'auth_bearer' => $tokenStorage->getToken()->getCredentials()
        ]);
    }

    public function getPost(int $postId): array
    {
        $response = $this->client->request(
            'GET',
            'posts/'.$postId
        );

       return $this->extractResult($response);
    }
}