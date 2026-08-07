<?php

declare(strict_types=1);

namespace App\Infrastructure\Order\Provider;

use App\Infrastructure\Order\Repository\OrderQueryRepository;

class OrderProvider
{
    public function __construct(private readonly OrderQueryRepository $queryRepository)
    {
    }

    public function findAll(): array
    {
        return $this->queryRepository->findAllNewestFirst();
    }
}
