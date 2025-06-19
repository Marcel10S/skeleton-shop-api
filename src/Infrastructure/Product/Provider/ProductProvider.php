<?php

namespace App\Infrastructure\Product\Provider;

use App\Entity\Product;
use App\Infrastructure\Product\Repository\ProductQueryRepository;

class ProductProvider
{
    public function __construct(
        private readonly ProductQueryRepository $queryRepository
    ) {}

    public function findAll(): array
    {
        return $this->queryRepository->findAll();
    }

    public function findOneById(string $id): Product
    {
        return $this->queryRepository->find($id);
    }
}
