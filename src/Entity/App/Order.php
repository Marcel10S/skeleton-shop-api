<?php

declare(strict_types=1);

namespace App\Entity\App;

use App\Infrastructure\Order\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: 'shop_order')]
class Order extends BaseEntity
{
    /** @var Collection<int, OrderItem> */
    #[ORM\OneToMany(mappedBy: 'order', targetEntity: OrderItem::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $items;

    #[ORM\OneToOne(mappedBy: 'order', targetEntity: Delivery::class, cascade: ['persist'], orphanRemoval: true)]
    private ?Delivery $delivery = null;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        parent::__construct();

        $this->items = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /** @return Collection<int, OrderItem> */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(OrderItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setOrder($this);
        }

        return $this;
    }

    public function setDelivery(Delivery $delivery): self
    {
        $this->delivery = $delivery;
        $delivery->setOrder($this);

        return $this;
    }

    public function getDelivery(): ?Delivery
    {
        return $this->delivery;
    }

    /** @return array<string, int> Amounts, in minor units, grouped by currency. */
    public function getTotalsByCurrency(): array
    {
        $totals = [];

        foreach ($this->items as $item) {
            $totals[$item->getCurrency()] = ($totals[$item->getCurrency()] ?? 0) + $item->getTotalAmount();
        }

        ksort($totals);

        return $totals;
    }

    public function getTotalInDefaultCurrency(): int
    {
        return $this->items->reduce(
            static fn (int $total, OrderItem $item): int => $total + $item->getTotalInDefaultCurrency(),
            0,
        );
    }
}
