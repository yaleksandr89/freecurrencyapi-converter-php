<?php

namespace Bundles\CurrencyConverterBundle\Service;


use DateTime;

readonly class UpdateScheduleService
{
    public function __construct(
        private int $updateIntervalHours
    ) {
    }

    public function getUpdateFrequency(): string
    {
        return "{$this->updateIntervalHours} часа";
    }

    public function calculateNextUpdate(DateTime $lastUpdate): DateTime
    {
        return (clone $lastUpdate)->modify("+{$this->updateIntervalHours} hours");
    }
}
