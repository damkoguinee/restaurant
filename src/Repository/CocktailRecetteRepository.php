<?php

namespace App\Repository;

use App\Entity\CocktailRecette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CocktailRecette>
 *
 * @method CocktailRecette|null find($id, $lockMode = null, $lockVersion = null)
 * @method CocktailRecette|null findOneBy(array $criteria, array $orderBy = null)
 * @method CocktailRecette[]    findAll()
 * @method CocktailRecette[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CocktailRecetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CocktailRecette::class);
    }

    //    /**
    //     * @return CocktailRecette[] Returns an array of CocktailRecette objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?CocktailRecette
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
