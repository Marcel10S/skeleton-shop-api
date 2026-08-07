<?php

declare(strict_types=1);

namespace App\Infrastructure\Order\Repository;

use App\Entity\App\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method null|Order find($id, $lockMode = null, $lockVersion = null)
 * @method null|Order findOneBy(array $criteria, array $orderBy = null)
 * @method Order[] findAll()
 * @method Order[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderQueryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /** @return Order[] */
    public function findAllNewestFirst(): array
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.items', 'i')->addSelect('i')
            ->leftJoin('o.delivery', 'd')->addSelect('d')
            ->orderBy('o.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
