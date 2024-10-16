<?php

namespace Bundles\CurrencyConverterBundle\Entity;

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

    #[Column(type: Types::FLOAT)]
    private float $rate;

    #[Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $createdAt;

    #[Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $updatedAt;

    public function __construct(string $title, string $code, float $rate)
    {
        $this->title = $title;
        $this->code = $code;
        $this->rate = $rate;
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

    public function getCode(): string
    {
        return $this->code;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function updateRate(float $rate): void
    {
        $this->rate = $rate;
        $this->updatedAt = new DateTime();
    }
}
