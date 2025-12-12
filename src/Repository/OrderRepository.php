<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * Find orders by status
     */
    public function findByStatus(string $status): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.status = :status')
            ->setParameter('status', $status)
            ->orderBy('o.orderDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find all pending orders
     */
    public function findPendingOrders(): array
    {
        return $this->findByStatus('pending');
    }

    /**
     * Count orders by status
     */
    public function countByStatus(string $status): int
    {
        return (int) $this->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->andWhere('o.status = :status')
            ->setParameter('status', $status)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Find recent orders
     */
    public function findRecentOrders(int $limit = 10): array
    {
        return $this->createQueryBuilder('o')
            ->orderBy('o.orderDate', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find orders by supplier
     */
    public function findBySupplier(int $supplierId): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.supplier = :supplierId')
            ->setParameter('supplierId', $supplierId)
            ->orderBy('o.orderDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find orders by medicine
     */
    public function findByMedicine(int $medicineId): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.medicine = :medicineId')
            ->setParameter('medicineId', $medicineId)
            ->orderBy('o.orderDate', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
