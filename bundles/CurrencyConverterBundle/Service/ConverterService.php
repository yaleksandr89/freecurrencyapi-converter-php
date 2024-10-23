<?php

namespace Bundles\CurrencyConverterBundle\Service;

use Bundles\CurrencyConverterBundle\Repository\CurrencyRepository;
use RuntimeException;

readonly class ConverterService
{
    public function __construct(
        private CurrencyRepository $currencyRepository
    ) {
    }

    public function convert(float $amount, string $fromCurrency, string $toCurrency, int $precision = 2): float
    {
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        $fromCurrencyDTO = $this->currencyRepository->findDTOByCode($fromCurrency);
        $toCurrencyDTO = $this->currencyRepository->findDTOByCode($toCurrency);

        if ($fromCurrencyDTO === null || $toCurrencyDTO === null) {
            throw new RuntimeException(sprintf('Currency data for %s or %s not found.', $fromCurrency, $toCurrency));
        }

        $baseAmount = $amount / $fromCurrencyDTO->rate;
        $convertedAmount = $baseAmount * $toCurrencyDTO->rate;

        return round($convertedAmount, $precision);
    }
}
