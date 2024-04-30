<?php

namespace App\Repository;

use App\Entity\StockIngredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StockIngredient>
 *
 * @method StockIngredient|null find($id, $lockMode = null, $lockVersion = null)
 * @method StockIngredient|null findOneBy(array $criteria, array $orderBy = null)
 * @method StockIngredient[]    findAll()
 * @method StockIngredient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockIngredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StockIngredient::class);
    }

    //    /**
    //     * @return StockIngredient[] Returns an array of StockIngredient objects
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

    //    public function findOneBySomeField($value): ?StockIngredient
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
