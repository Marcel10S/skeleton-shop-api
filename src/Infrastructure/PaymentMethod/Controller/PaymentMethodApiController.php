<?php

declare(strict_types=1);

namespace App\Infrastructure\PaymentMethod\Controller;

use App\Entity\App\PaymentMethod;
use App\Infrastructure\PaymentMethod\Provider\PaymentMethodProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/api/payment-methods', name: 'api_payment_methods_')]
class PaymentMethodApiController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(PaymentMethodProvider $provider): JsonResponse
    {
        return $this->json(array_map(
            static fn (PaymentMethod $method): array => [
                'id' => $method->getId(),
                'code' => $method->getCode(),
                'name' => $method->getName(),
            ],
            $provider->findAll(),
        ));
    }
}
