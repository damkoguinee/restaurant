<?php

namespace App\Repository;

use App\Entity\MouvementProduct;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<MouvementProduct>
 *
 * @method MouvementProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method MouvementProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method MouvementProduct[]    findAll()
 * @method MouvementProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MouvementProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MouvementProduct::class);
    }

//    /**
//     * @return MouvementProduct[] Returns an array of MouvementProduct objects
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

//    public function findOneBySomeField($value): ?MouvementProduct
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    /**
     * @return array
     */
    public function findListeProductsForApproInitPaginated($origine, $magasin, $search, int $pageEnCours, int $limit): array
    {
        $limit = abs($limit);
        $result = [];
        $query = $this->createQueryBuilder('m')
            ->leftJoin('m.product', 'p')
            ->Where('m.stockProduct = :stock ')
            ->andWhere('m.origine = :origine ')
            ->andwhere('p.nom LIKE :search')
            ->setParameter('stock', $magasin)
            ->setParameter('origine', $origine)
            ->setParameter('search', '%' . $search . '%')
            ->orderBy('m.id', 'DESC')
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
     * @return MouvementProduct[] Returns an array of MouvementProduct objects
    */
    public function findListeProductsReceptByTransfert($transferts): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.transfert IN (:transferts)')
            ->andWhere('m.quantite > 0')
            ->setParameter('transferts', $transferts)
            ->orderBy('m.id', 'DESC')
            ->addOrderBy('m.product', 'ASC')
            ->getQuery()
            ->getResult();
    }


     /**
     * @return array
     */
    public function findProductMouvGeneral($id_product, $startDate, $endDate, $lieuVente): array
    {
        $endDate = (new \DateTime($endDate))->modify('+1 day');
        $query = $this->createQueryBuilder('m')
            ->Where('m.lieuVente = :lieu')
            ->andWhere('m.product = :product')
            ->andWhere('m.dateOperation BETWEEN :startDate AND :endDate')
            ->setParameter('lieu', $lieuVente)
            ->setParameter('product', $id_product)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('m.dateOperation', 'ASC')
            ->getQuery()
            ->getResult();
        return $query;
    }

    /**
     * @return array
     */
    public function findProductMouvByStock($stock, $id_product, $startDate, $endDate): array
    {
        $endDate = (new \DateTime($endDate))->modify('+1 day');
        $query = $this->createQueryBuilder('m')
            ->Where('m.stockProduct = :stock')
            ->andWhere('m.product = :product')
            ->andWhere('m.dateOperation BETWEEN :startDate AND :endDate')
            ->setParameter('stock', $stock)
            ->setParameter('product', $id_product)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('m.dateOperation', 'ASC')
            ->getQuery()
            ->getResult();
        return $query;
    }

    /**
     * @return int|null
     */
    public function sumQuantiteBeforeStartDateByStock($stock, $id_product, $startDate): ?float
    {
        $query = $this->createQueryBuilder('m')
            ->select('sum(m.quantite) as totalQuantite')
            ->Where('m.stockProduct = :stock')
            ->andWhere('m.product = :product')
            ->andWhere('m.dateOperation < :startDate')
            ->setParameter('stock', $stock)
            ->setParameter('product', $id_product)
            ->setParameter('startDate', $startDate)
            ->getQuery()
            ->getSingleScalarResult();
        return $query;
    }

    /**
     * @return int|null
     */
    public function sumQuantiteBeforeStartDate($id_product, $startDate, $lieuVente): ?float
    {
        $query = $this->createQueryBuilder('m')
            ->select('sum(m.quantite) as totalQuantite')
            ->Where('m.lieuVente = :lieu')
            ->andWhere('m.product = :product')
            ->andWhere('m.dateOperation < :startDate')
            ->setParameter('lieu', $lieuVente)
            ->setParameter('product', $id_product)
            ->setParameter('startDate', $startDate)
            ->getQuery()
            ->getSingleScalarResult();
        return $query;
    }

    /**
     * @return int|null
     */
    public function sumQuantiteSup($id_product, $lieuVente): ?int
    {
        $query = $this->createQueryBuilder('m')
            ->select('sum(m.quantite) as totalQuantite')
            ->Where('m.lieuVente = :lieu')
            ->andWhere('m.product = :product')
            ->andWhere('m.quantite > 0')
            ->setParameter('lieu', $lieuVente)
            ->setParameter('product', $id_product)
            ->getQuery()
            ->getSingleScalarResult();
        return $query;
    }

    /**
     * @return int|null
     */
    public function sumQuantiteSupByStock($id_product, $stock): ?int
    {
        $query = $this->createQueryBuilder('m')
            ->select('sum(m.quantite) as totalQuantite')
            ->Where('m.stockProduct = :stock')
            ->andWhere('m.product = :product')
            ->andWhere('m.quantite > 0')
            ->setParameter('stock', $stock)
            ->setParameter('product', $id_product)
            ->getQuery()
            ->getSingleScalarResult();
        return $query;
    }


    /**
     * @return int|null
     */
    public function sumQuantiteInf($id_product, $lieuVente): ?int
    {
        $query = $this->createQueryBuilder('m')
            ->select('sum(m.quantite) as totalQuantite')
            ->Where('m.lieuVente = :lieu')
            ->andWhere('m.product = :product')
            ->andWhere('m.quantite < 0')
            ->setParameter('lieu', $lieuVente)
            ->setParameter('product', $id_product)
            ->getQuery()
            ->getSingleScalarResult();
        return $query;
    }

    /**
     * @return int|null
     */
    public function sumQuantiteInfByStock($id_product, $stock): ?float
    {
        $query = $this->createQueryBuilder('m')
            ->select('sum(m.quantite) as totalQuantite')
            ->Where('m.stockProduct = :stock')
            ->andWhere('m.product = :product')
            ->andWhere('m.quantite < 0')
            ->setParameter('stock', $stock)
            ->setParameter('product', $id_product)
            ->getQuery()
            ->getSingleScalarResult();
        return $query;
    }


    /**
     * @return int|null
     */
    public function sumMouvProdByCommandByProduct($commande, $product): ?float
    {
        $query = $this->createQueryBuilder('s')
            ->select('sum(s.quantite) as totalQuantite')
            ->andWhere('s.commande = :commande')
            ->andWhere('s.product = :product')
            ->setParameter('commande', $commande)
            ->setParameter('product', $product)
            ->getQuery()
            ->getSingleScalarResult();
        return $query;
    }
}
