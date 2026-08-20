<?php

namespace App\Infrastructure\Product\DTO;

use App\Entity\App\Category;
use App\Entity\App\Product;
use App\Entity\Embeddable\Money;
use Symfony\Component\Validator\Constraints as Assert;

class ProductFormDTO
{
    public ?Category $category = null;
    public string $name = '';
    public ?string $description = '';
    public int $stock = 0;
    public ?Money $price = null;
    public bool $isActive = false;
    #[Assert\Range(min: 1, max: 100)]
    public int $priority = 1;

    public static function fromEntity(Product $product): self
    {
        $dto = new self();

        $dto->category = $product->getCategory();
        $dto->name = $product->getName();
        $dto->description = $product->getDescription() ?? null;
        $dto->stock = $product->getStock();
        $dto->price = new Money(
            $product->getPrice()->getAmount(),
            $product->getPrice()->getCurrency()
        );
        $dto->isActive = $product->isActive();
        $dto->priority = $product->getPriority();

        return $dto;
    }
}
