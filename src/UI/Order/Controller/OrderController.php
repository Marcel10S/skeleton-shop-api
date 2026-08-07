<?php

declare(strict_types=1);

namespace App\UI\Order\Controller;

use App\Infrastructure\Order\DTO\OrderFormDTO;
use App\Infrastructure\Order\Handler\OrderCreate;
use App\Infrastructure\Order\Provider\OrderProvider;
use App\UI\Order\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('orders/', name: 'shop_orders_')]
class OrderController extends AbstractController
{
    #[Route('new', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request, OrderCreate $handler): Response
    {
        $form = $this->createForm(OrderType::class, new OrderFormDTO());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $handler->createByDTO($form->getData());

            return $this->redirectToRoute('shop_orders_list');
        }

        return $this->render('@ui/Order/View/form.html.twig', ['form' => $form->createView()]);
    }

    #[Route('', name: 'list', methods: ['GET'])]
    public function list(OrderProvider $provider): Response
    {
        $orders = $provider->findAll();

        return $this->render('@ui/Order/View/list.html.twig', ['orders' => $orders]);
    }
}
