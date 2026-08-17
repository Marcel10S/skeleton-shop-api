<?php

declare(strict_types=1);

namespace App\UI\Cart\Controller;

use App\Infrastructure\Cart\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class CartController extends AbstractController
{
    #[Route('carts/', name: 'shop_carts_active_list', methods: ['GET'])]
    public function activeList(CartRepository $repository): Response
    {
        return $this->render('@ui/Cart/View/active_list.html.twig', ['carts' => $repository->findAllActive()]);
    }
}
