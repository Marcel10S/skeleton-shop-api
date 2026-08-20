<?php

namespace App\Infrastructure\Product\Handler;

use App\Entity\App\Product;
use App\Infrastructure\Product\Repository\ProductRepository;
use App\Infrastructure\Product\DTO\ProductFormDTO;

class ProductCreate
{
    public function __construct(
        private readonly ProductRepository $productRepository,
    ) {}

    public function createByDTO(ProductFormDTO $dto): void
    {
        $product = new Product(
            category: $dto->category,
            name: $dto->name,
            stock: $dto->stock,
            price: $dto->price
        );
        $product->setDescription($dto->description);
        $product->setPriority($dto->priority);

        $this->create($product);
    }

    public function create(Product $product): void
    {
        $this->productRepository->save($product);
    }
}
