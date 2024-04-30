<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function rechercheProduct($value): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.nom LIKE :val')
            ->setParameter('val', '%' . $value . '%')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function rechercheProductVente($value): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('App\Entity\Ingredient', 'i', 'WITH', 'p INSTANCE OF App\Entity\Ingredient')
            ->andWhere('i.id IS NULL')
            ->andWhere('p.nom LIKE :val')
            ->setParameter('val', '%' . $value . '%')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array
     */
    public function listeDesProduitsPagination($search, int $pageEnCours, int $limit): array
    {
        $limit = abs($limit);
        $result = [];
        $query = $this->createQueryBuilder('p')
            ->andwhere('p.nom LIKE :search OR p.description LIKE :search ')
            ->setParameter('search', '%' . $search . '%')
            ->orderBy('p.nom', 'ASC')
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
}
