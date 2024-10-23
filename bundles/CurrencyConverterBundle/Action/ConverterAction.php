<?php

namespace Bundles\CurrencyConverterBundle\Action;

use Bundles\CurrencyConverterBundle\DTO\CurrencyDTO;
use Bundles\CurrencyConverterBundle\Form\ConverterType;
use Bundles\CurrencyConverterBundle\Service\ConverterService;
use Exception;
use InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\Service\Attribute\Required;

class ConverterAction extends BaseAction
{
    private ConverterService $converterService;

    #[Required]
    public function setCurrencyConverterService(ConverterService $converterService): void
    {
        $this->converterService = $converterService;
    }

    /**
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(Request $request): Response
    {
        // Проверяем наличие данных в таблице currencies
        $currencies = $this->currencyRepository->findAllDTO();
        if (empty($currencies)) {
            return $this->render('@CurrencyConverter/_embed/_no-data-available.html.twig', [
                'message' => $this->trans('currencies.actions.historical_rates.data_is_empty'),
            ]);
        }

        $form = $this->createForm(
            type: ConverterType::class,
            options: [
                'currencies' => $currencies,
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$this->isAjaxRequest()) {
                return $this->json([
                    'success' => false,
                    'message' => $this->trans('currencies.actions.update_currencies.forbidden'),
                ], 403);
            }
            if ($form->isValid()) {
                $data = $form->getData();
                try {
                    $this->dataIsNotEmpty($data, ['from_currency', 'to_currency', 'amount']);

                    /** @var CurrencyDTO $fromCurrency */
                    $fromCurrency = $data['from_currency'];
                    /** @var CurrencyDTO $toCurrency */
                    $toCurrency = $data['to_currency'];
                    $amount = $data['amount'];

                    $convertedAmount = $this->converterService->convert(
                        amount: $amount,
                        fromCurrency: $fromCurrency->code,
                        toCurrency: $toCurrency->code,
                    );

                    return $this->json([
                        'success' => true,
                        'convertedAmount' => $convertedAmount,
                    ]);

                } catch (Exception $e) {
                    $this->logger->error('Internal Server Error', [
                        'method' => __METHOD__,
                        'message' => $e->getMessage(),
                    ]);

                    return $this->json([
                        'success' => false,
                        'message' => $e->getMessage(),
                    ], 400);
                }
            } else {
                $errors = [];
                foreach ($form->getErrors(true) as $error) {
                    $errors[] = $this->trans($error->getMessage());
                }

                return $this->json([
                    'success' => false,
                    'errors' => $errors,
                ], 400);
            }
        }

        return $this->render('@CurrencyConverter/action/converter.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function dataIsNotEmpty(array $data, array $requiredKeys): void
    {
        if (array_diff_key(array_flip($requiredKeys), $data)) {
            throw new InvalidArgumentException($this->trans('currencies.actions.converter.missing_required_fields'));
        }
    }
}
