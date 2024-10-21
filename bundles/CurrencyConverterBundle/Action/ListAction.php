<?php

namespace Bundles\CurrencyConverterBundle\Action;

use Symfony\Component\HttpFoundation\Response;

class ListAction extends BaseAction
{
    public function __invoke(int $page = 1): Response
    {
        $limit = self::LIMIT;

        $currenciesDTO = $this->currencyRepository->findDTOPaginatedCurrencies($page, $limit);
        $totalCurrencies = $this->currencyRepository->countCurrencies();

        return $this->render('@CurrencyConverter/action/list.html.twig', [
            'currencies' => $currenciesDTO,
            'currentPage' => $page,
            'totalPages' => ceil($totalCurrencies / $limit),
        ]);
    }
}
