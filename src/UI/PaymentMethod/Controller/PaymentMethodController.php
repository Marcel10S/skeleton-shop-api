<?php

declare(strict_types=1);

namespace App\UI\PaymentMethod\Controller;

use App\Infrastructure\PaymentMethod\Provider\PaymentMethodProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('payment-methods/', name: 'shop_payment_methods_')]
class PaymentMethodController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(PaymentMethodProvider $provider): Response
    {
        return $this->render('@ui/PaymentMethod/View/list.html.twig', [
            'paymentMethods' => $provider->findAll(),
        ]);
    }
}
