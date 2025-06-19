<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Symfony\Component\Uid\Uuid;

#[MappedSuperclass]
abstract class BaseEntity
{
    #[Id]
    #[Column(type: 'uuid', unique: true)]
    protected Uuid $id;

    public function __construct()
    {
        $this->id = Uuid::v7();
    }

    public function __clone(): void
    {
        $this->id = Uuid::v7();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
