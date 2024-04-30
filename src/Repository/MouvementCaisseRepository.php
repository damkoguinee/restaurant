<?php

namespace App\Repository;

use App\Entity\MouvementCaisse;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<MouvementCaisse>
 *
 * @method MouvementCaisse|null find($id, $lockMode = null, $lockVersion = null)
 * @method MouvementCaisse|null findOneBy(array $criteria, array $orderBy = null)
 * @method MouvementCaisse[]    findAll()
 * @method MouvementCaisse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MouvementCaisseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MouvementCaisse::class);
    }

//    /**
//     * @return MouvementCaisse[] Returns an array of MouvementCaisse objects
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

   public function findSoldeCaisse($caisse, $devise): ?float
   {
       return $this->createQueryBuilder('m')
           ->select('sum(m.montant) as montant')
           ->andWhere('m.caisse = :val')
           ->andWhere('m.devise = :devise')
           ->setParameter('val', $caisse)
           ->setParameter('devise', $devise)
           ->getQuery()
           ->getSingleScalarResult()
       ;
   }

   /**
     * @return array
     */
    public function findReceptionTransfertByLieuBySearchPaginated($lieu, $caisse, $startDate, $endDate, int $pageEnCours, int $limit): array
    {
        $endDate = (new \DateTime($endDate))->modify('+1 day');
        $limit = abs($limit);
        $result = [];
        $query = $this->createQueryBuilder('m')
            ->Where('m.lieuVente = :lieu')
            ->andWhere('m.caisse = :caisse')
            ->andWhere('m.montant > 0 ')
            ->andWhere('m.transfertFond IS NOT NULL')
            ->andWhere('m.dateSaisie BETWEEN :startDate AND :endDate')
            ->setParameter('lieu', $lieu)
            ->setParameter('caisse', $caisse)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('m.dateSaisie', 'DESC')
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
    public function findReceptionTransfertByLieuPaginated($lieu, $startDate, $endDate, int $pageEnCours, int $limit): array
    {
        $endDate = (new \DateTime($endDate))->modify('+1 day');
        $limit = abs($limit);
        $result = [];
        $query = $this->createQueryBuilder('m')
            ->Where('m.lieuVente = :lieu')
            ->andWhere('m.montant > 0 ')
            ->andWhere('m.transfertFond IS NOT NULL')
            ->andWhere('m.dateSaisie BETWEEN :startDate AND :endDate')
            ->setParameter('lieu', $lieu)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('m.dateSaisie', 'DESC')
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
    public function soldeCaisseParPeriodeParLieu($startDate, $endDate, $lieu): array
    {
        $endDate = (new \DateTime($endDate))->modify('+1 day');
        return $this->createQueryBuilder('m')
            ->select('SUM(m.montant) as solde', 'c.id', 'c.designation')
            ->Where('m.lieuVente = :lieu')
            ->andWhere('m.dateOperation BETWEEN :startDate AND :endDate')
            ->leftJoin('m.caisse', 'c')
            ->groupBy('m.caisse')
            ->setParameter('lieu', $lieu)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult();
    }


    /**
     * @return array
     */
    public function soldeCaisseParPeriodeParTypeParLieu($startDate, $endDate, $lieu): array
    {
        $endDate = (new \DateTime($endDate))->modify('+1 day');
        return $this->createQueryBuilder('m')
            ->select('SUM(m.montant) as solde, COUNT(m.id) as nbre, m as mouvement')
            ->Where('m.lieuVente = :lieu')
            ->andWhere('m.dateOperation BETWEEN :startDate AND :endDate')
            ->groupBy('m.typeMouvement')
            ->orderBy('solde', 'DESC')
            ->addOrderBy('m.typeMouvement', 'ASC')
            ->setParameter('lieu', $lieu)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array
     */
    public function soldeCaisseParPeriodeParVendeurParLieu($vendeur, $startDate, $endDate, $lieu): array
    {
        $endDate = (new \DateTime($endDate))->modify('+1 day');
        return $this->createQueryBuilder('m')
            ->select('SUM(m.montant) as solde ')
            ->Where('m.lieuVente = :lieu')
            ->andWhere('m.saisiePar = :vendeur')
            ->andWhere('m.dateOperation BETWEEN :startDate AND :endDate')
            ->leftJoin('m.caisse', 'c')
            ->addSelect('c.id, c.designation')
            ->groupBy('m.caisse')
            ->setParameter('lieu', $lieu)
            ->setParameter('vendeur', $vendeur)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult();
    }


    /**
     * @return array
     */
    public function soldeCaisseParPeriodeParTypeParVendeurParLieu($vendeur, $startDate, $endDate, $lieu): array
    {
        $endDate = (new \DateTime($endDate))->modify('+1 day');
        return $this->createQueryBuilder('m')
            ->select('SUM(m.montant) as solde, COUNT(m.id) as nbre, m as mouvement')
            ->Where('m.lieuVente = :lieu')
            ->andWhere('m.saisiePar = :vendeur')
            ->andWhere('m.dateOperation BETWEEN :startDate AND :endDate')
            ->groupBy('m.typeMouvement')
            ->orderBy('solde', 'DESC')
            ->addOrderBy('m.typeMouvement', 'ASC')
            ->setParameter('lieu', $lieu)
            ->setParameter('vendeur', $vendeur)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array
     */
    public function compteOperationParPeriodeParLieu($startDate, $endDate, $lieu): array
    {
        $endDate = (new \DateTime($endDate))->modify('+1 day');
        return $this->createQueryBuilder('m')
            ->Where('m.lieuVente = :lieu')
            ->andWhere('m.dateOperation BETWEEN :startDate AND :endDate')
            ->setParameter('lieu', $lieu)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult();
    }
}
