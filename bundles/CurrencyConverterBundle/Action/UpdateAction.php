<?php

namespace Bundles\CurrencyConverterBundle\Action;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UpdateAction extends BaseAction
{
    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function __invoke(): RedirectResponse
    {
        // Загружаем данные с API
        $currenciesData = $this
            ->currencyApiService
            ->getAllCurrencies();

        // Сохраняем или обновляем валюты через репозиторий
        $this
            ->currencyRepository
            ->saveOrUpdateCurrencies($currenciesData);

        return $this
            ->redirectToRoute('list_currencies_action');
    }
}