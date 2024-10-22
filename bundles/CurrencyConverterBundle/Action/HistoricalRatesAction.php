<?php

namespace Bundles\CurrencyConverterBundle\Action;

use Bundles\CurrencyConverterBundle\Form\HistoricalRatesType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HistoricalRatesAction extends BaseAction
{
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(
            type: HistoricalRatesType::class,
            options: [
                'currencies' => $this->currencyRepository->findAllDTO(),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            dd($data);
            // Логика обработки данных формы
            // Например, получение исторических курсов валют на основе выбранных валют и даты
        }

        // Предположим, что historicalRates получены из базы данных или другого источника
        $historicalRates = []; // Получите исторические данные для передачи в шаблон

        return $this->render('@CurrencyConverter/action/historical_rates.html.twig', [
            'form' => $form->createView(),
            'historicalRates' => $historicalRates,
        ]);
    }
}
