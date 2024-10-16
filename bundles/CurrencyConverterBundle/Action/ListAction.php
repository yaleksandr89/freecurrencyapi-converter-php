<?php

namespace Bundles\CurrencyConverterBundle\Action;

use Symfony\Component\HttpFoundation\Response;

class ListAction extends BaseAction
{
    public function __invoke(int $page = 1): Response
    {
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $currencies = $this->currencyRepository->findPaginatedCurrencies($offset, $limit);
        $totalCurrencies = $this->currencyRepository->countCurrencies();
        $totalPages = ceil($totalCurrencies / $limit);

        return $this->render('@CurrencyConverter/action/list.html.twig', [
            'currencies' => $currencies,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }
}
