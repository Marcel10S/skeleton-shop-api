<?php

namespace App\Infrastructure\Category\DTO;

use App\Entity\App\Category;

final class CategoryTreeNodeDTO
{
    /**
     * @param CategoryTreeNodeDTO[] $children
     */
    public function __construct(
        public Category $category,
        public array $children = [],
        public int $depth = 0,
    ) {
    }
}
