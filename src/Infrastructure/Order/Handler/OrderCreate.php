<?php

declare(strict_types=1);

namespace App\Infrastructure\Order\Handler;

use App\Entity\App\Order;
use App\Entity\App\OrderItem;
use App\Entity\App\Delivery;
use App\Entity\App\Product;
use App\Infrastructure\Order\Exception\OutOfStockException;
use App\Infrastructure\Currency\Provider\CurrencyProvider;
use App\Infrastructure\Order\DTO\OrderFormDTO;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;

class OrderCreate
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CurrencyProvider $currencyProvider,
    )
    {
    }

    public function createByDTO(OrderFormDTO $dto): void
    {
        $this->entityManager->getConnection()->transactional(function () use ($dto): void {
            $order = new Order();
            $order->setPaymentMethod($dto->paymentMethod);

            foreach ($dto->items as $item) {
                /** @var Product $product */
                $product = $this->entityManager->find(Product::class, $item->product->getId(), LockMode::PESSIMISTIC_WRITE);
                if (!$product->isActive() || $product->getStock() < $item->quantity) {
                    throw new OutOfStockException(sprintf('Produkt „%s” nie jest już dostępny w wybranej ilości.', $product->getName()));
                }

                $price = $product->getPrice();
                $currency = $this->currencyProvider->findOneByCode($price->getCurrency());
                $order->addItem(new OrderItem($product, $product->getName(), $item->quantity, $price->getAmount(), $price->getCurrency(), $currency->getRateToDefaultCurrency()));
                $product->setStock($product->getStock() - $item->quantity);
            }

            $order->setDelivery(new Delivery($dto->delivery->courier, $dto->delivery->recipientName, $dto->delivery->addressLine, $dto->delivery->postalCode, $dto->delivery->city));
            $this->entityManager->persist($order);
            $this->entityManager->flush();
        });
    }
}
