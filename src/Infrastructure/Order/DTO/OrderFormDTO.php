<?php

declare(strict_types=1);

namespace App\Infrastructure\Order\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class OrderFormDTO
{
    /** @var OrderItemDTO[] */
    #[Assert\Count(min: 1, minMessage: 'Add at least one product to the order.')]
    #[Assert\Valid]
    public array $items;

    public function __construct()
    {
        $this->items = [new OrderItemDTO()];
    }
}
