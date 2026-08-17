<?php

declare(strict_types=1);

namespace App\Infrastructure\PaymentMethod\Provider;

use App\Infrastructure\PaymentMethod\Repository\PaymentMethodRepository;

class PaymentMethodProvider
{
    public function __construct(private readonly PaymentMethodRepository $repository)
    {
    }

    public function findAll(): array
    {
        return $this->repository->findAllOrdered();
    }
}
