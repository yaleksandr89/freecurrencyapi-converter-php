<?php

namespace Bundles\CurrencyConverterBundle\Repository;

use Bundles\CurrencyConverterBundle\Entity\Currency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CurrencyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Currency::class);
    }

    // Находим валюту по коду
    public function findByCode(string $code): ?Currency
    {
        return $this->findOneBy(['code' => $code]);
    }

    // Сохранение валюту в БД
    public function saveCurrency(Currency $currency): void
    {
        $em = $this->getEntityManager();
        $em->persist($currency);
        $em->flush();
    }

    // Обновляет курс валюты
    public function updateCurrencyRate(Currency $currency, float $rate): void
    {
        $currency->updateRate($rate);
        $this->getEntityManager()->flush();
    }

    // Сохраняет или обновляет валюты, полученные из API
    public function saveOrUpdateCurrencies(array $currenciesData): void
    {
        /** @var Currency $currencyData */
        foreach ($currenciesData as $code => $currencyData) {
            $currency = $this->findByCode($code);

            if (!$currency) {
                $currency = new Currency();
                $currency->setRate(1.0);
            } else {
                $currency->updateRate(1.0);
            }

            $currency->setTitle($currencyData['name']);
            $currency->setCode($currencyData['code']);
            $currency->setSymbol($currencyData['symbol']);
            $currency->setNamePlural($currencyData['name_plural']);
            $this->saveCurrency($currency);
        }
    }

    /**
     * Получить последние обновленные валюты (для панели управления)
     *
     * @param  int  $limit  Количество валют для получения
     * @return Currency[]
     */
    public function findRecentUpdatedCurrencies(int $limit = 10): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.updatedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}