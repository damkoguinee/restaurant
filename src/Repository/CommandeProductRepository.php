<?php

namespace App\Repository;

use App\Entity\CommandeProduct;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<CommandeProduct>
 *
 * @method CommandeProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommandeProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommandeProduct[]    findAll()
 * @method CommandeProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommandeProduct::class);
    }

    //    /**
    //     * @return CommandeProduct[] Returns an array of CommandeProduct objects
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

    //    public function findOneBySomeField($value): ?CommandeProduct
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * @return array
     */
    public function listeDesProduitsVendusParPeriodeParLieuPagine($startDate, $endDate, $lieu, int $pageEnCours, int $limit): array
    {
        $endDate = (new \DateTime($endDate))->modify('+1 day');
        $limit = abs($limit);
        $result = [];
        $query = $this->createQueryBuilder('c')
            ->leftJoin('c.facturation', 'f')
            ->Where('f.lieuVente = :lieu')
            ->andWhere('f.dateFacturation BETWEEN :startDate AND :endDate')
            ->setParameter('lieu', $lieu)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setMaxResults($limit)
            ->setFirstResult(($pageEnCours * $limit) - $limit);
        $paginator = new Paginator($query);
        $data = $paginator->getQuery()->getResult();

        $nbrePages = ceil($paginator->count() / $limit);
        
         $result['data'] = $data;
         $result['nbrePages'] = $nbrePages;
         $result['pageEncours'] = $pageEnCours;
         $result['limit'] = $limit;
         
        return $result;
    }

    /**
     * @return array
     */
    public function listeDesProduitsVendusGroupeParPeriodeParLieu($startDate, $endDate, $lieu): array
    {
        $endDate = (new \DateTime($endDate))->modify('+1 day');
        return $this->createQueryBuilder('c')
            ->select('sum(c.quantite) as nbre', 'c as commandes')
            ->leftJoin('c.facturation', 'f')
            ->leftJoin('c.product', 'p')
            ->Where('f.lieuVente = :lieu')
            ->andWhere('f.dateFacturation BETWEEN :startDate AND :endDate')
            ->setParameter('lieu', $lieu)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->addGroupBy('c.product')
            ->addOrderBy('p.nom')
            ->getQuery()
            ->getResult();
    }

    
}
