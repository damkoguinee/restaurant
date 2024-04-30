<?php

namespace App\Repository;

use App\Entity\Caisse;
use App\Entity\PointDeVente;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Caisse>
 *
 * @method Caisse|null find($id, $lockMode = null, $lockVersion = null)
 * @method Caisse|null findOneBy(array $criteria, array $orderBy = null)
 * @method Caisse[]    findAll()
 * @method Caisse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CaisseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Caisse::class);
    }

    //    /**
    //     * @return Caisse[] Returns an array of Caisse objects
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

    //    public function findOneBySomeField($value): ?Caisse
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
    * @return Caisse[] Returns an array of Caisse objects
    */
   public function findCaisseByLieu($lieu_vente): array
   {
       return $this->createQueryBuilder('c')
            ->leftJoin(PointDeVente::class, 'p', 'WITH', 'c.pointDeVente = p.id Or c.pointDeVente is null')
           ->where('p.lieuVente = :lieu')
           ->setParameter('lieu', $lieu_vente)
           ->orderBy('c.id', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }

   /**
    * @return Caisse[] Returns an array of Caisse objects
    */
    public function findCaisseByLieuByType($lieu_vente, $type): array
    {
        return $this->createQueryBuilder('c')
             ->leftJoin(PointDeVente::class, 'p', 'WITH', 'c.pointDeVente = p.id Or c.pointDeVente is null')
            ->where('c.type = :type')
            ->andWhere('p.lieuVente = :lieu')
            ->setParameter('type', $type)
            ->setParameter('lieu', $lieu_vente)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
