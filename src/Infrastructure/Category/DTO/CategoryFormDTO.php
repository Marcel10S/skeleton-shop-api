<?php

namespace App\Infrastructure\Category\DTO;

use App\Entity\App\Category;

class CategoryFormDTO
{
    public ?Category $parent = null;
    public string $name = '';
    public string $description = '';


    public static function fromEntity(Category $category): self
    {
        $dto = new self();

        $dto->parent = $category->getParent();
        $dto->name = $category->getName();
        $dto->description = $category->getDescription();

        return $dto;
    }
}
