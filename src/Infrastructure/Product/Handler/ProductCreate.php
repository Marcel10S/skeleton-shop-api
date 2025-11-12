<?php

namespace App\Infrastructure\Product\Handler;

use App\Entity\App\Product;
use App\Infrastructure\Product\Repository\ProductRepository;

class ProductCreate
{
    public function __construct(
        private readonly ProductRepository $productRepository,
    ) {}

    public function create(Product $product): void
    {
        $this->productRepository->save($product);
    }
}
