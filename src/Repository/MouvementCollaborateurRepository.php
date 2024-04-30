<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\MouvementCollaborateur;
use App\Entity\Personnel;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<MouvementCollaborateur>
 *
 * @method MouvementCollaborateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method MouvementCollaborateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method MouvementCollaborateur[]    findAll()
 * @method MouvementCollaborateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MouvementCollaborateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MouvementCollaborateur::class);
    }

//    /**
//     * @return MouvementCollaborateur[] Returns an array of MouvementCollaborateur objects
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

//    public function findOneBySomeField($value): ?MouvementCollaborateur
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findSoldeCollaborateur($collaborateur): array
    {
        return $this->createQueryBuilder('m')
            ->select('sum(m.montant) as montant' , 'c.nomDevise as devise')
            ->leftJoin('m.devise', 'c')
            ->andWhere('m.collaborateur = :colab')
            ->setParameter('colab', $collaborateur)
            ->addGroupBy('m.devise')
            ->getQuery()
            ->getResult()

        ;
    }

    /**
     * @return array
     */
    public function SoldeDetailByCollaborateurByDeviseByDate($collaborateur, $devise, $startDate, $endDate, int $pageEnCours, int $limit): array
    {
        $endDate = (new \DateTime($endDate))->modify('+1 day');
        $limit = abs($limit);
        $result = [];
        $query = $this->createQueryBuilder('m')
            ->andWhere('m.collaborateur = :collab')
            ->andWhere('m.devise = :devise')
            ->andWhere('m.dateSaisie BETWEEN :startDate AND :endDate')
            ->setParameter('collab', $collaborateur)
            ->setParameter('devise', $devise)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('m.dateSaisie', 'ASC')
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
        ;
    }

    /**
     * @return int|null
     */
    public function sumMontantBeforeStartDate($collaborateur, $devise, $startDate): ?int
    {
        $query = $this->createQueryBuilder('m')
            ->select('sum(m.montant) as totalMontant')
            ->andWhere('m.collaborateur = :collab')
            ->andWhere('m.devise = :devise')
            ->andWhere('m.dateSaisie < :startDate')
            ->setParameter('collab', $collaborateur)
            ->setParameter('devise', $devise)
            ->setParameter('startDate', $startDate)
            ->getQuery()
            ->getSingleScalarResult();
        return $query;
    }

    public function findSoldeCompteCollaborateur($collaborateur, $devises): array
    {
        $query = $this->createQueryBuilder('m');        
        $results = $query
            ->select('sum(m.montant) as montant', 'd.nomDevise as devise')
            ->leftJoin('m.devise', 'd')
            ->andWhere('m.collaborateur = :colab')
            ->setParameter('colab', $collaborateur)
            ->addGroupBy('d.nomDevise')
            ->getQuery()
            ->getResult();

        // Créer un tableau pour stocker les résultats finaux
        $finalResults = [];
        foreach ($devises as $devise) {
            $trouve = false;
            foreach ($results as $resultat) {
                if ($resultat['devise'] === $devise->getNomDevise()) {
                    $finalResults[] = $resultat;
                    $trouve = true;
                    break;
                }
            }
            if (!$trouve) {
                // Si la devise n'est pas trouvée dans les résultats, ajouter une entrée avec un montant de zéro
                $finalResults[] = ['montant' => '0.00', 'devise' => $devise->getNomDevise()];
            }
        }

        return $finalResults;
    }

    public function findSoldeGeneralByType($type1, $type2, $lieu_vente): array
    {
        if ($type1 == 'personnel' and $type2 == 'personnel') {
            return $this->createQueryBuilder('m')
                ->select('sum(m.montant) as montant' , 'd.nomDevise as devise')
                ->leftJoin('m.devise', 'd')
                ->leftJoin('m.collaborateur', 'u')
                ->leftJoin(Personnel::class, 'p', 'WITH', 'p.user = u.id ')
                ->andWhere('m.lieuVente= :lieu')
                ->setParameter('lieu', $lieu_vente)
                ->addGroupBy('m.devise')
                ->getQuery()
                ->getResult()
    
            ;
        }else{

            return $this->createQueryBuilder('m')
                ->select('sum(m.montant) as montant' , 'd.nomDevise as devise')
                ->leftJoin('m.devise', 'd')
                ->leftJoin('m.collaborateur', 'u')
                ->leftJoin(Client::class, 'c', 'WITH', 'c.user = u.id ')
                ->andWhere('c.rattachement= :lieu')
                ->andWhere('c.typeClient = :type1 or c.typeClient = :type2 ')
                ->setParameter('lieu', $lieu_vente)
                ->setParameter('type1', $type1)
                ->setParameter('type2', $type2)
                ->addGroupBy('m.devise')
                ->getQuery()
                ->getResult()
    
            ;
        }
    }

    public function findAncienSoldeCollaborateur($collaborateur, $dateOp): array
    {
        return $this->createQueryBuilder('m')
            ->select('sum(m.montant) as montant' , 'c.nomDevise as devise')
            ->leftJoin('m.devise', 'c')
            ->andWhere('m.collaborateur = :colab')
            ->andWhere('m.dateOperation < :dateOp')
            ->setParameter('colab', $collaborateur)
            ->setParameter('dateOp', $dateOp)
            ->addGroupBy('m.devise')
            ->orderBy('m.devise')
            ->getQuery()
            ->getResult()

        ;
    }
}
