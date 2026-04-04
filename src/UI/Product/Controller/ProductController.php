<?php

namespace App\UI\Product\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
#[Route('/shop/products', name: 'shop_products_')]
class ProductController extends AbstractController
{
//    #[Route('', name: 'list', methods: ['GET'])]
//    public function productList(): JsonResponse
//    {
//
//    }
//
//    #[Route('', name: 'create', methods: ['POST'])]
//    public function create(Request $request, ProductCreate $handler): JsonResponse
//    {
//
//    }
//
//    #[Route('/{id}', name: 'update', methods: ['PUT'])]
//    public function update(string $id, Request $request, ProductUpdate $handler): JsonResponse
//    {
//
//    }
}
