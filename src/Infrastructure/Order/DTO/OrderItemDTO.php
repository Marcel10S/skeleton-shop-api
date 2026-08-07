<?php

declare(strict_types=1);

namespace App\Infrastructure\Order\DTO;

use App\Entity\App\Product;
use Symfony\Component\Validator\Constraints as Assert;

class OrderItemDTO
{
    #[Assert\NotNull]
    public ?Product $product = null;

    #[Assert\Positive]
    public int $quantity = 1;
}
