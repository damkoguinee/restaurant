<?php

namespace App\Controller\Nhema\Vente;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Table;
use App\Entity\Service;
use App\Entity\LieuxVentes;
use App\Entity\TableCommande;
use App\Form\TableCommandeType;
use App\Repository\PlatRepository;
use App\Repository\UserRepository;
use App\Repository\SalleRepository;
use App\Repository\TableRepository;
use App\Repository\CaisseRepository;
use App\Repository\ChichaRepository;
use App\Repository\BoissonRepository;
use App\Repository\ProductRepository;
use App\Repository\ServiceRepository;
use App\Repository\CocktailRepository;
use App\Repository\TypePlatRepository;
use App\Repository\MenuVenteRepository;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FacturationRepository;
use App\Repository\PlatRecetteRepository;
use App\Repository\ModePaiementRepository;
use App\Repository\TypeCocktailRepository;
use App\Repository\TableCommandeRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CategorieBoissonRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/nhema/vente/table/commande')]
class TableCommandeController extends AbstractController
{
    #[Route('/{lieu_vente}', name: 'app_nhema_vente_table_commande_index', methods: ['GET'])]
    public function index(LieuxVentes $lieu_vente, TableCommandeRepository $tableCommandeRep, TableRepository $tableRep, ServiceRepository $serviceRep, SalleRepository $salleRep, Request $request, EntrepriseRepository $entrepriseRep): Response
    {
        $salle = $request->get('salle', $salleRep->findOneBy(['lieuVente' => $lieu_vente])->getId());
        $commandes = [];
        $tables = $tableRep->findBy(['lieuVente' => $lieu_vente]);

        foreach ($tables as $table) {
            $verif_cmd = $tableCommandeRep->findBy(['emplacement' => $table, 'lieuVente' => $lieu_vente]);
            if ($verif_cmd) {
                $commandes[] = [
                    'table' => $table,
                    'commande' => $tableCommandeRep->findBy(['emplacement' => $table, 'lieuVente' => $lieu_vente]),
                ]
                ;
            }
        }
        $commandes[] = [
            'table' => 'emporter',
            'commande' => $tableCommandeRep->findBy(['typeVente' => 'emporter', 'lieuVente' => $lieu_vente]),
        ];

        $commandes[] = [
            'table' => 'livraison',
            'commande' => $tableCommandeRep->findBy(['typeVente' => 'livraison', 'lieuVente' => $lieu_vente])
        ];
        return $this->render('nhema/vente/table_commande/index.html.twig', [
            'liste_commandes' => $commandes,
            'salles' => $salleRep->findBy(['lieuVente' => $lieu_vente]),
            'choix_salle' => $salle,
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
            'services' => $serviceRep->findAll(),
        ]);
    }

    #[Route('/new/{lieu_vente}', name: 'app_nhema_vente_table_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FacturationRepository $facturationRepository, LieuxVentes $lieu_vente, TypePlatRepository $typePlatRep, SessionInterface $session, ProductRepository $productRep, CocktailRepository $cocktailRep, CaisseRepository $caisseRep, TypeCocktailRepository $typeCocktailRep, CategorieBoissonRepository $categorieBoissonRep, BoissonRepository $boissonRep, MenuVenteRepository $menuVenteRep, ModePaiementRepository $modePaieRep, TableCommandeRepository $tableCommandeRep, UserRepository $userRep, ChichaRepository $chichaRep, PlatRepository $platRep, TableRepository $tableRep, EntrepriseRepository $entrepriseRep, EntityManagerInterface $em): Response
    {
        // on stock la table dans une session 
        $session_jour = $session->get('session_jour')->getJourDeTravail();
        $session_table = $session->get('session_table');
        $session_table = $request->get('table', $session_table);
        $session->set('session_table', $session_table);
        $type_vente = $session->get('session_type_vente'); // on recupère le type de vente (surplace, emporter, livraison)
        if ($request->get('type_vente')) {
            $session_type_vente = $request->get('type_vente');
            $session->set('session_type_vente', $session_type_vente);
            $type_vente = $session_type_vente;
        }
        // gestion des requetes json
        $search_product = $request->query->get('search_product');
        if ($search_product) {
            $products = $productRep->rechercheProductVente($search_product);    
            $response = [];
            foreach ($products as $product) {
                $response[] = [
                    'nom' => $product->getNom(),
                    'id' => $product->getId()
                ]; // Mettez à jour avec le nom réel de votre propriété
            }
            return new JsonResponse($response);
        }

        $search = $request->query->get('search_client');
        if ($search) {
            $clients = $userRep->findClientSearchByLieu($search, $lieu_vente);    
            $response = [];
            foreach ($clients as $client) {
                $response[] = [
                    'nom' => ucwords($client->getPrenom())." ".strtoupper($client->getNom()),
                    'id' => $client->getId()
                ]; // Mettez à jour avec le nom réel de votre propriété
            }
            return new JsonResponse($response);

        }

        $emplacement = $session_table ? $tableRep->find($session_table) : null;
        // gestion de la modification de la commande
        if ($request->get('id_prod_modif')) {
            $id_prod = $request->get('id_prod_modif');
            $quantite = $request->get('qtite');
            $commentaire = $request->get('commentaire');
            $commandeModif = $tableCommandeRep->find($id_prod);
            $commandeModif->setQuantite($quantite)
                    ->setCommentaire($commentaire);
            if ($request->get('offert')) {
                $commandeModif->setPrixVente(0);
            }
            $em->persist($commandeModif);
            $em->flush();
        }
        // gestion de la recherche produit
        if ($request->get('id_product_search')) {
            $id_prod = $request->get('id_product_search');
            $product = $productRep->find($id_prod);
            if($type_vente == 'emporterrr' or $type_vente == 'livraisonrrrr'){
                // $tableCommande =$tableCommandeRep->findOneBy(['product' => $product, 'lieuVente' => $lieu_vente, 'typeVente' => $type_vente, 'saisiePar' => $this->getUser()]);
            }else{
                $tableCommande =$tableCommandeRep->findOneBy(['product' => $product, 'lieuVente' => $lieu_vente, 'emplacement' => $emplacement]);
            }
            // on verifie si le produit existe déjà dans le panier si oui on incremente la quantité
            if ($tableCommande) {
                $tableCommande->setQuantite($tableCommande->getQuantite() + 1);
            }else{
                $tableCommande = new TableCommande();
                $tableCommande->setQuantite(1)
                        ->setProduct($product)
                        ->setPrixVente($product->getPrixVente())
                        ->setEmplacement($emplacement)
                        ->setSaisiePar($this->getUser())
                        ->setLieuVente($lieu_vente)
                        ->setTypeVente($type_vente)
                        ->setService($product->getService())
                        ->setStatut("commande")
                        ->setDateCommande($session_jour)
                        ->setDateSaisie(new \DateTime("now"));
            }
            $em->persist($tableCommande);
            $em->flush();

            return new RedirectResponse($this->generateUrl('app_nhema_vente_table_commande_new', ['nom_menu' => 'chichas', 'lieu_vente' => $lieu_vente->getId()]));   
        }
        // gestion d'un nouvel ajout de commande dans la table commande
        if ($request->get('nom_menu')) {
            $nom_menu = strtolower($request->get('nom_menu'));
            if ($nom_menu == 'cocktails') {
                $types_menu = $typeCocktailRep->findAll();
                $type_menu = 'type_cocktail'; // on définit le type de menu pour le filtrage
                $liste_menu = array();
                $type = array();

            }elseif ($nom_menu == 'type_cocktail') {

                $type = $request->get('type', $typeCocktailRep->findOneBy([])->getId());

                $liste_menu = $cocktailRep->findBy(['lieuVente' => $lieu_vente, 'typeCocktail' => $type,  'etat' => 'actif'], ['nom' => 'ASC']);

                $types_menu = array();
                $type_menu = 'cocktail';

                if ($request->get('id_prod')) {
                    $id_prod = $request->get('id_prod');
                    $product = $cocktailRep->find($id_prod);
                    if($type_vente == 'emporter' or $type_vente == 'livraison'){
                        $tableCommande =$tableCommandeRep->findOneBy(['product' => $product, 'lieuVente' => $lieu_vente, 'typeVente' => $type_vente, 'saisiePar' => $this->getUser()]);
                    }else{
                        $tableCommande =$tableCommandeRep->findOneBy(['product' => $product, 'lieuVente' => $lieu_vente, 'emplacement' => $emplacement]);
                    }
                    // on verifie si le produit existe déjà dans le panier si oui on incremente la quantité
                    if ($tableCommande) {
                        $tableCommande->setQuantite($tableCommande->getQuantite() + 1);
                    }else{
                        $tableCommande = new TableCommande();
                        $tableCommande->setQuantite(1)
                                ->setProduct($product)
                                ->setPrixVente($product->getPrixVente())
                                ->setEmplacement($emplacement)
                                ->setSaisiePar($this->getUser())
                                ->setLieuVente($lieu_vente)
                                ->setTypeVente($type_vente)
                                ->setService($product->getService())
                                ->setStatut("commande")
                                ->setDateCommande($session_jour)
                                ->setDateSaisie(new \DateTime("now"));
                    }
                    $em->persist($tableCommande);
                    $em->flush();

                    return new RedirectResponse($this->generateUrl('app_nhema_vente_table_commande_new', ['type' => $type, 'nom_menu' => 'type_cocktail', 'lieu_vente' => $lieu_vente->getId()]));   
                }

            }elseif ($nom_menu == 'alcools') {
                $types_menu = $categorieBoissonRep->findBy(['alcoolise' => true]);
                $type_menu = 'alcools'; // on définit le type de menu pour le filtrage
                $liste_menu = array();
                $type = array();

            }elseif ($nom_menu == 'type_vente_alcools') {
                $type = $categorieBoissonRep->find($request->get('type'))->getId();
                $type_menu = 'type_vente_alcool'; // on définit le type de menu pour le filtrage
                $types_menu = array();
                $liste_menu = array();

            }elseif ($nom_menu == 'choix_alcool') {
                $categorie = $request->get('categorie', $categorieBoissonRep->findOneBy([])->getId());
                $type_vente = $request->get('type_vente', 'verre');
                $liste_menu = $boissonRep->findBy(['categorie' => $categorie,  'typeBoisson' => $type_vente], ['nom' => 'ASC']);

                $types_menu = array();
                $type_menu = 'choix_alcool';
                $type = $categorie;
                if ($request->get('id_prod')) {
                    $id_prod = $request->get('id_prod');
                    $product = $boissonRep->find($id_prod);
                    if($type_vente == 'emporter' or $type_vente == 'livraison'){
                        $tableCommande =$tableCommandeRep->findOneBy(['product' => $product, 'lieuVente' => $lieu_vente, 'typeVente' => $type_vente, 'saisiePar' => $this->getUser()]);
                    }else{
                        $tableCommande =$tableCommandeRep->findOneBy(['product' => $product, 'lieuVente' => $lieu_vente, 'emplacement' => $emplacement]);
                    }
                    // on verifie si le produit existe déjà dans le panier si oui on incremente la quantité
                    if ($tableCommande) {
                        $tableCommande->setQuantite($tableCommande->getQuantite() + 1);
                    }else{
                        $tableCommande = new TableCommande();
                        $tableCommande->setQuantite(1)
                                ->setProduct($product)
                                ->setPrixVente($product->getPrixVente())
                                ->setEmplacement($emplacement)
                                ->setSaisiePar($this->getUser())
                                ->setLieuVente($lieu_vente)
                                ->setTypeVente($type_vente)
                                ->setService($product->getService())
                                ->setStatut("commande")
                                ->setDateCommande($session_jour)
                                ->setDateSaisie(new \DateTime("now"));
                    }
                    $em->persist($tableCommande);
                    $em->flush();

                    return new RedirectResponse($this->generateUrl('app_nhema_vente_table_commande_new', ['categorie' => $categorie, 'type_vente' => $type_vente, 'nom_menu' => 'choix_alcool', 'lieu_vente' => $lieu_vente->getId()]));   
                }

            }elseif ($nom_menu == 'bières') {
                $categorie = $categorieBoissonRep->findOneBy(['nom' => $nom_menu])->getId();
                $liste_menu = $boissonRep->findBy(['categorie' => $categorie], ['nom' => 'ASC']);

                $types_menu = array();
                $type_menu = 'bieres';
                $type = $categorie;

                if ($request->get('id_prod')) {
                    $id_prod = $request->get('id_prod');
                    $product = $boissonRep->find($id_prod);
                    if($type_vente == 'emporter' or $type_vente == 'livraison'){
                        $tableCommande =$tableCommandeRep->findOneBy(['product' => $product, 'lieuVente' => $lieu_vente, 'typeVente' => $type_vente, 'saisiePar' => $this->getUser()]);
                    }else{
                        $tableCommande =$tableCommandeRep->findOneBy(['product' => $product, 'lieuVente' => $lieu_vente, 'emplacement' => $emplacement]);
                    }
                    // on verifie si le produit existe déjà dans le panier si oui on incremente la quantité
                    if ($tableCommande) {
                        $tableCommande->setQuantite($tableCommande->getQuantite() + 1);
                    }else{
                        $tableCommande = new TableCommande();
                        $tableCommande->setQuantite(1)
                                ->setProduct($product)
                                ->setPrixVente($product->getPrixVente())
                                ->setEmplacement($emplacement)
                                ->setSaisiePar($this->getUser())
                                ->setLieuVente($lieu_vente)
                                ->setTypeVente($type_vente)
                                ->setService($product->getService())
                                ->setStatut("commande")
                                ->setDateCommande($session_jour)
                                ->setDateSaisie(new \DateTime("now"));
                    }
                    $em->persist($tableCommande);
                    $em->flush();

                    return new RedirectResponse($this->generateUrl('app_nhema_vente_table_commande_new', ['id_menu' => $type, 'nom_menu' => 'bières', 'lieu_vente' => $lieu_vente->getId()]));   
                }

            }elseif ($nom_menu == 'boissons') {
                $categorie = $categorieBoissonRep->findOneBy(['nom' => 'boisson normale'])->getId();
                $liste_menu = $boissonRep->findBy(['categorie' => $categorie], ['nom' => 'ASC']);

                $types_menu = array();
                $type_menu = 'boissons';
                $type = $categorie;

                if ($request->get('id_prod')) {
                    $id_prod = $request->get('id_prod');
                    $product = $boissonRep->find($id_prod);
                    if($type_vente == 'emporter' or $type_vente == 'livraison'){
                        $tableCommande =$tableCommandeRep->findOneBy(['product' => $product, 'lieuVente' => $lieu_vente, 'typeVente' => $type_vente, 'saisiePar' => $this->getUser()]);
                    }else{
                        $tableCommande =$tableCommandeRep->findOneBy(['product' => $product, 'lieuVente' => $lieu_vente, 'emplacement' => $emplacement]);
                    }
                    // on verifie si le produit existe déjà dans le panier si oui on incremente la quantité
                    if ($tableCommande) {
                        $tableCommande->setQuantite($tableCommande->getQuantite() + 1);
                    }else{
                        $tableCommande = new TableCommande();
                        $tableCommande->setQuantite(1)
                                ->setProduct($product)
                                ->setPrixVente($product->getPrixVente())
                                ->setEmplacement($emplacement)
                                ->setSaisiePar($this->getUser())
                                ->setLieuVente($lieu_vente)
                                ->setTypeVente($type_vente)
                                ->setService($product->getService())
                                ->setStatut("commande")
                                ->setDateCommande($session_jour)
                                ->setDateSaisie(new \DateTime("now"));
                    }
                    $em->persist($tableCommande);
                    $em->flush();

                    return new RedirectResponse($this->generateUrl('app_nhema_vente_table_commande_new', ['id_menu' => $type_menu , 'nom_menu' => 'boissons', 'lieu_vente' => $lieu_vente->getId()]));   
                }

            }elseif ($nom_menu == 'boissons chaudes') {
                $categorie = $categorieBoissonRep->findOneBy(['nom' => 'boissons chaudes'])->getId();
                $liste_menu = $boissonRep->findBy(['categorie' => $categorie], ['nom' => 'ASC']);

                $types_menu = array();
                $type_menu = 'boissons_chaudes';
                $type = $categorie;

                if ($request->get('id_prod')) {
                    $id_prod = $request->get('id_prod');
                    $product = $boissonRep->find($id_prod);
                    if($type_vente == 'emporter' or $type_vente == 'livraison'){
                        $tableCommande =$tableCommandeRep->findOneBy(['product' => $product, 'lieuVente' => $lieu_vente, 'typeVente' => $type_vente, 'saisiePar' => $this->getUser()]);
                    }else{
                        $tableCommande =$tableCommandeRep->findOneBy(['product' => $product, 'lieuVente' => $lieu_vente, 'emplacement' => $emplacement]);
                    }
                    // on verifie si le produit existe déjà dans le panier si oui on incremente la quantité
                    if ($tableCommande) {
                        $tableCommande->setQuantite($tableCommande->getQuantite() + 1);
                    }else{
                        $tableCommande = new TableCommande();
                        $tableCommande->setQuantite(1)
                                ->setProduct($product)
                                ->setPrixVente($product->getPrixVente())
                                ->setEmplacement($emplacement)
                                ->setSaisiePar($this->getUser())
                                ->setLieuVente($lieu_vente)
                                ->setTypeVente($type_vente)
                                ->setService($product->getService())
                                ->setStatut("commande")
                                ->setDateCommande($session_jour)
                                ->setDateSaisie(new \DateTime("now"));
                    }
                    $em->persist($tableCommande);
                    $em->flush();

                    return new RedirectResponse($this->generateUrl('app_nhema_vente_table_commande_new', ['id_menu' => $type_menu , 'nom_menu' => 'boissons chaudes', 'lieu_vente' => $lieu_vente->getId()]));   
                }

            }elseif ($nom_menu == 'chichas') {
                $liste_menu = $chichaRep->findBy(['etat' => 'actif'], ['nom' => 'ASC']);

                $types_menu = array();
                $type_menu = 'chichas';
                $type = array();

                if ($request->get('id_prod')) {
                    $id_prod = $request->get('id_prod');
                    $product = $chichaRep->find($id_prod);
                    if($type_vente == 'emporter' or $type_vente == 'livraison'){
                        $tableCommande =$tableCommandeRep->findOneBy(['product' => $product, 'lieuVente' => $lieu_vente, 'typeVente' => $type_vente, 'saisiePar' => $this->getUser()]);
                    }else{
                        $tableCommande =$tableCommandeRep->findOneBy(['product' => $product, 'lieuVente' => $lieu_vente, 'emplacement' => $emplacement]);
                    }
                    // on verifie si le produit existe déjà dans le panier si oui on incremente la quantité
                    if ($tableCommande) {
                        $tableCommande->setQuantite($tableCommande->getQuantite() + 1);
                    }else{
                        // dd($session_jour);
                        $tableCommande = new TableCommande();
                        $tableCommande->setQuantite(1)
                                ->setProduct($product)
                                ->setPrixVente($product->getPrixVente())
                                ->setEmplacement($emplacement)
                                ->setSaisiePar($this->getUser())
                                ->setLieuVente($lieu_vente)
                                ->setTypeVente($type_vente)
                                ->setService($product->getService())
                                ->setStatut("commande")
                                ->setDateCommande($session_jour)
                                ->setDateSaisie(new \DateTime("now"));
                    }
                    $em->persist($tableCommande);
                    $em->flush();

                    return new RedirectResponse($this->generateUrl('app_nhema_vente_table_commande_new', ['id_menu' => $type_menu , 'nom_menu' => 'chichas', 'lieu_vente' => $lieu_vente->getId()]));   
                }

            }elseif ($nom_menu == 'plat' ) {
                $id_plat = $request->get('id_plat');
                $liste_menu = $platRep->findBy(['typePlat'=> $id_plat, 'etat' => 'actif'], ['nom' => 'ASC']) ;
                
                $types_menu = array();
                $type_menu = 'plats';
                $type = $id_plat;

                if ($request->get('id_prod')) {
                    $id_prod = $request->get('id_prod');
                    $product = $platRep->find($id_prod);
                    if($type_vente == 'emporter' or $type_vente == 'livraison'){
                        $tableCommande =$tableCommandeRep->findOneBy(['product' => $product, 'lieuVente' => $lieu_vente, 'typeVente' => $type_vente, 'saisiePar' => $this->getUser()]);
                    }else{
                        $tableCommande =$tableCommandeRep->findOneBy(['product' => $product, 'lieuVente' => $lieu_vente, 'emplacement' => $emplacement]);
                    }
                    // on verifie si le produit existe déjà dans le panier si oui on incremente la quantité
                    if ($tableCommande) {
                        $tableCommande->setQuantite($tableCommande->getQuantite() + 1);
                    }else{
                        $tableCommande = new TableCommande();
                        $tableCommande->setQuantite(1)
                                ->setProduct($product)
                                ->setPrixVente($product->getPrixVente())
                                ->setEmplacement($emplacement)
                                ->setSaisiePar($this->getUser())
                                ->setLieuVente($lieu_vente)
                                ->setTypeVente($type_vente)
                                ->setService($product->getService())
                                ->setStatut("commande")
                                ->setDateCommande($session_jour)
                                ->setDateSaisie(new \DateTime("now"));
                    }
                    $em->persist($tableCommande);
                    $em->flush();

                    return new RedirectResponse($this->generateUrl('app_nhema_vente_table_commande_new', ['id_plat' => $id_plat , 'nom_menu' => 'plat', 'lieu_vente' => $lieu_vente->getId()]));   
                }

            }else{
                $liste_menu = array();
                $type_menu = array();
                $types_menu = array();
                $type = array();
            }
        }else{
            $liste_menu = $chichaRep->findBy(['etat' => 'actif'], ['nom' => 'ASC']);

                $types_menu = array();
                $type_menu = 'chichas';
                $type = array();

                if ($request->get('id_prod')) {
                    $id_prod = $request->get('id_prod');
                    $product = $chichaRep->find($id_prod);
                    if($type_vente == 'emporter' or $type_vente == 'livraison'){
                        $tableCommande =$tableCommandeRep->findOneBy(['product' => $product, 'lieuVente' => $lieu_vente, 'typeVente' => $type_vente, 'saisiePar' => $this->getUser()]);
                    }else{
                        $tableCommande =$tableCommandeRep->findOneBy(['product' => $product, 'lieuVente' => $lieu_vente, 'emplacement' => $emplacement]);
                    }
                    // on verifie si le produit existe déjà dans le panier si oui on incremente la quantité
                    if ($tableCommande) {
                        $tableCommande->setQuantite($tableCommande->getQuantite() + 1);
                    }else{
                        $tableCommande = new TableCommande();
                        $tableCommande->setQuantite(1)
                                ->setProduct($product)
                                ->setPrixVente($product->getPrixVente())
                                ->setEmplacement($emplacement)
                                ->setSaisiePar($this->getUser())
                                ->setLieuVente($lieu_vente)
                                ->setTypeVente($type_vente)
                                ->setService($product->getService())
                                ->setStatut("commande")
                                ->setDateCommande($session_jour)
                                ->setDateSaisie(new \DateTime("now"));
                    }
                    $em->persist($tableCommande);
                    $em->flush();

                    return new RedirectResponse($this->generateUrl('app_nhema_vente_table_commande_new', ['id_menu' => $type_menu , 'nom_menu' => 'chichas', 'lieu_vente' => $lieu_vente->getId()]));   
                }
        }

        // gestion des modes de paiements
        $session_remise_glob = $session->get("session_remise_glob", null);
        if ($request->get("remise_glob")!== null){
            $montant_remise = floatval(preg_replace('/[^-0-9,.]/', '', $request->get("remise_glob")));
            $session_remise_glob = $montant_remise;
        }else{
            if (empty($session_remise_glob)) {
                $session_remise_glob = array();
            }
        }
        $session_montant_paye = $session->get("montant_paye", null);
        if ($request->get("montant_paye")!== null){
            $montant_paye = floatval(preg_replace('/[^-0-9,.]/', '', $request->get("montant_paye")));
            $session_montant_paye = $montant_paye;
        }else{
            if (empty($session_montant_paye)) {
                $session_montant_paye = array();
            }
        }

        $session_frais_sup = $session->get("frais_sup", null);
        if ($request->get("frais_sup")!== null){
            $montant_frais_sup = floatval(preg_replace('/[^-0-9,.]/', '', $request->get("frais_sup")));
            $session_frais_sup = $montant_frais_sup;
        }else{
            if (empty($session_frais_sup)) {
                $session_frais_sup = array();
            }
        }

        $session_client = $session->get("session_client", null);
        if ($request->get("id_client_search")){
            $client = $request->get("id_client_search");
            $client = $userRep->find($client);
            $session_client = $client;            
        }else{
            $client = array();
            if (empty($session_client)) {
                $session_client= array();
            }
        }
        $session->set("session_remise_glob", $session_remise_glob);
        $session->set('montant_paye', $session_montant_paye);
        $session->set('frais_sup', $session_frais_sup);
        $session->set("session_client", $session_client);

        // recupération des commandes par table
        $emplacement = $session_table ? $tableRep->find($session_table) : $type_vente;
        if ($emplacement == 'emporter' or $emplacement == 'livraison') {
            $commandes = $tableCommandeRep->findBy(['typeVente' => $type_vente, 'lieuVente' => $lieu_vente, 'saisiePar' => $this->getUser()]);
        }else{
            $commandes = $tableCommandeRep->findBy(['emplacement' => $emplacement, 'lieuVente' => $lieu_vente]);
        }
        return $this->render('nhema/vente/facturation/vente.html.twig', [
            'facturations' => $facturationRepository->findAll(),
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
            'types_plats' => $typePlatRep->findAll(),
            'menu_vente' => $menuVenteRep->findBy(['lieuVente' => $lieu_vente], ['ordre' => 'ASC']),
            'type_menu' => $type_menu,
            'types_menu' => $types_menu,
            'liste_menu' => $liste_menu,
            'caisses' => $caisseRep->findCaisseByLieu($lieu_vente),
            'modes_paie' => $modePaieRep->findAll(),
            'type' => $type,// le type de cocktail ou type d'alcool 
            'commandes' => $commandes,
            'emplacement' => $emplacement,
            'type_vente' => $type_vente,
            'session_client' => $session_client,
            'session_remise_glob' => $session_remise_glob,
            'session_montant_paye' => $session_montant_paye,
            'session_frais_sup' => $session_frais_sup,
        ]);
    }

    #[Route('/{id}', name: 'app_nhema_vente_table_commande_show', methods: ['GET'])]
    public function show(TableCommande $tableCommande): Response
    {
        return $this->render('nhema/vente/table_commande/show.html.twig', [
            'table_commande' => $tableCommande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_nhema_vente_table_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TableCommande $tableCommande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TableCommandeType::class, $tableCommande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_nhema_vente_table_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/vente/table_commande/edit.html.twig', [
            'table_commande' => $tableCommande,
            'form' => $form,
        ]);
    }

    #[Route('/delete{id}/{lieu_vente}', name: 'app_nhema_vente_table_commande_delete', methods: ['POST'])]
    public function delete(Request $request, LieuxVentes $lieu_vente, TableCommande $tableCommande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tableCommande->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tableCommande);
            $entityManager->flush();
            $session = $request->getSession();
            $session->remove("session_remise_glob");
            $session->remove("montant_paye");
            $session->remove("frais_sup");
        }
        $this->addFlash("success","produit supprimé avec succès :)");
        // $referer = $request->headers->get('referer');
        // if ($referer) {
        //     return new RedirectResponse($referer);
        // }
        return $this->redirectToRoute('app_nhema_vente_table_commande_new', ['lieu_vente' => $lieu_vente->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/preparation/{service}/{lieu_vente}', name: 'app_nhema_vente_table_commande_preparation', methods: ['GET'])]
    public function preparation(Service $service, LieuxVentes $lieu_vente, TableCommandeRepository $tableCommandeRep, TableRepository $tableRep, ServiceRepository $serviceRep, SalleRepository $salleRep, Request $request, EntrepriseRepository $entrepriseRep, EntityManagerInterface $em): Response
    {
        if ($request->get("statut")) {
            $commande = $tableCommandeRep->find($request->get("id_cmd"));
            $commande->setStatut($request->get("statut"))
                    ->setDateSaisie(new \DateTime("now"));
            if ($request->get("statut") == 'prise en charge') {
                $commande->setPreparePar($this->getUser());
            }else{
                $commande->setServiePar($this->getUser());
            }

            $em->persist($commande);
            $em->flush();
        }
        if ($this->getUser()->getRoles()[0] == 'ROLE_CUISINIER') {
            $commandes = $tableCommandeRep->CommandesStatutCuisine($service, $lieu_vente);
        }elseif($this->getUser()->getRoles()[0] == 'ROLE_SERVEUR'){
            $commandes = $tableCommandeRep->findBy(['statut' => 'prête', 'lieuVente' => $lieu_vente], ['statut' => 'ASC']);
        }else{
            $commandes = $tableCommandeRep->findBy(['service' => $service, 'lieuVente' => $lieu_vente], ['statut' => 'ASC']);
        }
        return $this->render('nhema/vente/table_commande/preparation.html.twig', [
            'liste_commandes' => $commandes,
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
            'services' => $serviceRep->findAll(),
            'service' => $service,
        ]);
    }

    #[Route('/addition/{table}/{lieu_vente}', name: 'app_nhema_vente_table_commande_addition')]
    public function addition(Table $table, LieuxVentes $lieu_vente, TableCommandeRepository $tableCommandeRep)
    {  
        $logoPath = $this->getParameter('kernel.project_dir') . '/public/images/logos/'.$lieu_vente->getEntreprise()->getLogo();
        $logoBase64 = base64_encode(file_get_contents($logoPath));

        $commandes = $tableCommandeRep->findBy(['emplacement' => $table]);

        $html = $this->renderView('nhema/vente/table_commande/addition.html.twig', [
            'commandes' => $commandes,
            'logoPath' => $logoBase64,
            'lieu_vente' => $lieu_vente,
            'table' => $table,
            // 'qrCode'    => $qrCode,
        ]);

        // Configurez Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set("isPhpEnabled", true);
        $options->set("isHtml5ParserEnabled", true);

        // Instancier Dompdf
        $dompdf = new Dompdf($options);

        // Charger le contenu HTML
        $dompdf->loadHtml($html);

        // Définir la taille du papier (A4 par défaut)
        $dompdf->setPaper('A4', 'portrait');

        // Rendre le PDF (stream le PDF au navigateur)
        $dompdf->render();

        // Renvoyer une réponse avec le contenu du PDF
        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="addition.pdf"',
        ]);
    }
}
