<?php

namespace App\Infrastructure\Category\Handler;

use App\Entity\App\Category;
use App\Infrastructure\Category\DTO\CategoryFormDTO;
use App\Infrastructure\Category\Repository\CategoryRepository;

class CategoryCreate
{
    public function __construct(
        private readonly CategoryRepository $repository,
    ) {
    }

    public function createByDTO(CategoryFormDTO $dto): void
    {
        $category = new Category(
            name: $dto->name,
            description: $dto->description,
            parent: $dto->parent,
        );

        $this->create($category);
    }

    public function create(Category $category): void
    {
        $this->repository->save($category);
    }
}
