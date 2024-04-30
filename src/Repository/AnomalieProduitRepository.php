<?php

namespace App\Repository;

use App\Entity\AnomalieProduit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AnomalieProduit>
 *
 * @method AnomalieProduit|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnomalieProduit|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnomalieProduit[]    findAll()
 * @method AnomalieProduit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnomalieProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnomalieProduit::class);
    }

//    /**
//     * @return AnomalieProduit[] Returns an array of AnomalieProduit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AnomalieProduit
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
