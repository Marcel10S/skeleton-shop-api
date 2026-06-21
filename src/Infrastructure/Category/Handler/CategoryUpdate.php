<?php

namespace App\Infrastructure\Category\Handler;

use App\Entity\App\Category;
use App\Infrastructure\Category\DTO\CategoryFormDTO;
use App\Infrastructure\Category\Repository\CategoryRepository;

class CategoryUpdate
{
    public function __construct(
        private readonly CategoryRepository $repository,
    ) {
    }

    public function updateByDTO(Category $category, CategoryFormDTO $dto): void
    {
        $category->setName($dto->name);
        $category->setDescription($dto->description);
        $category->setParent($category->getParent());

        $this->update($category);
    }

    public function update(Category $category): void
    {
        $this->repository->save($category);
    }
}
