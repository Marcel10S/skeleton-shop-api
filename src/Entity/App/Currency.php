<?php

declare(strict_types=1);

namespace App\Entity\App;

use App\Infrastructure\Currency\Repository\CurrencyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
class Currency extends BaseEntity
{
    public function __construct(
        #[ORM\Column(length: 3, unique: true)]
        private string $code,
        #[ORM\Column(length: 100)]
        private string $name,
        #[ORM\Column]
        private int $rateToDefaultCurrency,
        #[ORM\Column(type: 'boolean')]
        private bool $isDefault = false,
    ) {
        parent::__construct();

        $this->code = strtoupper($code);
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /** Default-currency minor units for one unit of this currency. */
    public function getRateToDefaultCurrency(): int
    {
        return $this->rateToDefaultCurrency;
    }

    public function setRateToDefaultCurrency(int $rateToDefaultCurrency): self
    {
        $this->rateToDefaultCurrency = $rateToDefaultCurrency;

        return $this;
    }

    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(bool $isDefault): self
    {
        $this->isDefault = $isDefault;

        return $this;
    }
}
