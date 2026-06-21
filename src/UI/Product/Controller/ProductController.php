<?php

namespace App\UI\Product\Controller;

use App\Entity\App\Product;
use App\Infrastructure\Product\DTO\ProductFormDTO;
use App\Infrastructure\Product\Handler\ProductCreate;
use App\Infrastructure\Product\Handler\ProductRemove;
use App\Infrastructure\Product\Handler\ProductUpdate;
use App\Infrastructure\Product\Provider\ProductProvider;
use App\UI\Product\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
#[Route('products/', name: 'shop_products_')]
class ProductController extends AbstractController
{
    #[Route('new', name: 'create')]
    public function create(
        Request $request,
        ProductCreate $handler
    ): Response {
        $form = $this->createForm(
            ProductType::class,
            new ProductFormDTO()
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dto = $form->getData();
            $handler->createByDTO($dto);

            return $this->redirectToRoute('shop_products_list');
        }

        return $this->render('@ui/Product/View/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('{id}/edit', name: 'edit')]
    public function edit(
        Product $product,
        Request $request,
        ProductUpdate $handler
    ): Response {
        $dto = ProductFormDTO::fromEntity($product);
        $form = $this->createForm(ProductType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $handler->updateByDTO($product, $dto);

            return $this->redirectToRoute('shop_products_list');
        }

        return $this->render('@ui/Product/View/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('', name: 'list')]
    public function list(ProductProvider $provider): Response
    {
        $products = $provider->findAll();

        return $this->render('@ui/Product/View/list.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(
        Product $product,
        Request $request,
        ProductRemove $handler,
    ): Response {
        if (!$this->isCsrfTokenValid(
            'delete_product_'.$product->getId(),
            $request->request->get('_token')
        )) {
            throw $this->createAccessDeniedException();
        }

        $handler->remove($product);

        $this->addFlash('success', 'Product deleted.');
        return $this->redirectToRoute('shop_products_list');
    }
}
