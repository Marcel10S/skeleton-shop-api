<?php

declare(strict_types=1);

namespace App\Infrastructure\Order\Handler;

use App\Entity\App\Order;
use App\Entity\App\OrderItem;
use App\Infrastructure\Currency\Provider\CurrencyProvider;
use App\Infrastructure\Order\DTO\OrderFormDTO;
use App\Infrastructure\Order\Repository\OrderRepository;

class OrderCreate
{
    public function __construct(
        private readonly OrderRepository $repository,
        private readonly CurrencyProvider $currencyProvider,
    )
    {
    }

    public function createByDTO(OrderFormDTO $dto): void
    {
        $order = new Order();

        foreach ($dto->items as $item) {
            $product = $item->product;
            $price = $product->getPrice();
            $currency = $this->currencyProvider->findOneByCode($price->getCurrency());

            $order->addItem(new OrderItem(
                product: $product,
                productName: $product->getName(),
                quantity: $item->quantity,
                unitAmount: $price->getAmount(),
                currency: $price->getCurrency(),
                rateToDefaultCurrency: $currency->getRateToDefaultCurrency(),
            ));
        }

        $this->repository->save($order);
    }
}
