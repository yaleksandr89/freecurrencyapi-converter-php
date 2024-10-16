<?php

namespace Bundles\CurrencyConverterBundle\Action;

use IntlDateFormatter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Locales;

class DashboardAction extends BaseAction
{
    public function __invoke(): Response
    {
        $lastUpdatedCurrencies = $this
            ->currencyRepository
            ->findRecentUpdatedCurrencies(12);

        foreach ($lastUpdatedCurrencies as $currency) {
            $locale = $this->getCurrentLocale();
            $timezone = 'ru' === $locale ? 'Europe/Moscow' : 'UTC';

            $formatter = new IntlDateFormatter(
                $locale,
                IntlDateFormatter::LONG, // Дата
                IntlDateFormatter::SHORT, // Время
                $timezone, // Временная зона
                IntlDateFormatter::GREGORIAN, // Календарь
                'd MMMM yyyy \'г.\' в H:mm:ss' // Формат
            );

            $currency->formattedUpdatedAt = $formatter->format($currency->getUpdatedAt());
        }

        return $this->render('@CurrencyConverter/action/dashboard.html.twig', [
            'last_updated_currencies' => $lastUpdatedCurrencies,
        ]);
    }
}
