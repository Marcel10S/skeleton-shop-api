<?php

declare(strict_types=1);

namespace App\Infrastructure\Currency\DTO;

use App\Entity\App\Currency;
use Symfony\Component\Validator\Constraints as Assert;

class CurrencyFormDTO
{
    #[Assert\NotBlank]
    #[Assert\Regex('/^[A-Za-z]{3}$/', message: 'Use a three-letter currency code, for example EUR.')]
    public string $code = '';

    #[Assert\NotBlank]
    public string $name = '';

    #[Assert\Positive]
    public int $rateToDefaultCurrency = 100;

    public bool $isDefault = false;

    public static function fromEntity(Currency $currency): self
    {
        $dto = new self();
        $dto->code = $currency->getCode();
        $dto->name = $currency->getName();
        $dto->rateToDefaultCurrency = $currency->getRateToDefaultCurrency();
        $dto->isDefault = $currency->isDefault();

        return $dto;
    }
}
