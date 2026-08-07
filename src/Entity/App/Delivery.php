<?php

declare(strict_types=1);

namespace App\Entity\App;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'shop_order_delivery')]
class Delivery extends BaseEntity
{
    #[ORM\OneToOne(inversedBy: 'delivery')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Order $order;

    public function __construct(
        #[ORM\Column(length: 20)]
        private string $courier,
        #[ORM\Column(length: 255)]
        private string $recipientName,
        #[ORM\Column(length: 255)]
        private string $addressLine,
        #[ORM\Column(length: 20)]
        private string $postalCode,
        #[ORM\Column(length: 100)]
        private string $city,
    ) {
        parent::__construct();
    }

    public function setOrder(Order $order): void
    {
        $this->order = $order;
    }

    public function getCourier(): string
    {
        return $this->courier;
    }

    public function getRecipientName(): string
    {
        return $this->recipientName;
    }

    public function getAddressLine(): string
    {
        return $this->addressLine;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function getCity(): string
    {
        return $this->city;
    }
}
