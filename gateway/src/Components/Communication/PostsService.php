<?php

namespace App\Components\Communication;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PostsService extends BaseCommunicationService
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client, TokenStorageInterface $tokenStorage)
    {
        $this->client = $client->withOptions([
            'base_uri' => 'http://posts/', //TODO: Put this in config
            'auth_bearer' => $tokenStorage->getToken()->getCredentials()
        ]);
    }

    /**
     * Returns post data for given id
     * @param int $postId
     * @return array
     * @throws \JsonException
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getPost(int $postId): array
    {
        $response = $this->client->request(
            'GET',
            'posts/'.$postId
        );

       return $this->extractResult($response);
    }
}