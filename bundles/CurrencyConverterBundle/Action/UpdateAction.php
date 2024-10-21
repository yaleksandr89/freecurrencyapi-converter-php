<?php

namespace Bundles\CurrencyConverterBundle\Action;


use Bundles\CurrencyConverterBundle\DTO\CurrencyDTO;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
    public function __invoke(Request $request): JsonResponse
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse([
                'success' => false,
                'message' => $this->trans('currencies.actions.update_currencies.forbidden'),
            ], 403);
        }

        try {
            // >>> API request
            $currenciesData = $this->currencyApiService->getAllCurrencies();
            $currencyRates = $this->currencyApiService->getCurrencyRates();
            // API request <<<

            // Сохраняем или обновляем валюты через репозиторий
            $this->currencyRepository->saveOrUpdateCurrencies($currenciesData, $currencyRates);

            // Получаем текущую страницу из параметров запроса
            $currentPage = (int)$request->request->get('page', 1);

            // Получаем валюты с учетом пагинации
            $limit = self::LIMIT; // Значение LIMIT, установленное в BaseAction
            $currenciesDTO = $this->currencyRepository->findDTOPaginatedCurrencies($currentPage, $limit);
            $totalCurrencies = $this->currencyRepository->countCurrencies();

            // Пример: если нужно с форматированными датами
            /** @var CurrencyDTO $currencyDTO */
            $currencyArrayWithDateTime = array_map(
                static fn($currencyDTO) => $currencyDTO->toArrayWithDateTimeToString('H:i:s d.m.Y'),
                $currenciesDTO
            );

            return new JsonResponse([
                'success' => true,
                'currencies' => $currencyArrayWithDateTime,
                'totalPages' => ceil($totalCurrencies / $limit),
                'currentPage' => $currentPage,
            ]);

        } catch (Exception $e) {
            $this->logger->error('Internal Server Error', ['method' => __METHOD__, 'message' => $e->getMessage(),]);

            return new JsonResponse([
                'success' => false,
                'message' => $this->trans('currencies.actions.update_currencies.server_error'),
            ], 500);
        }
    }
}
