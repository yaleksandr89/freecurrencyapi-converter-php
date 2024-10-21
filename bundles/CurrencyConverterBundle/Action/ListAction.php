<?php

namespace Bundles\CurrencyConverterBundle\Action;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response;

class ListAction extends BaseAction
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(int $page = 1): Response
    {
        $limit = self::LIMIT;

        $currenciesDTO = $this->currencyRepository->findDTOPaginatedCurrencies($page, $limit);
        $totalCurrencies = $this->currencyRepository->countCurrencies();
        $totalPages = ceil($totalCurrencies / $limit);

        $pagination = $totalCurrencies > 0
            ? $this->renderView('@CurrencyConverter/_embed/_pagination.html.twig', [
                'routeName' => 'list_currencies_action',
                'currentPage' => $page,
                'totalPages' => $totalPages,
            ])
            : '';

        if ($this->isAjaxRequest()) {
            $currencyArrayWithDateTime = array_map(
                static fn($currencyDTO) => $currencyDTO->toArrayWithDateTimeToString('H:i:s d.m.Y'),
                $currenciesDTO
            );

            return $this->json([
                'currencies' => $currencyArrayWithDateTime,
                'pagination' => $pagination,
            ]);
        }

        return $this->render('@CurrencyConverter/action/list.html.twig', [
            'currencies' => $currenciesDTO,
            'pagination' => $pagination,
        ]);
    }
}
