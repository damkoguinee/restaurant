<?php

namespace App\Repository;

use App\Entity\JourneeTravail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JourneeTravail>
 *
 * @method JourneeTravail|null find($id, $lockMode = null, $lockVersion = null)
 * @method JourneeTravail|null findOneBy(array $criteria, array $orderBy = null)
 * @method JourneeTravail[]    findAll()
 * @method JourneeTravail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JourneeTravailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JourneeTravail::class);
    }

    //    /**
    //     * @return JourneeTravail[] Returns an array of JourneeTravail objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('j.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?JourneeTravail
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
