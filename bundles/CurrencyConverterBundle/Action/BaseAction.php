<?php

namespace Bundles\CurrencyConverterBundle\Action;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Contracts\Translation\TranslatorInterface;
use Bundles\CurrencyConverterBundle\Service\CurrencyApiService;

abstract class BaseAction extends AbstractController
{
    protected TranslatorInterface $translator;
    protected CurrencyApiService $currencyApiService;

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
}
