<?php

declare(strict_types=1);

namespace App\Infrastructure\Order\Controller;

use App\Entity\App\PaymentMethod;
use App\Entity\App\Product;
use App\Infrastructure\Order\DTO\DeliveryFormDTO;
use App\Infrastructure\Order\DTO\OrderFormDTO;
use App\Infrastructure\Order\DTO\OrderItemDTO;
use App\Infrastructure\Order\Exception\OutOfStockException;
use App\Infrastructure\Order\Handler\OrderCreate;
use App\Infrastructure\Order\Repository\OrderQueryRepository;
use App\Infrastructure\Currency\Provider\CurrencyProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsController]
#[Route('/api/orders', name: 'api_orders_')]
class OrderApiController extends AbstractController
{
    #[Route('', name: 'create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        OrderCreate $handler,
        ValidatorInterface $validator,
        CurrencyProvider $currencyProvider,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        if (!is_array($data)) {
            return $this->json(['error' => 'Nieprawidłowy format danych.'], 400);
        }

        try {
            $dto = $this->createOrderDTO($data, $entityManager);
        } catch (\InvalidArgumentException $exception) {
            return $this->json(['error' => $exception->getMessage()], 400);
        }

        $violations = $validator->validate($dto);
        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[] = $violation->getMessage();
            }

            return $this->json(['error' => 'Dane zamówienia są nieprawidłowe.', 'details' => $errors], 422);
        }

        try {
            $order = $handler->createByDTO($dto);
        } catch (OutOfStockException $exception) {
            return $this->json(['error' => $exception->getMessage()], 409);
        }

        return $this->json([
            'id' => (string) $order->getId(),
            'orderNumber' => $order->getOrderNumber(),
            'status' => $order->getPaymentStatus(),
            'createdAt' => $order->getCreatedAt()->format(DATE_ATOM),
            'total' => $this->serializeTotal($order, $currencyProvider),
        ], 201);
    }

    #[Route('/{orderNumber}', name: 'find_by_number', methods: ['GET'])]
    public function findByNumber(
        string $orderNumber,
        OrderQueryRepository $repository,
        CurrencyProvider $currencyProvider,
    ): JsonResponse {
        $order = $repository->findOneByOrderNumber($orderNumber);

        if (!$order) {
            return $this->json(['error' => 'Nie znaleziono zamówienia o podanym numerze.'], 404);
        }

        $total = $this->serializeTotal($order, $currencyProvider);

        return $this->json([
            'id' => (string) $order->getId(),
            'orderNumber' => $order->getOrderNumber(),
            'status' => $order->getPaymentStatus(),
            'createdAt' => $order->getCreatedAt()->format(DATE_ATOM),
            'total' => $total,
            'items' => array_map(static fn ($item): array => [
                'name' => $item->getProductName(),
                'quantity' => $item->getQuantity(),
                'unitAmount' => $item->getUnitAmount() / 100,
                'currency' => $item->getCurrency(),
            ], $order->getItems()->toArray()),
            'delivery' => $order->getDelivery() ? [
                'courier' => $order->getDelivery()->getCourier(),
                'city' => $order->getDelivery()->getCity(),
            ] : null,
            'paymentMethod' => $order->getPaymentMethod()->getName(),
        ]);
    }

    private function serializeTotal($order, CurrencyProvider $currencyProvider): array
    {
        $currencyCode = $order->getDefaultCurrencyCode() ?: $currencyProvider->findDefault()->getCode();

        return [
            'amount' => $order->getTotalInDefaultCurrency() / 100,
            'currency' => $currencyCode,
        ];
    }

    private function createOrderDTO(array $data, EntityManagerInterface $entityManager): OrderFormDTO
    {
        if (!isset($data['items']) || !is_array($data['items']) || $data['items'] === []) {
            throw new \InvalidArgumentException('Zamówienie musi zawierać co najmniej jeden produkt.');
        }

        $dto = new OrderFormDTO();
        $dto->items = [];

        foreach ($data['items'] as $itemData) {
            $productId = $itemData['product'] ?? null;
            $quantity = $itemData['quantity'] ?? null;
            $product = is_string($productId) ? $entityManager->find(Product::class, $productId) : null;

            if (!$product || !is_int($quantity)) {
                throw new \InvalidArgumentException('Produkt lub ilość w koszyku są nieprawidłowe.');
            }

            $item = new OrderItemDTO();
            $item->product = $product;
            $item->quantity = $quantity;
            $dto->items[] = $item;
        }

        $paymentMethodId = $data['paymentMethod'] ?? null;
        $paymentMethod = is_string($paymentMethodId)
            ? $entityManager->find(PaymentMethod::class, $paymentMethodId)
            : null;
        if (!$paymentMethod) {
            throw new \InvalidArgumentException('Wybrana metoda płatności nie istnieje.');
        }
        $dto->paymentMethod = $paymentMethod;

        $deliveryData = $data['delivery'] ?? [];
        $delivery = new DeliveryFormDTO();
        $delivery->courier = (string) ($deliveryData['courier'] ?? 'inpost');
        $delivery->recipientName = (string) ($deliveryData['recipientName'] ?? '');
        $delivery->addressLine = (string) ($deliveryData['addressLine'] ?? '');
        $delivery->postalCode = (string) ($deliveryData['postalCode'] ?? '');
        $delivery->city = (string) ($deliveryData['city'] ?? '');
        $dto->delivery = $delivery;

        return $dto;
    }
}
