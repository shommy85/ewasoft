<?php

namespace App\Components\Communication;

use Symfony\Contracts\HttpClient\ResponseInterface;

class BaseCommunicationService
{
    protected function extractResult(ResponseInterface $response): array
    {
        return json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
    }
}