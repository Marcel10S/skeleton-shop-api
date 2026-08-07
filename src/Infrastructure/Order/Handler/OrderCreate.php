<?php

declare(strict_types=1);

namespace App\Infrastructure\Order\Handler;

use App\Entity\App\Order;
use App\Entity\App\OrderItem;
use App\Infrastructure\Order\DTO\OrderFormDTO;
use App\Infrastructure\Order\Repository\OrderRepository;

class OrderCreate
{
    public function __construct(private readonly OrderRepository $repository)
    {
    }

    public function createByDTO(OrderFormDTO $dto): void
    {
        $order = new Order();

        foreach ($dto->items as $item) {
            $product = $item->product;
            $price = $product->getPrice();

            $order->addItem(new OrderItem(
                product: $product,
                productName: $product->getName(),
                quantity: $item->quantity,
                unitAmount: $price->getAmount(),
                currency: $price->getCurrency(),
            ));
        }

        $this->repository->save($order);
    }
}
