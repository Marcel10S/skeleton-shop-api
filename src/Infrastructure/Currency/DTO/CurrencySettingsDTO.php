<?php

declare(strict_types=1);

namespace App\Infrastructure\Currency\DTO;

use App\Entity\App\Currency;
use Symfony\Component\Validator\Constraints as Assert;

class CurrencySettingsDTO
{
    #[Assert\NotBlank(message: 'Wybierz walutę domyślną.')]
    public string $defaultCurrencyCode = '';

    /** @var CurrencyRateDTO[] */
    #[Assert\Count(min: 1)]
    #[Assert\Valid]
    public array $rates = [];

    /** @param Currency[] $currencies */
    public static function fromCurrencies(array $currencies): self
    {
        $dto = new self();

        foreach ($currencies as $currency) {
            $dto->rates[] = CurrencyRateDTO::fromEntity($currency);

            if ($currency->isDefault()) {
                $dto->defaultCurrencyCode = $currency->getCode();
            }
        }

        return $dto;
    }
}
