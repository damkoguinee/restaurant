<?php

namespace App\Repository;

use App\Entity\TypeCocktail;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<TypeCocktail>
 *
 * @method TypeCocktail|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeCocktail|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeCocktail[]    findAll()
 * @method TypeCocktail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeCocktailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeCocktail::class);
    }

    //    /**
    //     * @return TypeCocktail[] Returns an array of TypeCocktail objects
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

    //    public function findOneBySomeField($value): ?TypeCocktail
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * @return array
     */
    public function listeDesTypeCocktailsPagination($search, int $pageEnCours, int $limit): array
    {
        $limit = abs($limit);
        $result = [];
        $query = $this->createQueryBuilder('t')
            ->andwhere('t.nom LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->orderBy('t.nom', 'ASC')
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
