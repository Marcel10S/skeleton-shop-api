<?php

declare(strict_types=1);

namespace App\Infrastructure\Currency\Provider;

use App\Entity\App\Currency;
use App\Infrastructure\Currency\Repository\CurrencyQueryRepository;
use Doctrine\ORM\EntityNotFoundException;

class CurrencyProvider
{
    public function __construct(private readonly CurrencyQueryRepository $queryRepository)
    {
    }

    /** @return Currency[] */
    public function findAll(): array
    {
        return $this->queryRepository->findAllOrdered();
    }

    public function findOneByCode(string $code): Currency
    {
        $currency = $this->queryRepository->findOneByCode($code);

        if ($currency === null) {
            throw new EntityNotFoundException(sprintf('Currency "%s" was not found.', $code));
        }

        return $currency;
    }

    public function findDefault(): Currency
    {
        $currency = $this->queryRepository->findDefault();

        if ($currency === null) {
            throw new EntityNotFoundException('Default currency was not found.');
        }

        return $currency;
    }
}
