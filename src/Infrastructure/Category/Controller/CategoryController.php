<?php

namespace App\Infrastructure\Category\Controller;

use App\Entity\App\Category;
use App\Infrastructure\Category\Handler\CategoryCreate;
use App\Infrastructure\Category\Handler\CategoryDelete;
use App\Infrastructure\Category\Handler\CategoryUpdate;
use App\Infrastructure\Category\Provider\CategoryProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpFoundation\JsonResponse;

#[AsController]
#[Route('/api/categories')]
class CategoryController extends AbstractController
{
    public function __construct(
        private readonly CategoryProvider $categoryProvider,
    ) {}

    #[Route('', name: 'list', methods: ['GET'])]
    public function categoryList(): JsonResponse
    {
        $categories = $this->categoryProvider->findAll();

        return $this->json($categories);
    }

    #[Route('/{id}', name: 'single_by_id', methods: ['GET'])]
    public function categoryById(string $id): JsonResponse
    {
        return $this->json(
            $this->categoryProvider->findOneById($id)
        );
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request, CategoryCreate $handler): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!empty($data['parent_id'])) {
            $parent = $this->categoryProvider->findOneById($data['parent_id']);
        }

        $category = new Category(
            $data['name'],
            $data['description'],
            $parent ?? null,
        );

        $handler->create($category);
        return $this->json($category, 201);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(string $id, Request $request, CategoryUpdate $handler): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $category = $this->categoryProvider->findOneById($id);

        if (!empty($data['parent_id'])) {
            $category->setParent($this->categoryProvider->findOneById($data['parent_id']));
        }

        $category->setName($data['name']);
        $category->setDescription($data['description']);

        $handler->update($category);
        return $this->json($category);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(string $id, CategoryDelete $handler): JsonResponse
    {
        $category = $this->categoryProvider->findOneById($id);
        $handler->delete($category);

        return $this->json(null, 204);
    }
}
