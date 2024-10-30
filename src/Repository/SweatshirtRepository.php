<?php

namespace App\Repository;

use App\Entity\SweatShirt; // Correction ici
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SweatshirtRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SweatShirt::class); // Correction ici
    }

    /**
     * @param int $minPrice
     * @param int $maxPrice
     * @return SweatShirt[] Returns an array of SweatShirt objects filtered by price range
     */
    public function findByPriceRange(int $minPrice, int $maxPrice): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.price >= :minPrice')
            ->andWhere('s.price <= :maxPrice')
            ->setParameter('minPrice', $minPrice)
            ->setParameter('maxPrice', $maxPrice)
            ->getQuery()
            ->getResult();
    }
}
