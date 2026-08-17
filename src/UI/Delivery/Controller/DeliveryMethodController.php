<?php

declare(strict_types=1);

namespace App\UI\Delivery\Controller;

use App\Infrastructure\Delivery\DeliveryMethodProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('delivery-methods/', name: 'shop_delivery_methods_')]
class DeliveryMethodController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(DeliveryMethodProvider $provider): Response
    {
        return $this->render('@ui/Delivery/View/list.html.twig', ['deliveryMethods' => $provider->findAll()]);
    }
}
