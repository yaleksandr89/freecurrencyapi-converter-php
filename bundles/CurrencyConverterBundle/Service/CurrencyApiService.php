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
        //$this->log('info', 'Response received from FreeCurrencyAPI', ['status_code' => $response->getStatusCode()]);

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
            return include dirname(__DIR__) . '/Resources/mocks/received_currencies_17102024.php';
        }

        $endpoint = $this->apiUrl . '/currencies';

        $response = $this->client->request('GET', $endpoint, [
            'query' => [
                'apikey' => $this->apiKey,
            ],
        ]);

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
            return include dirname(__DIR__) . '/Resources/mocks/exchange_rate_relative_base_currency_17102024.php';
        }

        $endpoint = $this->apiUrl . '/latest';

        $response = $this->client->request('GET', $endpoint, [
            'query' => [
                'apikey' => $this->apiKey,
                'base_currency' => $baseCurrency,
            ],
        ]);

        $data = $response->toArray();

        if (!isset($data['data'])) {
            throw new RuntimeException('Invalid response from FreeCurrencyAPI');
        }

        return $data['data'];
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
//        if ($this->useMockData) {
//            return include dirname(__DIR__) . '/Resources/mocks/historical_rates_mock.php';
//        }

        $endpoint = $this->apiUrl . '/historical';
        $query = [
            'apikey' => $this->apiKey,
            'date' => $date,
            'base_currency' => $baseCurrency,
        ];

        if (!empty($currencies)) {
            $query['currencies'] = implode(',', $currencies);
        }

        $response = $this->client->request('GET', $endpoint, [
            'query' => $query,
        ]);

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
