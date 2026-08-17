<?php

declare(strict_types=1);

namespace App\Entity\App;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'cart_item')]
class CartItem extends BaseEntity
{
    public function __construct(
        #[ORM\ManyToOne(inversedBy: 'items')]
        #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
        private Cart $cart,
        #[ORM\ManyToOne]
        #[ORM\JoinColumn(nullable: false)]
        private Product $product,
        #[ORM\Column]
        private int $quantity,
    ) { parent::__construct(); }

    public function getProduct(): Product { return $this->product; }
    public function getQuantity(): int { return $this->quantity; }
    public function setQuantity(int $quantity): void { $this->quantity = $quantity; }
    public function getTotalAmount(): int { return $this->product->getPrice()->getAmount() * $this->quantity; }
}
