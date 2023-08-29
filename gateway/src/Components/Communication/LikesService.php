<?php

namespace App\Components\Communication;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class LikesService extends BaseCommunicationService
{
    public function __construct(HttpClientInterface $client, TokenStorageInterface $tokenStorage)
    {
        $this->client = $client->withOptions([
            'base_uri' => 'http://likes/',
            'auth_bearer' => $tokenStorage->getToken()->getCredentials()
        ]);
    }

    public function getUserLikedPosts(): array
    {
        $response = $this->client->request(
            'GET',
            'post-likes/me'
        );

        return $this->extractResult($response);
    }
}