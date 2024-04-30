<?php

namespace App\Repository;

use App\Entity\Stock;
use App\Entity\Inventaire;
use App\Entity\MouvementProduct;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Stock>
 *
 * @method Stock|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stock|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stock[]    findAll()
 * @method Stock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stock::class);
    }

//    /**
//     * @return Stock[] Returns an array of Stock objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Stock
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    /**
     * @return array
     */
    public function findStocksPaginated($magasin, $search, int $pageEnCours, int $limit): array
    {
        $limit = abs($limit);
        $result = [];
        $query = $this->createQueryBuilder('s')
            ->leftJoin('s.product', 'p')
            ->Where('s.emplacement = :stock ')
            ->andWhere('s.quantite != 0')
            ->andwhere('p.nom LIKE :search ')
            ->setParameter('stock', $magasin)
            ->setParameter('search', '%' . $search . '%')
            ->orderBy('p.nom', 'ASC')
            ->setMaxResults($limit)
            ->setFirstResult(($pageEnCours * $limit) - $limit);
        $paginator = new Paginator($query);
        $data = $paginator->getQuery()->getResult();
        // on calcule le nombre des pages

        $nbrePages = ceil($paginator->count() / $limit);
         // on remplit le tableau
        
         $result['data'] = $data;
         $result['nbrePages'] = $nbrePages;
         $result['pageEncours'] = $pageEnCours;
         $result['limit'] = $limit;
         
        return $result;
    }

    /**
     * @return array
     */
    public function findStocksForApproInitPaginated($magasin, $search, int $pageEnCours, int $limit): array
    {
        $limit = abs($limit);
        $result = [];
        $query = $this->createQueryBuilder('s')
            ->leftJoin('s.product', 'p')
            ->Where('s.emplacement = :stock ')
            ->andwhere('p.nom LIKE :search ')
            ->setParameter('stock', $magasin)
            ->setParameter('search', '%' . $search . '%')
            ->addOrderBy('p.nom')
            ->setMaxResults($limit)
            ->setFirstResult(($pageEnCours * $limit) - $limit);
        $paginator = new Paginator($query);
        $data = $paginator->getQuery()->getResult();
        // on calcule le nombre des pages

        $nbrePages = ceil($paginator->count() / $limit);
         // on remplit le tableau
        
         $result['data'] = $data;
         $result['nbrePages'] = $nbrePages;
         $result['pageEncours'] = $pageEnCours;
         $result['limit'] = $limit;
         
        return $result;
    }

    /**
     * @return array
     */
    public function findStocksByCodeBarrePaginated($magasin, $search, int $pageEnCours, int $limit): array
    {
        $limit = abs($limit);
        $result = [];
        $query = $this->createQueryBuilder('s')
            ->leftJoin('s.product', 'p')
            ->Where('s.emplacement = :stock ')
            ->setParameter('stock', $magasin)
            ->addOrderBy('p.nom')
            ->setMaxResults($limit)
            ->setFirstResult(($pageEnCours * $limit) - $limit);
        $paginator = new Paginator($query);
        $data = $paginator->getQuery()->getResult();
        // on calcule le nombre des pages

        $nbrePages = ceil($paginator->count() / $limit);
         // on remplit le tableau
        
         $result['data'] = $data;
         $result['nbrePages'] = $nbrePages;
         $result['pageEncours'] = $pageEnCours;
         $result['limit'] = $limit;
         
        return $result;
    }

    /**
     * @return array
     */
    public function findStocksForTransfertPaginated($magasin, $search, int $pageEnCours, int $limit): array
    {
        $limit = abs($limit);
        $result = [];
        $query = $this->createQueryBuilder('s')
            ->leftJoin('s.product', 'p')
            ->Where('s.emplacement = :stock ')
            ->andWhere('s.quantite > 0')
            ->andwhere('p.nom LIKE :search ')
            ->setParameter('stock', $magasin)
            ->setParameter('search', '%' . $search . '%')
            ->addOrderBy('p.nom')
            ->setMaxResults($limit)
            ->setFirstResult(($pageEnCours * $limit) - $limit);
        $paginator = new Paginator($query);
        $data = $paginator->getQuery()->getResult();
        // on calcule le nombre des pages

        $nbrePages = ceil($paginator->count() / $limit);
         // on remplit le tableau
        
         $result['data'] = $data;
         $result['nbrePages'] = $nbrePages;
         $result['pageEncours'] = $pageEnCours;
         $result['limit'] = $limit;
         
        return $result;
    }



    /**
     * @return array
     */
    public function findProductsPaginated($inventaire, $magasin, $search, int $pageEnCours, int $limit): array
    {
        $limit = abs($limit);
        $result = [];
        $query = $this->createQueryBuilder('s')
            ->select('s as entity', 'i.id as id_inv', 'i.ecart', 'i.quantiteInv as qtite_inv', 'i.statut')
            ->leftJoin(Inventaire::class, 'i', 'WITH', 'i.product = s.product')
            ->leftJoin('s.product', 'p')
            ->where('p.nom LIKE :search ')
            ->andWhere('s.emplacement = :stock')
            ->andWhere('i.stock = :stock OR i.id IS NULL')
            ->andWhere('i.inventaire = :inventaire OR i.id IS NULL')
            ->setParameter('search', '%' . $search . '%')
            ->setParameter('stock', $magasin)
            ->setParameter('inventaire', $inventaire)
            ->addOrderBy('i.id', 'ASC')
            ->addOrderBy('p.nom', 'ASC')
            ->setMaxResults($limit)
            ->setFirstResult(($pageEnCours * $limit) - $limit);
        $paginator = new Paginator($query);
        $data = $paginator->getQuery()->getResult();
        // on calcule le nombre des pages

        $nbrePages = ceil($paginator->count() / $limit);
         // on remplit le tableau
        
         $result['data'] = $data;
         $result['nbrePages'] = $nbrePages;
         $result['pageEncours'] = $pageEnCours;
         $result['limit'] = $limit;
         
        return $result;
    }

    /**
     * @return int|null
     */
    public function sumQuantiteProduct($id_product, $stock): ?float
    {
        $query = $this->createQueryBuilder('s')
            ->select('sum(s.quantite) as totalQuantite')
            ->andWhere('s.emplacement IN (:stock)')
            ->andWhere('s.product = :product')
            ->setParameter('stock', $stock)
            ->setParameter('product', $id_product)
            ->getQuery()
            ->getSingleScalarResult();
        return $query;
    }

    
}
