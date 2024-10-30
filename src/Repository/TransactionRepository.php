<?php

namespace App\Repository;

use App\Entity\Transaction;
use App\Entity\Warehouse;
use App\Enum\TransactionType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transaction>
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function findProductsInWarehouse(Warehouse $warehouse)
    {
        return $this->createQueryBuilder('t')
            ->select('p.name, SUM(CASE WHEN t.transaction_type = :in THEN t.quantity ELSE 0 END) - SUM(CASE WHEN t.transaction_type = :out THEN t.quantity ELSE 0 END) as quantity')
            ->join('t.product', 'p')
            ->where('t.warehouse = :warehouse')
            ->groupBy('p.id')
            ->setParameter('warehouse', $warehouse)
            ->setParameter('in', TransactionType::IN)
            ->setParameter('out', TransactionType::OUT)
            ->getQuery()
            ->getResult();
    }
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    //    /**
    //     * @return Transaction[] Returns an array of Transaction objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Transaction
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
