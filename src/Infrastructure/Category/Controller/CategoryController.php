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
        $data = array_map(fn(Category $c) => $this->serializeCategory($c), $categories);

        return $this->json($data);
    }

    #[Route('/{id}', name: 'single_by_id', methods: ['GET'])]
    public function categoryById(string $id): JsonResponse
    {
        $category = $this->categoryProvider->findOneById($id);

        if (!$category) {
            return $this->json(['error' => 'Category not found'], 404);
        }

        return $this->json($this->serializeCategory($category));
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request, CategoryCreate $handler): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $parent = !empty($data['parent_id']) ? $this->categoryProvider->findOneById($data['parent_id']) : null;

        $category = new Category(
            $data['name'],
            $data['description'],
            $parent,
        );

        $handler->create($category);

        return $this->json($this->serializeCategory($category), 201);
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

        return $this->json($this->serializeCategory($category));
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(string $id, CategoryDelete $handler): JsonResponse
    {
        $category = $this->categoryProvider->findOneById($id);
        $handler->delete($category);

        return $this->json(null, 204);
    }

    /**
     * Serialize Category entity to API response format
     * Excludes products to avoid circular references
     */
    private function serializeCategory(Category $category): array
    {
        return [
            'id' => $category->getId(),
            'name' => $category->getName(),
            'description' => $category->getDescription(),
        ];
    }
}
