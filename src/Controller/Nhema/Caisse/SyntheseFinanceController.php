<?php

namespace App\Controller\Nhema\Caisse;

use App\Entity\LieuxVentes;
use App\Entity\ClotureCaisse;
use App\Entity\MouvementCaisse;
use App\Repository\UserRepository;
use App\Repository\CaisseRepository;
use App\Repository\DeviseRepository;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ModePaiementRepository;
use App\Repository\ClotureCaisseRepository;
use App\Repository\CompteOperationRepository;
use App\Repository\MouvementCaisseRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategorieOperationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/nhema/caisse')]
class SyntheseFinanceController extends AbstractController
{
    #[Route('/{lieu_vente}', name: 'app_nhema_caisse_index')]
    public function index(LieuxVentes $lieu_vente, Request $request, SessionInterface $session, MouvementCaisseRepository $mouvementRep, UserRepository $userRep, EntrepriseRepository $entrepriseRep): Response
    {
        $session_jour = $session->get("session_jour")->getJourDeTravail();
        $session_date1 = $session->get("session_date1", null);
        $session_date2 = $session->get("session_date2", null);

        if ($request->query->get('id_personnel') or $request->isXmlHttpRequest()) {
            $date1 = $session_date1;
            $date2 = $session_date2;
        }else{
            if ($request->get("date1")){
                $date1 = $request->get("date1");
                $date2 = $request->get("date2");
            }else{
                $date1 = $session_jour->format("Y-m-d");
                $date2 = date("Y-m-d");
            }
            $session_date1 = $date1;
            $session_date2 = $date2;

            $session->set("session_date1", $session_date1);
            $session->set("session_date2", $session_date2);
        }

        if ($request->get("id_personnel")){
            $search = $request->get("id_personnel");
        }else{
            $search = "";
        }

        if ($request->isXmlHttpRequest()) {
            if ( $request->query->get('search_personnel')) {
                $search = $request->query->get('search_personnel');
                $clients = $userRep->findPersonnelSearchByLieu($search, $lieu_vente);    
                $response = [];
                foreach ($clients as $client) {
                    $response[] = [
                        'nom' => ucwords($client->getPrenom())." ".strtoupper($client->getNom()),
                        'id' => $client->getId()
                    ]; // Mettez à jour avec le nom réel de votre propriété
                }
                return new JsonResponse($response);
            }
        }
        // dd($session_date1, $session_date2);
        if ($request->get("id_personnel")){
            $solde_caisses = $mouvementRep->soldeCaisseParPeriodeParVendeurParLieu($search, $session_date1, $session_date2, $lieu_vente);
            $solde_types = $mouvementRep->soldeCaisseParPeriodeParTypeParVendeurParLieu($search, $session_date1, $session_date2, $lieu_vente);
        }else{
            $solde_caisses = $mouvementRep->soldeCaisseParPeriodeParLieu($date1, $date2, $lieu_vente);
            $solde_types = $mouvementRep->soldeCaisseParPeriodeParTypeParLieu($date1, $date2, $lieu_vente);
        }
        $compte_operations = $mouvementRep->compteOperationParPeriodeParLieu($date1, $date2, $lieu_vente);
        return $this->render('nhema/caisse/index.html.twig', [
            'solde_caisses' => $solde_caisses,
            'solde_types' => $solde_types,
            'compte_operations' => $compte_operations,
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente'   => $lieu_vente,
            'search' => $search,
            'date1' => $date1,
            'date2' => $date2,
        ]);
    }

    #[Route('/cloture/{lieu_vente}', name: 'app_nhema_caisse_cloture')]
    public function cloture(LieuxVentes $lieu_vente, Request $request, SessionInterface $session, MouvementCaisseRepository $mouvementRep, UserRepository $userRep, CaisseRepository $caisseRep, DeviseRepository $deviseRep, CompteOperationRepository $compteOpRep, CategorieOperationRepository $catetgorieOpRep, ModePaiementRepository $modePaieRep, ClotureCaisseRepository $clotureRep, EntrepriseRepository $entrepriseRep,EntityManagerInterface $em): Response
    {
        $session_jour = $session->get("session_jour")->getJourDeTravail();
        if ($request->get("date1")){
            $date1 = $request->get("date1");
        }else{
            $date1 = $session_jour->format("Y-m-d");
        }
        if ($request->get('montant_reel')) {
            $montant_reel = floatval(preg_replace('/[^-0-9,.]/', '', $request->get('montant_reel')));
            $montant_theo = floatval(preg_replace('/[^-0-9,.]/', '', $request->get('montant_theo')));
            $difference = $montant_theo - $montant_reel;
            $caisse = $caisseRep->find($request->get('id_caisse'));
            $devise = $deviseRep->find(1);
            $clotureCaisse = new ClotureCaisse();
            $clotureCaisse->setJournee($session_jour)
                    ->setMontantTheo($montant_theo)
                    ->setMontantReel($montant_reel)
                    ->setDifference($difference)
                    ->setDevise($devise)
                    ->setCaisse($caisse)
                    ->setLieuVente($lieu_vente)
                    ->setSaisiePar($this->getUser())
                    ->setDateSaisie(new \DateTime("now"));
            $mouvementCaisse = new MouvementCaisse();
            $categorie_op = $catetgorieOpRep->find(7);
            $compte_op = $compteOpRep->find(7);
            $mouvementCaisse->setCategorieOperation($categorie_op)
                    ->setCompteOperation($compte_op)
                    ->setTypeMouvement("cloture")
                    ->setMontant(- $difference)
                    ->setModePaie($modePaieRep->find(1))
                    ->setSaisiePar($this->getUser())
                    ->setDevise($devise)
                    ->setCaisse($caisse)
                    ->setLieuVente($lieu_vente)
                    ->setDateOperation($session_jour)
                    ->setDateSaisie(new \DateTime("now"));
            $clotureCaisse->addMouvementCaiss($mouvementCaisse);
            $em->persist($clotureCaisse);
            $em->flush();
            $this->addFlash("success", "Caisse clôturée avec succés :) ");
            return new RedirectResponse($this->generateUrl('app_nhema_caisse_cloture', ['lieu_vente' => $lieu_vente->getId(), 'search' => $request->get("search")]));
        }

        $solde_caisses = $mouvementRep->soldeCaisseParPeriodeParLieu($date1, $date1, $lieu_vente);
        $solde_types = $mouvementRep->soldeCaisseParPeriodeParTypeParLieu($date1, $date1, $lieu_vente);
        $journee = new \DateTime($date1);
        return $this->render('nhema/caisse/cloture.html.twig', [
            'solde_caisses' => $solde_caisses,
            'solde_types' => $solde_types,
            'liste_clotures' => $clotureRep->findBy(['lieuVente' => $lieu_vente, 'journee' => $journee]),
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente'   => $lieu_vente,
            'date1' => $date1,
            'date2' => $date1,
        ]);
    }
}
