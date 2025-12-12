<?php

namespace App\Repository;

use App\Entity\SupplierInventory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SupplierInventory>
 */
class SupplierInventoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SupplierInventory::class);
    }

    /**
     * Find inventory by supplier
     */
    public function findBySupplier(int $supplierId): array
    {
        return $this->createQueryBuilder('si')
            ->andWhere('si.supplier = :supplierId')
            ->setParameter('supplierId', $supplierId)
            ->orderBy('si.lastRestocked', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find low stock items for specific supplier
     */
    public function findLowStockBySupplier(int $supplierId, int $threshold = 50): array
    {
        return $this->createQueryBuilder('si')
            ->andWhere('si.supplier = :supplierId')
            ->andWhere('si.quantity < :threshold')
            ->setParameter('supplierId', $supplierId)
            ->setParameter('threshold', $threshold)
            ->orderBy('si.quantity', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Calculate total stock value for supplier
     */
    public function getTotalStockValueBySupplier(int $supplierId): float
    {
        $result = $this->createQueryBuilder('si')
            ->select('SUM(si.quantity * si.wholesalePrice)')
            ->andWhere('si.supplier = :supplierId')
            ->setParameter('supplierId', $supplierId)
            ->getQuery()
            ->getSingleScalarResult();
        
        return $result ? (float) $result : 0.0;
    }

    /**
     * Find inventory item by supplier and medicine
     */
    public function findBySupplierAndMedicine(int $supplierId, int $medicineId): ?SupplierInventory
    {
        return $this->createQueryBuilder('si')
            ->andWhere('si.supplier = :supplierId')
            ->andWhere('si.medicine = :medicineId')
            ->setParameter('supplierId', $supplierId)
            ->setParameter('medicineId', $medicineId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
