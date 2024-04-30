<?php

namespace App\Repository;

use App\Entity\ClotureCaisse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClotureCaisse>
 *
 * @method ClotureCaisse|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClotureCaisse|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClotureCaisse[]    findAll()
 * @method ClotureCaisse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClotureCaisseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClotureCaisse::class);
    }

    //    /**
    //     * @return ClotureCaisse[] Returns an array of ClotureCaisse objects
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

    //    public function findOneBySomeField($value): ?ClotureCaisse
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
