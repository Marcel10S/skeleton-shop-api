<?php


namespace App\UI\Category\Controller;

use App\Entity\App\Category;
use App\Infrastructure\Category\DTO\CategoryFormDTO;
use App\Infrastructure\Category\Handler\CategoryCreate;
use App\Infrastructure\Category\Handler\CategoryDelete;
use App\Infrastructure\Category\Handler\CategoryUpdate;
use App\Infrastructure\Category\Provider\CategoryProvider;
use App\Infrastructure\Product\Provider\ProductProvider;
use App\UI\Category\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
#[Route('categories/', name: 'shop_categories_')]
class CategoryController extends AbstractController
{
    #[Route('new', name: 'create')]
    public function create(
        Request       $request,
        CategoryCreate $handler
    ): Response
    {
        $form = $this->createForm(
            CategoryType::class,
            new CategoryFormDTO(),
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dto = $form->getData();
            $handler->createByDTO($dto);

            return $this->redirectToRoute('shop_categories_list');
        }

        return $this->render('@ui/Category/View/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('{id}/edit', name: 'edit')]
    public function edit(
        Category       $category,
        Request       $request,
        CategoryUpdate $handler
    ): Response
    {
        $dto = CategoryFormDTO::fromEntity($category);
        $form = $this->createForm(CategoryType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $handler->updateByDTO($category, $dto);

            return $this->redirectToRoute('shop_categories_list');
        }

        return $this->render('@ui/Category/View/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('', name: 'list')]
    public function list(CategoryProvider $provider): Response
    {
        return $this->render('@ui/Category/View/list.html.twig', [
            'tree' => $provider->findAllInTree(),
        ]);
    }

    #[Route('{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(
        Category       $category,
        Request       $request,
        CategoryDelete $handler,
    ): Response
    {
        if (!$this->isCsrfTokenValid(
            'delete_category_' . $category->getId(),
            $request->request->get('_token')
        )) {
            throw $this->createAccessDeniedException();
        }

        $handler->delete($category);

        $this->addFlash('success', 'Kategoria została usunięta.');
        return $this->redirectToRoute('shop_category_list');
    }
}
