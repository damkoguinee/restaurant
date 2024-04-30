<?php

namespace App\Repository;

use App\Entity\LiaisonProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LiaisonProduct>
 *
 * @method LiaisonProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method LiaisonProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method LiaisonProduct[]    findAll()
 * @method LiaisonProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LiaisonProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LiaisonProduct::class);
    }

    //    /**
    //     * @return LiaisonProduct[] Returns an array of LiaisonProduct objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?LiaisonProduct
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
    * @return Products[] Returns an array of Products objects
    */
    public function findLiaisonByProduct($value): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.product1 = :val OR p.product2 = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
