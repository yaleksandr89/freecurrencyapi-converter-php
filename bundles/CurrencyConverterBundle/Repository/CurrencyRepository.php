<?php

namespace Bundles\CurrencyConverterBundle\Repository;

use Bundles\CurrencyConverterBundle\DTO\CurrencyDTO;
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
    public function saveOrUpdateCurrencies(array $currenciesData, array $currencyRates): void
    {
        /** @var Currency $currencyData */
        foreach ($currenciesData as $code => $currencyData) {
            $rate = $currencyRates[$code] ?? null;
            $currency = $this->findByCode($code);

            if (!$currency) {
                $currency = new Currency();
                $currency->setRate($rate);
            } else {
                $currency->updateRate($rate);
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

    // Выборка валют из БД с учетом параметров пагинации
    public function findPaginatedCurrencies(int $page, int $limit): array
    {
        return $this->createQueryBuilder('c')
            ->setFirstResult(self::getOffset($page, $limit))
            ->setMaxResults($limit)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function countCurrencies(): int
    {
        return (int)$this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    private function convertToDTO(Currency $currency): CurrencyDTO
    {
        return new CurrencyDTO(
            $currency->getId(),
            $currency->getTitle(),
            $currency->getCode(),
            $currency->getSymbol(),
            $currency->getNamePlural(),
            $currency->getRate(),
            $currency->getCreatedAt(),
            $currency->getUpdatedAt()
        );
    }

    public function findDTOById(int $id): ?CurrencyDTO
    {
        $currency = $this->find($id);

        return $currency ? $this->convertToDTO($currency) : null;
    }

    public function findAllDTO(): array
    {
        return array_map([$this, 'convertToDTO'], $this->findAll());
    }

    public function findDTOPaginatedCurrencies(int $page, int $limit): array
    {
        return array_map([$this, 'convertToDTO'], $this->findPaginatedCurrencies($page, $limit));
    }

    private static function getOffset(int $page, int $limit): int
    {
        return ($page - 1) * $limit;
    }
}
