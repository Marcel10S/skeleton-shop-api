<?php

declare(strict_types=1);

namespace App\Entity\App;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'shop_order_item')]
class OrderItem extends BaseEntity
{
    public function __construct(
        #[ORM\ManyToOne]
        #[ORM\JoinColumn(nullable: false, onDelete: 'RESTRICT')]
        private Product $product,
        #[ORM\Column(length: 255)]
        private string $productName,
        #[ORM\Column]
        #[Assert\Positive]
        private int $quantity,
        #[ORM\Column]
        private int $unitAmount,
        #[ORM\Column(length: 3)]
        private string $currency,
    ) {
        parent::__construct();
    }

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Order $order;

    public function setOrder(Order $order): void
    {
        $this->order = $order;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getUnitAmount(): int
    {
        return $this->unitAmount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getTotalAmount(): int
    {
        return $this->unitAmount * $this->quantity;
    }
}
