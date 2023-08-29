<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
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

        if ($request->request->count()) {
            // This is a post request, update the body and headers accordingly
            $formData = new FormDataPart($request->request->all());
            $options['headers'] = array_merge($options['headers'], $formData->getPreparedHeaders()->toArray());
            $options['body'] = $formData->bodyToIterable();
        }

        $requestUrl = $remoteBaseUrl . $path . $request->getQueryString();

        $clientResponse = $this->client->request(
            $request->getMethod(),
            $requestUrl,
            $options
        );

        //$contentType = $clientResponse->getHeaders(false)['content-type'][0]?: 'application\/json';
        $contentType = 'application\/json';
//        dump($clientResponse);
//        return new Response('OK');
        return new Response(
            $clientResponse->getContent(false),
            $clientResponse->getStatusCode(),
            ['Content-Type' => $contentType],
        );
    }
}