<?php

namespace App\Infrastructure\Product\Handler;

use App\Entity\Product;
use App\Infrastructure\Product\Repository\ProductRepository;

class ProductUpdate
{
    public function __construct(
        private readonly ProductRepository $productRepository,
    ) {}

    public function update(Product $product): void
    {
        $this->productRepository->save($product);
    }
}
