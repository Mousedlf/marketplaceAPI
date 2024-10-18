<?php

namespace App\Service;

use App\Entity\API;
use App\Repository\PlatformAPIKeyRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RequestToApiService
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private PlatformAPIKeyRepository $platformAPIKeyRepository,
        private MailerService $mailerService,
    )
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

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getRequestFromClient(string $extendUrl, string $baseUrl, string $adminKey, string $clientEmail)
    {
        if (!mb_check_encoding($adminKey, 'UTF-8')) {
            $adminKey = mb_convert_encoding($adminKey, 'UTF-8');
        }
        if (!mb_check_encoding($clientEmail, 'UTF-8')) {
            $clientEmail = mb_convert_encoding($clientEmail, 'UTF-8');
        }

        $url = $baseUrl.$extendUrl;

        $response = $this->client->request(
            'POST',
            $url,
            [
                'json' => [
                    "adminKey" => $adminKey,
                    "email" => $clientEmail
                ]
            ]
        );

        return $response->toArray();

    }

    public function getAdminKeyByApi(API $API)
    {
        $adminKey = $this->platformAPIKeyRepository->findOneBy(['api' => $API]);
        if ($adminKey) {
            return base64_decode($adminKey->getValue());
        }
        return "default";
    }


    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws \Exception
     */
    public function generateNewKey(string $url, $adminKey, $clientEmail,$delete = false)
    {

        if (!mb_check_encoding($adminKey, 'UTF-8')) {
            $adminKey = mb_convert_encoding($adminKey, 'UTF-8');
        }
        if (!mb_check_encoding($clientEmail, 'UTF-8')) {
            $clientEmail = mb_convert_encoding($clientEmail, 'UTF-8');
        }

        $response = $this->client->request(
            'POST',
            $url,
            [
                'json' => [
                    "adminKey" => $adminKey,
                    "email" => $clientEmail,
                    "mustBeRemoved"=>$delete
                ]
            ]
        );

        if (!$delete){
            $data = $response->toArray();

            $apiKey = $data["apiKey"];
            return $this->mailerService->sendNewGeneratedApiKey($clientEmail,"Your new generated key",$apiKey);
        }
    }
}