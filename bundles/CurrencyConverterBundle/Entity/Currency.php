<?php

declare(strict_types=1);

namespace Bundles\CurrencyConverterBundle\Entity;

use AllowDynamicProperties;
use Bundles\CurrencyConverterBundle\Repository\CurrencyRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[
    AllowDynamicProperties,
    Table(name: 'currencies'),
    Entity(repositoryClass: CurrencyRepository::class)
]
class Currency
{
    #[Id, GeneratedValue, Column(type: Types::INTEGER)]
    private int $id;

    #[Column(type: Types::STRING, length: 255)]
    private string $title;

    #[Column(type: Types::STRING, length: 10, unique: true)]
    private string $code;

    #[Column(type: Types::STRING, length: 10)]
    private string $symbol;

    #[Column(type: Types::STRING, length: 255)]
    private string $namePlural;

    #[Column(type: Types::FLOAT)]
    private float $rate;

    #[Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $createdAt;

    #[Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $updatedAt;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): void
    {
        $this->symbol = $symbol;
    }

    public function getNamePlural(): string
    {
        return $this->namePlural;
    }

    public function setNamePlural(string $namePlural): void
    {
        $this->namePlural = $namePlural;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function setRate(float $rate): void
    {
        $this->rate = $rate;
    }

    public function updateRate(float $rate): void
    {
        $this->rate = $rate;
        $this->updatedAt = new DateTime();
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }
}
