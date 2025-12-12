<?php

namespace App\Repository;

use App\Entity\Medicine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Medicine>
 */
class MedicineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Medicine::class);
    }

    /**
     * Find medicines with low stock
     */
    public function findLowStock(int $threshold = 10): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.quantity < :threshold')
            ->setParameter('threshold', $threshold)
            ->orderBy('m.quantity', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find medicines expiring soon
     */
    public function findExpiringSoon(int $days = 30): array
    {
        $date = new \DateTime("+{$days} days");
        
        return $this->createQueryBuilder('m')
            ->andWhere('m.expirationDate <= :date')
            ->andWhere('m.expirationDate IS NOT NULL')
            ->setParameter('date', $date)
            ->orderBy('m.expirationDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Calculate total inventory value
     */
    public function getTotalValue(): float
    {
        $result = $this->createQueryBuilder('m')
            ->select('SUM(m.quantity * m.price)')
            ->getQuery()
            ->getSingleScalarResult();
        
        return $result ? (float) $result : 0.0;
    }

    /**
     * Find medicines by category
     */
    public function findByCategory(string $category): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.category = :category')
            ->setParameter('category', $category)
            ->orderBy('m.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Search medicines by name
     */
    public function searchByName(string $searchTerm): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.name LIKE :search')
            ->setParameter('search', '%'.$searchTerm.'%')
            ->orderBy('m.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
