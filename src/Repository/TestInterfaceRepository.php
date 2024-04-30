<?php

namespace App\Repository;

use App\Entity\TestInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TestInterface>
 *
 * @method TestInterface|null find($id, $lockMode = null, $lockVersion = null)
 * @method TestInterface|null findOneBy(array $criteria, array $orderBy = null)
 * @method TestInterface[]    findAll()
 * @method TestInterface[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestInterfaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TestInterface::class);
    }

    //    /**
    //     * @return TestInterface[] Returns an array of TestInterface objects
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

    //    public function findOneBySomeField($value): ?TestInterface
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
