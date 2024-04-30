<?php

namespace App\Repository;

use App\Entity\ListeProductAchatFournisseur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ListeProductAchatFournisseur>
 *
 * @method ListeProductAchatFournisseur|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListeProductAchatFournisseur|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListeProductAchatFournisseur[]    findAll()
 * @method ListeProductAchatFournisseur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListeProductAchatFournisseurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListeProductAchatFournisseur::class);
    }

    //    /**
    //     * @return ListeProductAchatFournisseur[] Returns an array of ListeProductAchatFournisseur objects
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

    //    public function findOneBySomeField($value): ?ListeProductAchatFournisseur
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
