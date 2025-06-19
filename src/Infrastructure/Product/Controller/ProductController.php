<?php

namespace App\Infrastructure\Product\Controller;

use App\Entity\Product;
use App\Infrastructure\Product\Handler\ProductCreate;
use App\Infrastructure\Product\Handler\ProductRemove;
use App\Infrastructure\Product\Handler\ProductUpdate;
use App\Infrastructure\Product\Provider\ProductProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
#[Route('/api/products', name: 'api_products_')]
class ProductController extends AbstractController
{
    public function __construct(
        private readonly ProductProvider $productProvider,
    ) {}

    #[Route('', name: 'list', methods: ['GET'])]
    public function productList(): JsonResponse
    {
        $products = $this->productProvider->findAll();

        return $this->json($products);
    }

    #[Route('/{id}', name: 'single_by_id', methods: ['GET'])]
    public function productById(string $id): JsonResponse
    {
        return $this->json(
            $this->productProvider->findOneById($id)
        );
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request, ProductCreate $handler): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $product = new Product(
            $data['name'],
            $data['price'],
            $data['stock'],
        );
        $product->setDescription($data['description'] ?? null);

        $handler->create($product);
        return $this->json($product, 201);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(string $id, Request $request, ProductUpdate $handler): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $product = $this->productProvider->findOneById($id);

        $product->setName($data['name']);
        $product->setPrice($data['price']);
        $product->setStock($data['stock']);
        $product->setDescription($data['description'] ?? null);

        $handler->update($product);
        return $this->json($product);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(string $id, ProductRemove $handler): JsonResponse
    {
        $product = $this->productProvider->findOneById($id);
        $handler->remove($product);

        return $this->json(null, 204);
    }
}
