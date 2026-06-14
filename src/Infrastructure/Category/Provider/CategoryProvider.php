<?php


namespace App\Infrastructure\Category\Provider;

use App\Entity\App\Category;
use App\Infrastructure\Category\Repository\CategoryQueryRepository;
use Doctrine\ORM\EntityNotFoundException;

class CategoryProvider
{
    public function __construct(
        private readonly CategoryQueryRepository $queryRepository
    )
    {
    }

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
}
