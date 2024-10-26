<?php

namespace Bundles\CurrencyConverterBundle\Repository;

use Bundles\CurrencyConverterBundle\Entity\CurrencyUpdateSchedule;
use DateTime;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CurrencyUpdateScheduleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CurrencyUpdateSchedule::class);
    }

    // Метод для получения текущего расписания обновлений или его создания
    public function getOrCreateSchedule(): CurrencyUpdateSchedule
    {
        $schedule = $this->find(1);

        if (!$schedule) {
            $schedule = new CurrencyUpdateSchedule();
            $schedule->setLastUpdate(new DateTime('1970-01-01'));
            $schedule->setNextUpdate((new DateTime())->modify('+1 day'));
            $this->saveSchedule($schedule);
        }

        return $schedule;
    }

    // Метод для обновления дат последнего и следующего обновлений
    public function updateScheduleDates(DateTimeInterface $lastUpdate, DateTimeInterface $nextUpdate): void
    {
        $schedule = $this->getOrCreateSchedule();
        $schedule->setLastUpdate($lastUpdate);
        $schedule->setNextUpdate($nextUpdate);
        $this->saveSchedule($schedule);
    }

    private function saveSchedule(CurrencyUpdateSchedule $schedule): void
    {
        $em = $this->getEntityManager();
        $em->persist($schedule);
        $em->flush();
    }
}
