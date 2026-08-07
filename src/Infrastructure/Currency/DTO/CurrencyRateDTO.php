<?php

declare(strict_types=1);

namespace App\Infrastructure\Currency\DTO;

use App\Entity\App\Currency;
use Symfony\Component\Validator\Constraints as Assert;

class CurrencyRateDTO
{
    public string $code;
    public string $name;

    #[Assert\Positive]
    public int $rateToDefaultCurrency;

    public static function fromEntity(Currency $currency): self
    {
        $dto = new self();
        $dto->code = $currency->getCode();
        $dto->name = $currency->getName();
        $dto->rateToDefaultCurrency = $currency->getRateToDefaultCurrency();

        return $dto;
    }
}
