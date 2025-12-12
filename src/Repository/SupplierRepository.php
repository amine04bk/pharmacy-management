<?php

namespace App\Repository;

use App\Entity\Supplier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Supplier>
 */
class SupplierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Supplier::class);
    }

    /**
     * Find all suppliers ordered by name
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find top suppliers by order count
     */
    public function findTopSuppliers(int $limit = 10): array
    {
        return $this->createQueryBuilder('s')
            ->select('s', 'COUNT(o.id) as orderCount')
            ->leftJoin('App\Entity\Order', 'o', 'WITH', 'o.supplier = s')
            ->groupBy('s.id')
            ->orderBy('orderCount', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Search suppliers by name
     */
    public function searchByName(string $searchTerm): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.name LIKE :search')
            ->setParameter('search', '%'.$searchTerm.'%')
            ->orderBy('s.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
