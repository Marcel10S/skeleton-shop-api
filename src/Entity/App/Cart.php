<?php

declare(strict_types=1);

namespace App\Entity\App;

use App\Infrastructure\Cart\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
#[ORM\Table(name: 'cart')]
class Cart extends BaseEntity
{
    /** @var Collection<int, CartItem> */
    #[ORM\OneToMany(mappedBy: 'cart', targetEntity: CartItem::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $items;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?PaymentMethod $paymentMethod = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $courier = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $recipientName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $addressLine = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $postalCode = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $city = null;

    public function __construct(#[ORM\Column(length: 64, unique: true)] private string $token)
    {
        parent::__construct();
        $this->items = new ArrayCollection();
    }

    public function getToken(): string { return $this->token; }

    /** @return Collection<int, CartItem> */
    public function getItems(): Collection { return $this->items; }

    public function addProduct(Product $product, int $quantity): void
    {
        foreach ($this->items as $item) {
            if ($item->getProduct()->getId()->equals($product->getId())) {
                $item->setQuantity($item->getQuantity() + $quantity);
                return;
            }
        }

        $this->items->add(new CartItem($this, $product, $quantity));
    }

    public function removeItem(CartItem $item): void { $this->items->removeElement($item); }
    public function isEmpty(): bool { return $this->items->isEmpty(); }
    public function getPaymentMethod(): ?PaymentMethod { return $this->paymentMethod; }
    public function setPaymentMethod(?PaymentMethod $paymentMethod): void { $this->paymentMethod = $paymentMethod; }
    public function setDelivery(string $courier, string $recipientName, string $addressLine, string $postalCode, string $city): void
    {
        $this->courier = $courier;
        $this->recipientName = $recipientName;
        $this->addressLine = $addressLine;
        $this->postalCode = $postalCode;
        $this->city = $city;
    }
    public function getCourier(): ?string { return $this->courier; }
    public function getRecipientName(): ?string { return $this->recipientName; }
    public function getAddressLine(): ?string { return $this->addressLine; }
    public function getPostalCode(): ?string { return $this->postalCode; }
    public function getCity(): ?string { return $this->city; }

    /** @return array<string, int> */
    public function getTotalsByCurrency(): array
    {
        $totals = [];
        foreach ($this->items as $item) {
            $currency = $item->getProduct()->getPrice()->getCurrency();
            $totals[$currency] = ($totals[$currency] ?? 0) + $item->getTotalAmount();
        }
        ksort($totals);
        return $totals;
    }
}
