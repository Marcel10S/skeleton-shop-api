<?php

namespace App\Infrastructure\Product\Provider;

use App\Entity\App\Product;
use App\Infrastructure\Product\Repository\ProductQueryRepository;
use Doctrine\ORM\EntityNotFoundException;

class ProductProvider
{
    public function __construct(
        private readonly ProductQueryRepository $queryRepository
    ) {}

    public function findAll(): array
    {
        return $this->queryRepository->findBy([], ['priority' => 'DESC', 'name' => 'ASC']);
    }

    public function findOneById(string $id): Product
    {
        $product = $this->queryRepository->find($id);
        if (null === $product) {
            throw new EntityNotFoundException();
        }

        return $product;
    }
}
