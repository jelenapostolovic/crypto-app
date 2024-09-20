<?php

namespace App\Repository;

use App\Entity\CryptoCurrency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CryptoCurrency>
 */
class CryptoCurrencyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CryptoCurrency::class);
    }

     /**
     * @return CryptoCurrency[]
     */
    public function findByPriceGreaterThan(float $minPrice): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.currentPrice > :minPrice')
            ->setParameter('minPrice', $minPrice)
            ->getQuery()
            ->getResult();
    }

    public function findByPriceLessThan(float $maxPrice): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.currentPrice < :maxPrice')
            ->setParameter('maxPrice', $maxPrice)
            ->getQuery()
            ->getResult();
    }

    public function findLowestPrices(int $limit): array
{
    return $this->createQueryBuilder('c')
        ->orderBy('c.currentPrice', 'ASC')
        ->setMaxResults($limit)
        ->getQuery()
        ->getResult();
}
}