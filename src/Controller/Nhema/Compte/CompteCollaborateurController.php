<?php

namespace App\Controller\Nhema\Compte;

use App\Entity\LieuxVentes;
use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use App\Repository\DeviseRepository;
use App\Repository\RegionRepository;
use App\Repository\EntrepriseRepository;
use App\Repository\MouvementCaisseRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\MouvementCollaborateurRepository;
use App\Repository\PersonnelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/nhema/compte/compte/collaborateur')]
class CompteCollaborateurController extends AbstractController
{
    #[Route('/{lieu_vente}', name: 'app_nhema_compte_compte_collaborateur')]
    public function index(LieuxVentes $lieu_vente, Request $request, MouvementCollaborateurRepository $mouvementRep, DeviseRepository $deviseRep, ClientRepository $clientRep, UserRepository $userRep, RegionRepository $regionRep, PersonnelRepository $personnelRep, EntrepriseRepository $entrepriseRep): Response
    {
        if ($request->get("id_client_search")){
            $search = $request->get("id_client_search");
        }else{
            $search = "";
        }

        if ($request->get("date1")){
            $date1 = $request->get("date1");
            $date2 = $request->get("date2");

        }else{
            $date1 = date("Y-01-01");
            $date2 = date("Y-m-d");
        }
        $type1 = $request->get('type1') ? $request->get('type1') : 'client';
        $type2 = $request->get('type2') ? $request->get('type2') : 'client-fournisseur';

        if ($request->isXmlHttpRequest()) {
            $search = $request->query->get('search');
            if ($request->query->get('search_personnel')) {
                $clients = $userRep->findPersonnelSearchByLieu($search, $lieu_vente);    
            }else{

                $clients = $userRep->findClientSearchByLieu($search, $lieu_vente);    
            }
            $response = [];
            foreach ($clients as $client) {
                $response[] = [
                    'nom' => ucwords($client->getPrenom())." ".strtoupper($client->getNom()),
                    'id' => $client->getId()
                ]; // Mettez à jour avec le nom réel de votre propriété
            }
            return new JsonResponse($response);
        }
        $regions = $regionRep->findBy([], ['nom' => 'ASC']);
        $devises = $deviseRep->findAll();
        
        if (($type1 == 'personnel' and $type2 == 'personnel') or $request->get('id_personnel')) {
            $type1 = 'personnel';
            $type2 = 'personnel';
            if ($request->get("id_personnel")) {
                $clients = $personnelRep->findPersonnelByTypeBySearchByLieu($type1, $type2, $lieu_vente, $request->get("id_personnel"));
            }elseif ($request->get("region")) {
                $clients = $personnelRep->findPersonnelByTypeBySearchByLieu($type1, $type2, $lieu_vente, $request->get("region"));
            }else{
                $clients = $personnelRep->findPersonnelByTypeByLieu($type1, $type2, $lieu_vente);
    
            }
        }else{

            if ($request->get("id_client_search")) {
                $clients = $clientRep->findClientSearchByTypeByLieu($type1, $type2, $lieu_vente, $request->get("id_client_search"));
            }elseif ($request->get("region")) {
                $clients = $clientRep->findClientSearchByTypeByLieuByRegion($type1, $type2, $lieu_vente, $request->get("region"));
            }else{
                $clients = $clientRep->findClientByTypeByLieu($type1, $type2, $lieu_vente);
    
            }
        }
        $comptes = [];
        foreach ($clients as $client) {
           $comptes[] = [
                'collaborateur' => $client->getUser(),
                'soldes' => $mouvementRep->findSoldeCompteCollaborateur($client->getUser(), $devises)
            ];
        }

        if ($request->get("region")){
            $region_find = $regionRep->find($request->get("region"));
        }else{
            $region_find = array();
        }
        $solde_general_type = $mouvementRep->findSoldeGeneralByType($type1, $type2, $lieu_vente);
        return $this->render('nhema/compte/compte_collaborateur/index.html.twig', [
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
            'search' => $search,
            'comptes' => $comptes,
            'devises'   => $devises,
            'regions' => $regions,
            'region_find' => $region_find,
            'type1' => $type1,
            'type2' => $type2,
            'solde_general_type' => $solde_general_type,
        ]);
    }

    #[Route('/detail/{lieu_vente}', name: 'app_nhema_compte_compte_collaborateur_detail')]
    public function detailCompte(LieuxVentes $lieu_vente, UserRepository $userRep, Request $request, MouvementCollaborateurRepository $mouvementRep, DeviseRepository $deviseRep, EntrepriseRepository $entrepriseRep): Response
    {
        

        if ($request->get("date1")){
            $date1 = $request->get("date1");
            $date2 = $request->get("date2");

        }else{
            $date1 = date("Y-01-01");
            $date2 = date("Y-m-d");
        }
        $user = $userRep->find($request->get('user'));
        $devise = $deviseRep->findOneBy(['nomDevise' => $request->get('devise')]);
        $pageEncours = $request->get('pageEncours', 1);

        $mouvements = $mouvementRep->SoldeDetailByCollaborateurByDeviseByDate($user, $devise, $date1, $date2, $pageEncours, 2000);
        
        $solde_init = $mouvementRep->sumMontantBeforeStartDate($user, $devise, $date1);
        return $this->render('nhema/compte/compte_collaborateur/detail_compte.html.twig', [
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
            'mouvements' =>$mouvements,
            'solde_init' => $solde_init,
            'user' => $user,
            'devise' => $devise
        ]);
    }
}
