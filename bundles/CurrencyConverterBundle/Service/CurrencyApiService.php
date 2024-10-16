<?php

namespace Bundles\CurrencyConverterBundle\Service;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class CurrencyApiService
{
    public function __construct(
        private HttpClientInterface $client,
        private LoggerInterface $logger,
        private ?string $apiKey,
        private ?string $apiUrl
    ) {
        self::checkApiKeyAndUrl($this->apiKey, $this->apiUrl);
    }

    private static function checkApiKeyAndUrl(?string $apiKey, ?string $apiUrl): void
    {
        if (empty($apiKey)) {
            throw new \RuntimeException('Environment variable "CURRENCY_CONVERTER_API_KEY" is not set. Please add it to your .env file.');
        }
        if (empty($apiUrl)) {
            throw new \RuntimeException('Environment variable "CURRENCY_CONVERTER_API_URL" is not set. Please add it to your .env file.');
        }
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getStatus(): array
    {
        $endpoint = $this->apiUrl.'/status';

        //$this->log('info', 'Sending request to FreeCurrencyAPI status endpoint', ['url' => $endpoint]);
        $response = $this->client->request('GET', $endpoint, [
            'query' => [
                'apikey' => $this->apiKey,
            ],
        ]);
        //$this->log('info', 'Response received from FreeCurrencyAPI', ['status_code' => $response->getStatusCode()]);

        return $response->toArray();
    }

    private function log(string $type = 'info', string $message, array $context = []): void
    {
        $this
            ->logger
            ->$type($message, $context);
    }
}
