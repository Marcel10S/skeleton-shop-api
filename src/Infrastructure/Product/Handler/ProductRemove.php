<?php

namespace App\Infrastructure\Product\Handler;

use App\Entity\Product;
use App\Infrastructure\Product\Repository\ProductRepository;

class ProductRemove
{
    public function __construct(
        private readonly ProductRepository $productRepository,
    ) {}

    public function remove(Product $product): void
    {
        $this->productRepository->remove($product);
    }
}
