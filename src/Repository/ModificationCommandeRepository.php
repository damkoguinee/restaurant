<?php

namespace App\Repository;

use App\Entity\ModificationCommande;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<ModificationCommande>
 *
 * @method ModificationCommande|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModificationCommande|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModificationCommande[]    findAll()
 * @method ModificationCommande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModificationCommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModificationCommande::class);
    }

    //    /**
    //     * @return ModificationCommande[] Returns an array of ModificationCommande objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ModificationCommande
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    
}
