<?php

declare(strict_types=1);

namespace App\Infrastructure\Delivery;

class DeliveryMethodProvider
{
    /** @return array<string, string> */
    public function getChoices(): array
    {
        return ['InPost' => 'inpost', 'DPD' => 'dpd'];
    }

    /** @return array<string, string> */
    public function findAll(): array
    {
        return $this->getChoices();
    }
}
