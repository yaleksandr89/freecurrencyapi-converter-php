<?php

namespace Bundles\CurrencyConverterBundle\Service;


use DateTime;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class UpdateScheduleService
{
    public function __construct(
        private int $updateIntervalHours,
        private TranslatorInterface $translator
    ) {
    }

    public function getUpdateFrequency(): string
    {
        return $this->translator->trans(
            'update_frequency.hours',
            ['%count%' => $this->updateIntervalHours],
            'CurrencyConverterBundle'
        );
    }

    public function calculateNextUpdate(DateTime $lastUpdate): DateTime
    {
        return (clone $lastUpdate)->modify("+{$this->updateIntervalHours} hours");
    }
}
