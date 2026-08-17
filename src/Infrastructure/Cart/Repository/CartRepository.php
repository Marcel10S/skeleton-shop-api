<?php

declare(strict_types=1);

namespace App\Infrastructure\Cart\Repository;

use App\Entity\App\Cart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<Cart> */
class CartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry) { parent::__construct($registry, Cart::class); }
    public function findOneByToken(string $token): ?Cart { return $this->findOneBy(['token' => $token]); }
    public function save(Cart $cart): void { $this->getEntityManager()->persist($cart); $this->getEntityManager()->flush(); }

    /** @return Cart[] */
    public function findAllActive(): array
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.items', 'i')->addSelect('i')
            ->leftJoin('i.product', 'p')->addSelect('p')
            ->leftJoin('c.paymentMethod', 'pm')->addSelect('pm')
            ->orderBy('c.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
