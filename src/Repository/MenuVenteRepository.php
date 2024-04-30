<?php

namespace App\Repository;

use App\Entity\MenuVente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MenuVente>
 *
 * @method MenuVente|null find($id, $lockMode = null, $lockVersion = null)
 * @method MenuVente|null findOneBy(array $criteria, array $orderBy = null)
 * @method MenuVente[]    findAll()
 * @method MenuVente[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuVenteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MenuVente::class);
    }

    //    /**
    //     * @return MenuVente[] Returns an array of MenuVente objects
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

    //    public function findOneBySomeField($value): ?MenuVente
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
