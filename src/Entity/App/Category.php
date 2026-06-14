<?php

namespace App\Entity\App;

use App\Infrastructure\Category\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category extends BaseEntity
{
    #[ORM\OneToMany(
        mappedBy: 'parent',
        targetEntity: self::class
    )]
    private Collection $children;

    #[ORM\OneToMany(
        mappedBy: 'category',
        targetEntity: Product::class
    )]
    private Collection $products;

    public function __construct(
        #[ORM\Column(length: 255)]
        private string $name,
        #[ORM\Column(type: 'text', nullable: true)]
        private ?string $description = null,
        #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
        private ?Category $parent = null,
    ) {
        parent::__construct();

        $this->children = new ArrayCollection();
        $this->products = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getParent(): ?Category
    {
        return $this->parent;
    }

    public function setParent(?Category $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }
}
