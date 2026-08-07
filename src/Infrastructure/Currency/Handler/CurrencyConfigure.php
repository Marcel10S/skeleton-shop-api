<?php

declare(strict_types=1);

namespace App\Infrastructure\Currency\Handler;

use App\Infrastructure\Currency\DTO\CurrencySettingsDTO;
use App\Infrastructure\Currency\Repository\CurrencyRepository;

class CurrencyConfigure
{
    public function __construct(private readonly CurrencyRepository $repository)
    {
    }

    public function updateByDTO(CurrencySettingsDTO $dto): void
    {
        $this->repository->saveConfiguration($dto->defaultCurrencyCode, $dto->rates);
    }
}
