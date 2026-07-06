<?php


namespace App\Infrastructure\Category\Provider;

use App\Entity\App\Category;
use App\Infrastructure\Category\DTO\CategoryTreeNodeDTO;
use App\Infrastructure\Category\Repository\CategoryQueryRepository;
use Doctrine\ORM\EntityNotFoundException;

class CategoryProvider
{
    public function __construct(
        private readonly CategoryQueryRepository $queryRepository
    ) {}

    public function findAll(): array
    {
        return $this->queryRepository->findAll();
    }

    public function findOneById(string $id): Category
    {
        $category = $this->queryRepository->find($id);
        if (null === $category) {
            throw new EntityNotFoundException();
        }

        return $category;
    }

    public function findAllInTree(): array
    {
        $roots = [];
        $nodes = [];
        $categories = $this->queryRepository->findAllOrdered();

        foreach ($categories as $category) {
            $nodes[$category->getId()->toRfc4122()] = new CategoryTreeNodeDTO($category);
        }

        foreach ($nodes as $node) {

            $parent = $node->category->getParent();
            if ($parent === null) {
                $roots[] = $node;
                continue;
            }

            $parentNode = $nodes[$parent->getId()->toRfc4122()];
            $node->depth = $parentNode->depth + 1;
            $parentNode->children[] = $node;
        }

        return $roots;
    }
}
