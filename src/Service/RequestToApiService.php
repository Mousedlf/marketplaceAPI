<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RequestToApiService
{
    public function __construct(private readonly HttpClientInterface $client)
    {

    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function createClient(string $url, int $boughtRequests,string $adminKey,string $clientEmail): array
    {
        $response = $this->client->request(
            'POST',
            $url,
            [
                'body' => [
                    "nbOfAvailableRequests" => $boughtRequests,
                    "adminKey" => $adminKey,
                    "email" => $clientEmail
                ]
            ]
        );
        return $response->toArray();
    }
}