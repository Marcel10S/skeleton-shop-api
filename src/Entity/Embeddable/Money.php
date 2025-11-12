<?php

declare(strict_types=1);

namespace App\Entity\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Money
{
    public function __construct(
        #[ORM\Column(type: 'integer')]
        private int $amount,
        #[ORM\Column(length: 3)]
        private string $currency,
    ) {
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
