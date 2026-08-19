<?php

namespace App\Infrastructure\Product\Controller;

use App\Entity\App\Product;
use App\Entity\Embeddable\Money;
use App\Infrastructure\Category\Provider\CategoryProvider;
use App\Infrastructure\Product\Handler\ProductCreate;
use App\Infrastructure\Product\Handler\ProductRemove;
use App\Infrastructure\Product\Handler\ProductUpdate;
use App\Infrastructure\Product\Provider\ProductProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
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
        $data = array_map(fn(Product $p) => $this->serializeProduct($p), $products);

        return $this->json($data);
    }

    #[Route('/{id}', name: 'single_by_id', methods: ['GET'])]
    public function productById(string $id): JsonResponse
    {
        $product = $this->productProvider->findOneById($id);

        if (!$product) {
            return $this->json(['error' => 'Product not found'], 404);
        }

        return $this->json($this->serializeProduct($product));
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request, ProductCreate $handler, CategoryProvider $categoryProvider): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $product = new Product(
            $categoryProvider->findOneById($data['category']),
            $data['name'],
            $data['stock'],
            new Money((int)($data['amount'] * 100), $data['currency']),
        );
        $product->setDescription($data['description'] ?? null);

        $handler->create($product);

        return $this->json($this->serializeProduct($product), 201);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(string $id, Request $request, ProductUpdate $handler, CategoryProvider $categoryProvider): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $product = $this->productProvider->findOneById($id);

        $product->setCategory($categoryProvider->findOneById($data['category']));
        $product->setName($data['name']);
        $product->setStock($data['stock']);
        $product->setPrice(new Money((int)($data['amount'] * 100), $data['currency']));
        $product->setDescription($data['description'] ?? null);

        $handler->update($product);

        return $this->json($this->serializeProduct($product));
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(string $id, ProductRemove $handler): JsonResponse
    {
        $product = $this->productProvider->findOneById($id);
        $handler->remove($product);

        return $this->json(null, 204);
    }

    /**
     * Serialize Product entity to API response format
     */
    private function serializeProduct(Product $product): array
    {
        return [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'stock' => $product->getStock(),
            'isActive' => $product->isActive(),
            'price' => $this->serializePrice($product->getPrice()),
            'category' => $product->getCategory() ? $this->serializeCategory($product->getCategory()) : null,
        ];
    }

    /**
     * Serialize Money value object to API format
     * Converts INT cents to decimal amount (e.g., 9999 => 99.99)
     */
    private function serializePrice(Money $money): array
    {
        return [
            'amount' => $money->getAmount() / 100,
            'currency' => $money->getCurrency(),
        ];
    }

    /**
     * Serialize Category to API format (simplified, without products)
     */
    private function serializeCategory($category): array
    {
        return [
            'id' => $category->getId(),
            'name' => $category->getName(),
        ];
    }
}
