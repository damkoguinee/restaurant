<?php

namespace App\Repository;

use App\Entity\StockDessert;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StockDessert>
 *
 * @method StockDessert|null find($id, $lockMode = null, $lockVersion = null)
 * @method StockDessert|null findOneBy(array $criteria, array $orderBy = null)
 * @method StockDessert[]    findAll()
 * @method StockDessert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockDessertRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StockDessert::class);
    }

//    /**
//     * @return StockDessert[] Returns an array of StockDessert objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?StockDessert
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
