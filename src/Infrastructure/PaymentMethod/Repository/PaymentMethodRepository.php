<?php

declare(strict_types=1);

namespace App\Infrastructure\PaymentMethod\Repository;

use App\Entity\App\PaymentMethod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<PaymentMethod> */
class PaymentMethodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentMethod::class);
    }

    /** @return PaymentMethod[] */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('pm')
            ->orderBy('pm.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
