<?php

namespace App\Infrastructure\Product\Handler;

use App\Entity\App\Product;
use App\Infrastructure\Product\DTO\ProductFormDTO;
use App\Infrastructure\Product\Repository\ProductRepository;

class ProductUpdate
{
    public function __construct(
        private readonly ProductRepository $productRepository,
    ) {}

    public function updateByDTO(Product $product, ProductFormDTO $dto): void
    {
        $product->setName($dto->name);
        $product->setDescription($dto->description);
        $product->setStock($dto->stock);
        $product->setPrice($dto->price);
        $product->setIsActive($dto->isActive);

        $this->update($product);
    }

    public function update(Product $product): void
    {
        $this->productRepository->save($product);
    }
}
