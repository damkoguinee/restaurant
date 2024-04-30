<?php

namespace App\Repository;

use App\Entity\TableCommande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TableCommande>
 *
 * @method TableCommande|null find($id, $lockMode = null, $lockVersion = null)
 * @method TableCommande|null findOneBy(array $criteria, array $orderBy = null)
 * @method TableCommande[]    findAll()
 * @method TableCommande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TableCommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TableCommande::class);
    }

//    /**
//     * @return TableCommande[] Returns an array of TableCommande objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TableCommande
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function listeDesCommandes($lieu_vente): array
    {
        return $this->createQueryBuilder('t')
            ->select('t as commandes', 'c.nom as table')
            ->leftJoin('t.emplacement', 'c')
            ->andWhere('t.lieuVente = :lieuVente')
            ->setParameter('lieuVente', $lieu_vente)
            ->addGroupBy('t.emplacement')
            ->orderBy('t.id')
            ->getQuery()
            ->getResult()

        ;
    }

    public function CommandesStatutCuisine($service, $lieu_vente): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.service = :service')
            ->andWhere('t.lieuVente = :lieuVente')
            ->andWhere('t.statut != :statut')
            ->setParameter('service', $service)
            ->setParameter('lieuVente', $lieu_vente)
            ->setParameter('statut', 'servie')
            ->orderBy('t.statut')
            ->getQuery()
            ->getResult()

        ;
    }
}
