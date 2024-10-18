<?php

namespace App\Service;

use App\Entity\API;
use App\Entity\Offer;
use App\Repository\PlatformAPIKeyRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FetchApiData
{
    private $platformApiKeyRepository;
    private $httpClient;

    public function __construct(PlatformAPIKeyRepository $platformApiKeyRepository, HttpClientInterface $httpClient)
    {
        $this->platformApiKeyRepository = $platformApiKeyRepository;
        $this->httpClient = $httpClient;
    }

    public function fetchGenerateKey(API $api, Offer $offer, string $email): string
    {
        $platformApiKey = $this->platformApiKeyRepository->findOneBy(['api' => $api]);
        if (!$platformApiKey) {
            throw new \Exception('Admin key not found for the API.');
        }

        $url = $api->getBaseUrl() . $api->getClientCreationRoute();
        $data = [
            'email' => $email,
            'nbOfAvailableRequests' => $offer->getNbOfAvailableRequests(),
            'adminKey' => base64_decode($platformApiKey->getValue()),
        ];

        $response = $this->httpClient->request('POST', $url, [
            'json' => $data,
        ]);

        // status request response
        if ($response->getStatusCode() !== 201) {
            throw new \Exception('Failed to fetch API key from the external API.');
        }

        $responseData = $response->toArray();
        $apiKey = $responseData['apiKey'] ?? null;

        if (!$apiKey) {
            throw new \Exception('API key not found in the response.');
        }

        return $apiKey;
    }
}
