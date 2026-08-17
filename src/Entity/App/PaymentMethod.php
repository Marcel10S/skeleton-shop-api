<?php

declare(strict_types=1);

namespace App\Entity\App;

use App\Infrastructure\PaymentMethod\Repository\PaymentMethodRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentMethodRepository::class)]
#[ORM\Table(name: 'payment_method')]
class PaymentMethod extends BaseEntity
{
    public function __construct(
        #[ORM\Column(length: 50, unique: true)]
        private string $code,
        #[ORM\Column(length: 100)]
        private string $name,
    ) {
        parent::__construct();
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
