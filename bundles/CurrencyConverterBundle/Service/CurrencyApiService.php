<?php

namespace Bundles\CurrencyConverterBundle\Service;

use Psr\Log\LoggerInterface;
use RuntimeException;
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
        private ?string $apiUrl,
        private bool $useMockData,
    ) {
        self::checkApiKeyAndUrl($this->apiKey, $this->apiUrl);
    }

    private static function checkApiKeyAndUrl(?string $apiKey, ?string $apiUrl): void
    {
        if (empty($apiKey)) {
            throw new RuntimeException('Environment variable "CURRENCY_CONVERTER_API_KEY" is not set. Please add it to your .env file.');
        }
        if (empty($apiUrl)) {
            throw new RuntimeException('Environment variable "CURRENCY_CONVERTER_API_URL" is not set. Please add it to your .env file.');
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
        $endpoint = $this->apiUrl . '/status';
        //$this->log('info', 'Sending request to FreeCurrencyAPI status endpoint', ['url' => $endpoint]);
        $response = $this->client->request('GET', $endpoint, [
            'query' => [
                'apikey' => $this->apiKey,
            ],
        ]);
        //$this->log('info', 'Response received from FreeCurrencyAPI (status endpoint)', ['status_code' => $response->getStatusCode()]);

        return $response->toArray();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getAllCurrencies(): array
    {
        if ($this->useMockData) {
            return include dirname(__DIR__) . '/Resources/mocks/received-currencies-17102024.php';
        }

        $endpoint = $this->apiUrl . '/currencies';
        //$this->log('info', 'Sending request to FreeCurrencyAPI currencies endpoint', ['url' => $endpoint]);
        $response = $this->client->request('GET', $endpoint, [
            'query' => [
                'apikey' => $this->apiKey,
            ],
        ]);
        //$this->log('info', 'Response received from FreeCurrencyAPI (currencies endpoint)', ['status_code' => $response->getStatusCode()]);

        return $response->toArray()['data'];
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getCurrencyRates(string $baseCurrency = 'USD'): array
    {
        if ($this->useMockData) {
            return include dirname(__DIR__) . '/Resources/mocks/exchange-rate-relative-base-currency_17102024.php';
        }

        $endpoint = $this->apiUrl . '/latest';
        //$this->log('info', 'Sending request to FreeCurrencyAPI latest endpoint', ['url' => $endpoint]);
        $response = $this->client->request('GET', $endpoint, [
            'query' => [
                'apikey' => $this->apiKey,
                'base_currency' => $baseCurrency,
            ],
        ]);
        //$this->log('info', 'Response received from FreeCurrencyAPI (latest endpoint)', ['status_code' => $response->getStatusCode()]);

        return $response->toArray()['data'];
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getHistoricalRates(string $date, string $baseCurrency = 'USD', array $currencies = []): array
    {
        if ($this->useMockData) {
            return include dirname(__DIR__) . '/Resources/mocks/currencies-historical-20000101.php';
        }

        $endpoint = $this->apiUrl . '/historical';
        //$this->log('info', 'Sending request to FreeCurrencyAPI historical endpoint', ['url' => $endpoint]);
        $response = $this->client->request('GET', $endpoint, [
            'query' => [
                'apikey' => $this->apiKey,
                'date' => $date,
                'base_currency' => $baseCurrency,
                'currencies' => implode(',', $currencies),
            ],
        ]);
        //$this->log('info', 'Response received from FreeCurrencyAPI (historical endpoint)', ['status_code' => $response->getStatusCode()]);

        return $response->toArray()['data'];
    }

    private function log(string $message, string $type = 'info', array $context = []): void
    {
        $this
            ->logger
            ->$type(
                $message,
                $context
            );
    }
}
