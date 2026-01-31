<?php

namespace App\Infrastructure\Product\Repository;

use App\Entity\App\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method null|Product find($id, $lockMode = null, $lockVersion = null)
 * @method null|Product findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductQueryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return Product[]
     */
    public function findAvailableProducts(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.isActive = :active')
            ->andWhere('p.stock > :stock')
            ->setParameter('active', true)
            ->setParameter('stock', 0)
            ->getQuery()
            ->getResult();
    }
}
