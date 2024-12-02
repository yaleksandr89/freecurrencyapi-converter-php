<?php

namespace Bundles\CurrencyConverterBundle\Action;

use Symfony\Component\HttpFoundation\Response;

class DashboardAction extends BaseAction
{
    public function __invoke(): Response
    {
        $currenciesExist = $this->currencyRepository->countCurrencies() > 0;

        $schedule = $this->scheduleRepository->getOrCreateSchedule();
        $nextUpdateAt = $schedule->getNextUpdate();

        return $this->render('@CurrencyConverter/action/dashboard.html.twig', [
            'updatedAt' => $this->currencyRepository->findLastUpdateAt(),
            'currenciesExist' => $currenciesExist,
            'updateFrequency' => $this->updateScheduleService->getUpdateFrequency(),
            'nextUpdateAt' => $nextUpdateAt,
        ]);
    }
}
