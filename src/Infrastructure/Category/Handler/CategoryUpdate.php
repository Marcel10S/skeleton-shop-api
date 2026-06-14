<?php

namespace App\Infrastructure\Category\Handler;

use App\Entity\App\Category;
use App\Infrastructure\Category\Repository\CategoryRepository;

class CategoryUpdate
{
    public function __construct(
        private readonly CategoryRepository $repository,
    ) {
    }

    public function handle(Category $category): void
    {
        $this->repository->save($category);
    }
}
