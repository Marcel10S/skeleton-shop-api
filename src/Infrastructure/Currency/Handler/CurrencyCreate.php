<?php

declare(strict_types=1);

namespace App\Infrastructure\Currency\Handler;

use App\Entity\App\Currency;
use App\Infrastructure\Currency\DTO\CurrencyFormDTO;
use App\Infrastructure\Currency\Repository\CurrencyRepository;

class CurrencyCreate
{
    public function __construct(private readonly CurrencyRepository $repository)
    {
    }

    public function createByDTO(CurrencyFormDTO $dto): void
    {
        $this->repository->save(new Currency($dto->code, $dto->name, 100));
    }
}
