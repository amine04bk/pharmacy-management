<?php

namespace App\Repository;

use App\Entity\PharmacyInventory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PharmacyInventory>
 */
class PharmacyInventoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PharmacyInventory::class);
    }

    /**
     * Find inventory by pharmacy
     */
    public function findByPharmacy(int $pharmacyId): array
    {
        return $this->createQueryBuilder('pi')
            ->andWhere('pi.pharmacy = :pharmacyId')
            ->setParameter('pharmacyId', $pharmacyId)
            ->orderBy('pi.lastRestocked', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find low stock items for specific pharmacy
     */
    public function findLowStockByPharmacy(int $pharmacyId, int $threshold = 10): array
    {
        return $this->createQueryBuilder('pi')
            ->andWhere('pi.pharmacy = :pharmacyId')
            ->andWhere('pi.quantity < :threshold')
            ->setParameter('pharmacyId', $pharmacyId)
            ->setParameter('threshold', $threshold)
            ->orderBy('pi.quantity', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Calculate total stock value for pharmacy
     */
    public function getTotalStockValueByPharmacy(int $pharmacyId): float
    {
        $result = $this->createQueryBuilder('pi')
            ->select('SUM(pi.quantity * pi.purchasePrice)')
            ->andWhere('pi.pharmacy = :pharmacyId')
            ->setParameter('pharmacyId', $pharmacyId)
            ->getQuery()
            ->getSingleScalarResult();
        
        return $result ? (float) $result : 0.0;
    }

    /**
     * Find inventory item by pharmacy and medicine
     */
    public function findByPharmacyAndMedicine(int $pharmacyId, int $medicineId): ?PharmacyInventory
    {
        return $this->createQueryBuilder('pi')
            ->andWhere('pi.pharmacy = :pharmacyId')
            ->andWhere('pi.medicine = :medicineId')
            ->setParameter('pharmacyId', $pharmacyId)
            ->setParameter('medicineId', $medicineId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
