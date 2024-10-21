<?php

namespace Bundles\CurrencyConverterBundle\Action;

use Bundles\CurrencyConverterBundle\Repository\CurrencyRepository;
use Bundles\CurrencyConverterBundle\Service\CurrencyApiService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class BaseAction extends AbstractController
{
    protected const LIMIT = 10;

    protected TranslatorInterface $translator;

    protected CurrencyApiService $currencyApiService;

    protected CurrencyRepository $currencyRepository;

    protected LoggerInterface $logger;

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

    #[Required]
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
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

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getSession(): SessionInterface
    {
        return $this->container
            ->get('request_stack')
            ->getSession();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function isAjaxRequest(): bool
    {
        $request = $this->container
            ->get('request_stack')
            ->getCurrentRequest();

        if (!$request) {
            return false;
        }

        return $request->isXmlHttpRequest();
    }
}
