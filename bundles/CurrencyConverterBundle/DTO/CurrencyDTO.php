<?php

namespace Bundles\CurrencyConverterBundle\DTO;

use DateTimeInterface;

class CurrencyDTO
{
    public function __construct(
        public int $id,
        public string $title,
        public string $code,
        public string $symbol,
        public string $namePlural,
        public float $rate,
        public DateTimeInterface $createdAt,
        public DateTimeInterface $updatedAt
    ) {
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'code' => $this->code,
            'symbol' => $this->symbol,
            'namePlural' => $this->namePlural,
            'rate' => $this->rate,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }

    public function toArrayWithDateTimeToString(string $dateTimeFormat = 'Y-m-d H:i:s'): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'code' => $this->code,
            'symbol' => $this->symbol,
            'namePlural' => $this->namePlural,
            'rate' => $this->rate,
            'createdAt' => $this->createdAt->format($dateTimeFormat),
            'updatedAt' => $this->updatedAt->format($dateTimeFormat),
        ];
    }
}
