<?php

namespace Bundles\CurrencyConverterBundle\Action;

use Bundles\CurrencyConverterBundle\Form\HistoricalRatesType;
use DateTime;
use Exception;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class HistoricalRatesAction extends BaseAction
{
    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function __invoke(Request $request): Response
    {
        // Проверяем наличие данных в таблице currencies
        $currencies = $this->currencyRepository->findAllDTO();
        if (empty($currencies)) {
            return $this->render('@CurrencyConverter/_embed/_no-data-available.html.twig', [
                'message' => $this->trans('currencies.actions.historical_rates.data_is_empty'),
            ]);
        }

        $form = $this->createForm(
            type: HistoricalRatesType::class,
            options: [
                'currencies' => $this->currencyRepository->findAllDTO('code'),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Данные для отправки в API
            $date = $data['date'];
            $baseCurrency = $data['base_currency']->code;
            $currencies = array_map(static function ($currency) {
                return $currency->code; // Получаем коды валют
            }, $data['currencies']);

            // Получение исторических курсов из API
            try {
                $this->dateIsValid($date);

                // Получение курсов из API
                $historicalRates = $this->currencyApiService->getHistoricalRates(
                    date: $date->format('Y-m-d'),
                    baseCurrency: $baseCurrency,
                    currencies: $currencies,
                );

                $dataForTable = [];
                foreach ($historicalRates as $rates) {
                    $dataForTable[] = [
                        'date' => $date->format('d.m.Y'),
                        'rates' => $rates,
                    ];
                }

                return $this->json([
                    'success' => true,
                    'html' => $this->renderView('@CurrencyConverter/_embed/_historical_rates_table.html.twig', [
                        'historicalData' => $dataForTable,
                        'baseCurrency' => $baseCurrency,
                    ]),
                ]);
            } catch (Exception $e) {
                $this->logger->error('Internal Server Error', ['method' => __METHOD__, 'message' => $e->getMessage(),]);

                return $this->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        return $this->render('@CurrencyConverter/action/historical_rates.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function dateIsValid(DateTime $date): void
    {
        $minDate = new DateTime('2000-01-01');
        if ($date < $minDate) {
            throw new RuntimeException($this->trans('currencies.form.date.too_early'));
        }

        $yesterday = new DateTime('yesterday');
        if ($date > $yesterday) {
            throw new RuntimeException($this->trans('currencies.form.date.too_late'));
        }
    }
}
