<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ReverseProxyService
{
    /**
     * @param HttpClientInterface $client
     */
    public function __construct(private HttpClientInterface $client)
    {
    }

    public function makeProxyRequest(Request $request, $remoteHost, $path): Response
    {
        $options            = [];
        $remoteBaseUrl      = "http://{$remoteHost}/";
        $options['headers'] = $request->headers->all();
        $options['body']    = $request->getContent();

        if ($request->files->count()) {
            $options['body'] = array_map(
                function($file) { return fopen($file->getPathname(), 'r'); },
                $request->files->all()
            );
        }

        $requestUrl = $remoteBaseUrl . $path . $request->getQueryString();

        $clientResponse = $this->client->request(
            $request->getMethod(),
            $requestUrl,
            $options
        );

        $contentType = $clientResponse->getHeaders(false)['content-type'][0]?: 'application\/json';

        return new Response(
            $clientResponse->getContent(false),
            $clientResponse->getStatusCode(),
            ['Content-Type' => $contentType],
        );
    }
}