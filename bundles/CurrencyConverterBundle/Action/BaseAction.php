<?php

namespace Bundles\CurrencyConverterBundle\Action;

use Bundles\CurrencyConverterBundle\Repository\CurrencyRepository;
use Bundles\CurrencyConverterBundle\Service\CurrencyApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class BaseAction extends AbstractController
{
    protected TranslatorInterface $translator;

    protected CurrencyApiService $currencyApiService;

    protected CurrencyRepository $currencyRepository;

    #[Required]
    public function setTranslator(TranslatorInterface $translator): void
    {
        $this->translator = $translator;
    }

    #[Required]
    public function setCurrencyApiService(CurrencyApiService $currencyApiService): void
    {
        $this->currencyApiService = $currencyApiService;
    }

    #[Required]
    public function setCurrencyRepository(CurrencyRepository $currencyRepository): void
    {
        $this->currencyRepository = $currencyRepository;
    }

    protected function trans(
        string $id,
        array $parameters = [],
        string $domain = 'CurrencyConverterBundle',
        string $locale = null
    ): string {
        return $this->translator->trans(
            id: $id,
            parameters: $parameters,
            domain: $domain,
            locale: $locale
        );
    }

    public function getCurrentLocale(): string
    {
        return $this
            ->translator
            ->getLocale();
    }
}
