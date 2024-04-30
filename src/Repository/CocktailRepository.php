<?php

namespace App\Repository;

use App\Entity\Cocktail;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Cocktail>
 *
 * @method Cocktail|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cocktail|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cocktail[]    findAll()
 * @method Cocktail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CocktailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cocktail::class);
    }

    //    /**
    //     * @return Cocktail[] Returns an array of Cocktail objects
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

    //    public function findOneBySomeField($value): ?Cocktail
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function rechercheCocktail($value): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.nom LIKE :val')
            ->setParameter('val', '%' . $value . '%')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array
     */
    public function listeDesCocktailsPagination($search, int $pageEnCours, int $limit): array
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
