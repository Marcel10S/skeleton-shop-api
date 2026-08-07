<?php

declare(strict_types=1);

namespace App\Infrastructure\Order\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class DeliveryFormDTO
{
    #[Assert\Choice(['inpost', 'dpd'])]
    public string $courier = 'inpost';

    #[Assert\NotBlank]
    public string $recipientName = '';

    #[Assert\NotBlank]
    public string $addressLine = '';

    #[Assert\NotBlank]
    #[Assert\Regex('/^\d{2}-\d{3}$/', message: 'Podaj kod pocztowy w formacie 00-000.')]
    public string $postalCode = '';

    #[Assert\NotBlank]
    public string $city = '';
}
