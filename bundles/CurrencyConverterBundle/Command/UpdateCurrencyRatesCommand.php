<?php

namespace Bundles\CurrencyConverterBundle\Command;

use Bundles\CurrencyConverterBundle\Repository\CurrencyRepository;
use Bundles\CurrencyConverterBundle\Repository\CurrencyUpdateScheduleRepository;
use Bundles\CurrencyConverterBundle\Service\CurrencyApiService;
use DateTime;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\Service\Attribute\Required;

#[AsCommand(
    name: 'currency:update-rates',
    description: 'Fetch and update currency exchange rates daily.'
)]
class UpdateCurrencyRatesCommand extends Command
{
    private CurrencyRepository $currencyRepository;

    private CurrencyUpdateScheduleRepository $scheduleRepository;

    private CurrencyApiService $currencyApiService;

    #[Required]
    public function setCurrencyRepository(CurrencyRepository $currencyRepository): void
    {
        $this->currencyRepository = $currencyRepository;
    }

    #[Required]
    public function setCurrencyUpdateScheduleRepository(CurrencyUpdateScheduleRepository $scheduleRepository): void
    {
        $this->scheduleRepository = $scheduleRepository;
    }

    #[Required]
    public function setCurrencyApiService(CurrencyApiService $currencyApiService): void
    {
        $this->currencyApiService = $currencyApiService;
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $schedule = $this->scheduleRepository->getOrCreateSchedule();
        $now = new DateTime();

        // нужно ли обновление
        if ($schedule->getNextUpdate() > $now) {
            $output->writeln(
                'Next update scheduled at: ' .
                $schedule->getNextUpdate()->format('Y-m-d H:i:s')
            );

            return Command::SUCCESS;
        }

        // >>> API request
        $currenciesData = $this->currencyApiService->getAllCurrencies();
        $currencyRates = $this->currencyApiService->getCurrencyRates();
        // API request <<<

        $this->currencyRepository->saveOrUpdateCurrencies($currenciesData, $currencyRates);

        // Обновляем расписание
        $tomorrow = (clone $now)->modify('+1 day');
        $this->scheduleRepository->updateScheduleDates($now, $tomorrow);

        $output->writeln(
            'Currency rates updated at ' .
            $now->format('Y-m-d H:i:s')
        );

        return Command::SUCCESS;
    }
}
