<?php

declare(strict_types=1);

namespace App\Infrastructure\Order\DTO;

use App\Entity\App\PaymentMethod;
use Symfony\Component\Validator\Constraints as Assert;

class OrderFormDTO
{
    /** @var OrderItemDTO[] */
    #[Assert\Count(min: 1, minMessage: 'Add at least one product to the order.')]
    #[Assert\Valid]
    public array $items;

    #[Assert\Valid]
    public DeliveryFormDTO $delivery;

    #[Assert\NotNull]
    public ?PaymentMethod $paymentMethod = null;

    public function __construct()
    {
        $this->items = [new OrderItemDTO()];
        $this->delivery = new DeliveryFormDTO();
    }
}
