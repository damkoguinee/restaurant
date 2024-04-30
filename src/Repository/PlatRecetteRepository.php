<?php

namespace App\Repository;

use App\Entity\PlatRecette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlatRecette>
 *
 * @method PlatRecette|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlatRecette|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlatRecette[]    findAll()
 * @method PlatRecette[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlatRecetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlatRecette::class);
    }

    //    /**
    //     * @return PlatRecette[] Returns an array of PlatRecette objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?PlatRecette
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
