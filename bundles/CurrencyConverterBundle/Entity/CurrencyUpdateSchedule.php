<?php

declare(strict_types=1);

namespace Bundles\CurrencyConverterBundle\Entity;

use Bundles\CurrencyConverterBundle\Repository\CurrencyUpdateScheduleRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[
    Table(name: 'currency_update_schedule'),
    Entity(repositoryClass: CurrencyUpdateScheduleRepository::class)
]
class CurrencyUpdateSchedule
{
    #[Id, GeneratedValue, Column(type: Types::INTEGER)]
    private int $id;

    #[Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $lastUpdate;

    #[Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $nextUpdate;

    public function getId(): int
    {
        return $this->id;
    }

    public function getLastUpdate(): DateTimeInterface
    {
        return $this->lastUpdate;
    }

    public function setLastUpdate(DateTimeInterface $date): void
    {
        $this->lastUpdate = $date;
    }

    public function getNextUpdate(): DateTimeInterface
    {
        return $this->nextUpdate;
    }

    public function setNextUpdate(DateTimeInterface $date): void
    {
        $this->nextUpdate = $date;
    }
}
