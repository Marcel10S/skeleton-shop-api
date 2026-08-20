<?php

namespace App\Entity\App;

use App\Entity\Embeddable\Money;
use App\Infrastructure\Product\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product extends BaseEntity
{
    #[ORM\Column]
    #[Assert\Range(min: 1, max: 100)]
    private int $priority = 1;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive = true;

    #[ORM\Embedded(class: Money::class)]
    private Money $price;

    public function __construct(
        #[ORM\ManyToOne(
            targetEntity: Category::class,
            inversedBy: 'products'
        )]
        #[ORM\JoinColumn(nullable: false)]
        private Category $category,
        #[ORM\Column(type: 'string', length: 255)]
        private string $name,
        #[ORM\Column(type: 'integer')]
        #[Assert\PositiveOrZero]
        private int $stock,
        Money $price,
    ) {
        parent::__construct();

        $this->price = $price;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): Money
    {
        return $this->price;
    }

    public function setPrice(Money $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): self
    {
        $this->priority = max(1, min(100, $priority));

        return $this;
    }
}
