<?php

namespace App\Controller\Nhema\Vente;

use App\Entity\Facturation;
use App\Entity\LieuxVentes;
use App\Entity\TableCommande;
use App\Entity\CommandeProduct;
use App\Entity\MouvementCaisse;
use App\Entity\MouvementProduct;
use App\Repository\PlatRepository;
use App\Repository\UserRepository;
use App\Repository\StockRepository;
use App\Repository\TableRepository;
use App\Entity\ModificationCommande;
use App\Entity\ModificationFacture;
use App\Repository\CaisseRepository;
use App\Repository\ChichaRepository;
use App\Repository\DeviseRepository;
use App\Repository\BoissonRepository;
use App\Repository\ProductRepository;
use App\Repository\RecetteRepository;
use App\Entity\MouvementCollaborateur;
use App\Form\ModificationCommandeType;
use App\Repository\CocktailRepository;
use App\Repository\TypePlatRepository;
use App\Repository\MenuVenteRepository;
use App\Repository\EntrepriseRepository;
use App\Repository\ListeStockRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FacturationRepository;
use App\Repository\ModePaiementRepository;
use App\Repository\TypeCocktailRepository;
use App\Repository\TableCommandeRepository;
use App\Repository\LiaisonProductRepository;
use App\Repository\CommandeProductRepository;
use App\Repository\CompteOperationRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CategorieBoissonRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CategorieOperationRepository;
use App\Repository\ModificationCommandeRepository;
use App\Repository\MouvementCaisseRepository;
use App\Repository\MouvementCollaborateurRepository;
use App\Repository\MouvementProductRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/nhema/vente/modification/commande')]
class ModificationCommandeController extends AbstractController
{
    #[Route('/', name: 'app_nhema_vente_modification_commande_index', methods: ['GET'])]
    public function index(ModificationCommandeRepository $modificationCommandeRepository): Response
    {
        return $this->render('nhema/vente/modification_commande/index.html.twig', [
            'modification_commandes' => $modificationCommandeRepository->findAll(),
        ]);
    }

    #[Route('/modification/{id}/{lieu_vente}', name: 'app_nhema_vente_modification_commande_new', methods: ['GET', 'POST'])]
    public function new(Facturation $facture, CommandeProductRepository $commandeProdRep, Request $request, FacturationRepository $facturationRepository, LieuxVentes $lieu_vente, TypePlatRepository $typePlatRep, SessionInterface $session, ProductRepository $productRep, CocktailRepository $cocktailRep, CaisseRepository $caisseRep, TypeCocktailRepository $typeCocktailRep, CategorieBoissonRepository $categorieBoissonRep, BoissonRepository $boissonRep, MenuVenteRepository $menuVenteRep, ModePaiementRepository $modePaieRep, TableCommandeRepository $tableCommandeRep, UserRepository $userRep, ChichaRepository $chichaRep, PlatRepository $platRep, TableRepository $tableRep, ModificationCommandeRepository $modifCmdRep, EntrepriseRepository $entrepriseRep, EntityManagerInterface $em): Response
    {
        // on stock la table dans une session 
        $session_jour = $facture->getDateFacturation();
        $session_table = $session->get('session_table');
        $session_table = $facture->getTableCommande();
        $session->set('session_table', $session_table);
        $type_vente = $facture->getTypeVente(); // on recupère le type de vente (surplace, emporter, livraison)

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

        $emplacement = $session_table ? $tableRep->findOneBy(['nom' =>$session_table]) : null;

        // gestion de la modification de la commande
        if ($request->get('id_prod_modif')) {
            $id_prod = $request->get('id_prod_modif');
            $quantite = $request->get('qtite');
            $commentaire = $request->get('commentaire');
            $commandeModif = $modifCmdRep->find($id_prod);
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
            
            $tableCommande =$modifCmdRep->findOneBy(['product' => $product, 'modifierPar' => $this->getUser(), 'emplacement' => $emplacement]);
            
            // on verifie si le produit existe déjà dans le panier si oui on incremente la quantité
            if ($tableCommande) {
                $tableCommande->setQuantite($tableCommande->getQuantite() + 1);
            }else{
                $tableCommande = new ModificationCommande();
                $tableCommande->setQuantite(1)
                        ->setProduct($product)
                        ->setPrixVente($product->getPrixVente())
                        ->setEmplacement($emplacement)
                        ->setModifierPar($this->getUser())
                        ->setTypeVente($type_vente)
                        ->setStatut("commande")
                        ->setDateCommande($session_jour)
                        ->setDateSaisie(new \DateTime("now"));
            }
            $em->persist($tableCommande);
            $em->flush();

            return new RedirectResponse($this->generateUrl('app_nhema_vente_modification_commande_new', ['id'=> $facture->getId(),'nom_menu' => 'chichas', 'lieu_vente' => $lieu_vente->getId()]));   
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
                    $tableCommande =$modifCmdRep->findOneBy(['product' => $product, 'modifierPar' => $this->getUser(), 'emplacement' => $emplacement]);
                    // on verifie si le produit existe déjà dans le panier si oui on incremente la quantité
                    if ($tableCommande) {
                        $tableCommande->setQuantite($tableCommande->getQuantite() + 1);
                    }else{
                        $tableCommande = new ModificationCommande();
                        $tableCommande->setQuantite(1)
                                ->setProduct($product)
                                ->setPrixVente($product->getPrixVente())
                                ->setEmplacement($emplacement)
                                ->setModifierPar($this->getUser())
                                ->setTypeVente($type_vente)
                                ->setStatut("commande")
                                ->setDateCommande($session_jour)
                                ->setDateSaisie(new \DateTime("now"));
                    }
                    $em->persist($tableCommande);
                    $em->flush();

                    return new RedirectResponse($this->generateUrl('app_nhema_vente_modification_commande_new', ['id'=> $facture->getId(),'type' => $type, 'nom_menu' => 'type_cocktail', 'lieu_vente' => $lieu_vente->getId()]));   
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
                    $tableCommande =$modifCmdRep->findOneBy(['product' => $product, 'modifierPar' => $this->getUser(), 'emplacement' => $emplacement]);
                    // on verifie si le produit existe déjà dans le panier si oui on incremente la quantité
                    if ($tableCommande) {
                        $tableCommande->setQuantite($tableCommande->getQuantite() + 1);
                    }else{
                        $tableCommande = new ModificationCommande();
                        $tableCommande->setQuantite(1)
                        ->setProduct($product)
                        ->setPrixVente($product->getPrixVente())
                        ->setEmplacement($emplacement)
                        ->setModifierPar($this->getUser())
                        ->setTypeVente($type_vente)
                        ->setStatut("commande")
                        ->setDateCommande($session_jour)
                        ->setDateSaisie(new \DateTime("now"));
                    }
                    $em->persist($tableCommande);
                    $em->flush();

                    return new RedirectResponse($this->generateUrl('app_nhema_vente_modification_commande_new', ['id'=> $facture->getId(),'categorie' => $categorie, 'type_vente' => $type_vente, 'nom_menu' => 'choix_alcool', 'lieu_vente' => $lieu_vente->getId()]));   
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
                    $tableCommande =$modifCmdRep->findOneBy(['product' => $product, 'modifierPar' => $this->getUser(), 'emplacement' => $emplacement]);
                    // on verifie si le produit existe déjà dans le panier si oui on incremente la quantité
                    if ($tableCommande) {
                        $tableCommande->setQuantite($tableCommande->getQuantite() + 1);
                    }else{
                        $tableCommande = new ModificationCommande();
                        $tableCommande->setQuantite(1)
                        ->setProduct($product)
                        ->setPrixVente($product->getPrixVente())
                        ->setEmplacement($emplacement)
                        ->setModifierPar($this->getUser())
                        ->setTypeVente($type_vente)
                        ->setStatut("commande")
                        ->setDateCommande($session_jour)
                        ->setDateSaisie(new \DateTime("now"));
                    }
                    $em->persist($tableCommande);
                    $em->flush();

                    return new RedirectResponse($this->generateUrl('app_nhema_vente_modification_commande_new', ['id'=> $facture->getId(),'id_menu' => $type, 'nom_menu' => 'bières', 'lieu_vente' => $lieu_vente->getId()]));   
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
                    $tableCommande =$modifCmdRep->findOneBy(['product' => $product, 'modifierPar' => $this->getUser(), 'emplacement' => $emplacement]);
                    // on verifie si le produit existe déjà dans le panier si oui on incremente la quantité
                    if ($tableCommande) {
                        $tableCommande->setQuantite($tableCommande->getQuantite() + 1);
                    }else{
                        $tableCommande = new ModificationCommande();
                        $tableCommande->setQuantite(1)
                        ->setProduct($product)
                        ->setPrixVente($product->getPrixVente())
                        ->setEmplacement($emplacement)
                        ->setModifierPar($this->getUser())
                        ->setTypeVente($type_vente)
                        ->setStatut("commande")
                        ->setDateCommande($session_jour)
                        ->setDateSaisie(new \DateTime("now"));
                    }
                    $em->persist($tableCommande);
                    $em->flush();

                    return new RedirectResponse($this->generateUrl('app_nhema_vente_modification_commande_new', ['id'=> $facture->getId(),'id_menu' => $type_menu , 'nom_menu' => 'boissons', 'lieu_vente' => $lieu_vente->getId()]));   
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
                    $tableCommande =$modifCmdRep->findOneBy(['product' => $product, 'modifierPar' => $this->getUser(), 'emplacement' => $emplacement]);
                    // on verifie si le produit existe déjà dans le panier si oui on incremente la quantité
                    if ($tableCommande) {
                        $tableCommande->setQuantite($tableCommande->getQuantite() + 1);
                    }else{
                        $tableCommande = new ModificationCommande();
                        $tableCommande->setQuantite(1)
                        ->setProduct($product)
                        ->setPrixVente($product->getPrixVente())
                        ->setEmplacement($emplacement)
                        ->setModifierPar($this->getUser())
                        ->setTypeVente($type_vente)
                        ->setStatut("commande")
                        ->setDateCommande($session_jour)
                        ->setDateSaisie(new \DateTime("now"));
                    }
                    $em->persist($tableCommande);
                    $em->flush();

                    return new RedirectResponse($this->generateUrl('app_nhema_vente_modification_commande_new', ['id'=> $facture->getId(),'id_menu' => $type_menu , 'nom_menu' => 'boissons chaudes', 'lieu_vente' => $lieu_vente->getId()]));   
                }

            }elseif ($nom_menu == 'chichas') {
                $liste_menu = $chichaRep->findBy(['etat' => 'actif'], ['nom' => 'ASC']);

                $types_menu = array();
                $type_menu = 'chichas';
                $type = array();

                if ($request->get('id_prod')) {
                    $id_prod = $request->get('id_prod');
                    $product = $chichaRep->find($id_prod);
                    $tableCommande =$modifCmdRep->findOneBy(['product' => $product, 'modifierPar' => $this->getUser(), 'emplacement' => $emplacement]);
                    // on verifie si le produit existe déjà dans le panier si oui on incremente la quantité
                    if ($tableCommande) {
                        $tableCommande->setQuantite($tableCommande->getQuantite() + 1);
                    }else{
                        // dd($session_jour);
                        $tableCommande = new ModificationCommande();
                        $tableCommande->setQuantite(1)
                        ->setProduct($product)
                        ->setPrixVente($product->getPrixVente())
                        ->setEmplacement($emplacement)
                        ->setModifierPar($this->getUser())
                        ->setTypeVente($type_vente)
                        ->setStatut("commande")
                        ->setDateCommande($session_jour)
                        ->setDateSaisie(new \DateTime("now"));
                    }
                    $em->persist($tableCommande);
                    $em->flush();

                    return new RedirectResponse($this->generateUrl('app_nhema_vente_modification_commande_new', ['id'=> $facture->getId(),'id_menu' => $type_menu , 'nom_menu' => 'chichas', 'lieu_vente' => $lieu_vente->getId()]));   
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
                    $tableCommande =$modifCmdRep->findOneBy(['product' => $product, 'modifierPar' => $this->getUser(), 'emplacement' => $emplacement]);
                    // on verifie si le produit existe déjà dans le panier si oui on incremente la quantité
                    if ($tableCommande) {
                        $tableCommande->setQuantite($tableCommande->getQuantite() + 1);
                    }else{
                        $tableCommande = new ModificationCommande();
                        $tableCommande->setQuantite(1)
                        ->setProduct($product)
                        ->setPrixVente($product->getPrixVente())
                        ->setEmplacement($emplacement)
                        ->setModifierPar($this->getUser())
                        ->setTypeVente($type_vente)
                        ->setStatut("commande")
                        ->setDateCommande($session_jour)
                        ->setDateSaisie(new \DateTime("now"));
                    }
                    $em->persist($tableCommande);
                    $em->flush();

                    return new RedirectResponse($this->generateUrl('app_nhema_vente_modification_commande_new', ['id'=> $facture->getId(),'id_plat' => $id_plat , 'nom_menu' => 'plat', 'lieu_vente' => $lieu_vente->getId()]));   
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
                    $tableCommande =$modifCmdRep->findOneBy(['product' => $product, 'modifierPar' => $this->getUser(), 'emplacement' => $emplacement]);
                    // on verifie si le produit existe déjà dans le panier si oui on incremente la quantité
                    if ($tableCommande) {
                        $tableCommande->setQuantite($tableCommande->getQuantite() + 1);
                    }else{
                        $tableCommande = new ModificationCommande();
                        $tableCommande->setQuantite(1)
                        ->setProduct($product)
                        ->setPrixVente($product->getPrixVente())
                        ->setEmplacement($emplacement)
                        ->setModifierPar($this->getUser())
                        ->setTypeVente($type_vente)
                        ->setStatut("commande")
                        ->setDateCommande($session_jour)
                        ->setDateSaisie(new \DateTime("now"));
                    }
                    $em->persist($tableCommande);
                    $em->flush();

                    return new RedirectResponse($this->generateUrl('app_nhema_vente_modification_commande_new', ['id'=> $facture->getId(),'id_menu' => $type_menu , 'nom_menu' => 'chichas', 'lieu_vente' => $lieu_vente->getId()]));   
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
        // inserertion des commandes à modifier dans la table 
        if ($request->get('modif_facture')) {
            $commandesModif = $commandeProdRep->findBy(['facturation' => $facture]);
            foreach ($commandesModif as $cmd) {
                $em->remove($cmd);
                $em->flush();
            }
            foreach ($commandesModif as $cmd) {
                $commande = new ModificationCommande();
                $commande->setQuantite($cmd->getQuantite())
                        ->setPrixVente($cmd->getPrixVente())
                        ->setDateCommande($facture->getDateFacturation())
                        ->setDateSaisie(new \DateTime("now"))
                        ->setTypeVente($facture->getTypeVente())
                        ->setModifierPar($this->getUser())
                        ->setStatut('commande')
                        ->setEmplacement($emplacement)
                        ->setProduct($cmd->getProduct());
                $em->persist($commande);
                $em->flush();
            }

            $session_remise_glob = $facture->getRemise();
            $session_montant_paye = $facture->getMontantPaye();
            $session_frais_sup = $facture->getFraisSup();
            $session_client = $facture->getClient();

        }
        $session->set("session_remise_glob", $session_remise_glob);
        $session->set('montant_paye', $session_montant_paye);
        $session->set('frais_sup', $session_frais_sup);
        $session->set("session_client", $session_client);

        // recupération des commandes par table
        $commandes = $modifCmdRep->findBy(['emplacement' => $emplacement, 'modifierPar' => $this->getUser()]);
        return $this->render('nhema/vente/facturation/vente_modification.html.twig', [
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
            'facture' => $facture
        ]);
    }

    #[Route('/{id}', name: 'app_nhema_vente_modification_commande_show', methods: ['GET'])]
    public function show(ModificationCommande $modificationCommande): Response
    {
        return $this->render('nhema/vente/modification_commande/show.html.twig', [
            'modification_commande' => $modificationCommande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_nhema_vente_modification_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ModificationCommande $modificationCommande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ModificationCommandeType::class, $modificationCommande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_nhema_vente_modification_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/vente/modification_commande/edit.html.twig', [
            'modification_commande' => $modificationCommande,
            'form' => $form,
        ]);
    }

    #[Route('/delete{id}/{lieu_vente}/{facture}', name: 'app_nhema_vente_modification_commande_delete', methods: ['POST'])]
    public function delete(Request $request, LieuxVentes $lieu_vente, Facturation $facture, ModificationCommande $tableCommande, EntityManagerInterface $entityManager): Response
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
        return $this->redirectToRoute('app_nhema_vente_modification_commande_new', ['id' => $facture->getId(), 'lieu_vente' => $lieu_vente->getId()], Response::HTTP_SEE_OTHER);
    }


    #[Route('/valdation/{facture}/{lieu_vente}', name: 'app_nhema_vente_modification_commande_validation')]
    public function validation(Facturation $facture, LieuxVentes $lieu_vente, Request $request, ModificationCommandeRepository $tableCmdRep, TableRepository $tableRep, FacturationRepository $facturationRep, StockRepository $stockRep, ListeStockRepository $listeStockRep, CaisseRepository $caisseRep, DeviseRepository $deviseRep, CategorieOperationRepository $categorieOpRep, CompteOperationRepository $compteOpRep,  SessionInterface $session, ModePaiementRepository $modePaieRep, UserRepository $userRep, RecetteRepository $recetteRep, ProductRepository $productRep, LiaisonProductRepository $liaisonRep, BoissonRepository $boissonRep, MouvementCaisseRepository $mouvementCaisseRep, MouvementProductRepository $mouvementProdRep, CommandeProductRepository $commandeProdRep, MouvementCollaborateurRepository $mouvementCollabRep, EntityManagerInterface $em): Response
    {
        $session_jour = $facture->getDateFacturation();
        $session_client = $session->get("session_client", '');
        $emplacement = $request->get('emplacement');
        $table = $tableRep->find($emplacement);
        $tableCmd = $tableCmdRep->findBy(['emplacement' => $table]);
        $emplacement = $table->getNom();
        $typeVente = $request->get('type_vente');
        // on met à jour le stock par defaut le magasin principal
        $lieuLivraison = $listeStockRep->findOneBy(['lieuVente' => $lieu_vente], ['id' => 'ASC']);
        
        if ($tableCmd) {
            $devise = $deviseRep->find(1);
            $dateDuJour = new \DateTime();
            $totalFacture =  floatval(preg_replace('/[^-0-9,.]/', '', $request->get('totalFacture')));
            $totalFacture = $totalFacture ? $totalFacture : 0;
            $montantPaye =  floatval(preg_replace('/[^-0-9,.]/', '', $request->get('montantPaye')));
            $montantPaye = $montantPaye ? $montantPaye : 0;
            $reste =  floatval(preg_replace('/[^-0-9,.]/', '', $request->get('reste')));
            $reste = $reste ? $reste : 0;
            $rendu =  floatval(preg_replace('/[^-0-9,.]/', '', $request->get('rendu')));
            $rendu = $rendu ? $rendu : 0;
            $montantRemise =  floatval(preg_replace('/[^-0-9,.]/', '', $request->get('montantRemise')));
            $frais =  floatval(preg_replace('/[^-0-9,.]/', '', $request->get('frais')));
            $frais = $frais ? $frais : 0 ;

            $caisse = $request->get('caisse') ? $caisseRep->find($request->get('caisse')) : null;

            $modePaie = $request->get('mode_paie') ? $modePaieRep->find($request->get('mode_paie')) : null;
            $commentaire = $request->get('commentaire');

            $dateVente = $session_jour ? $session_jour : $dateDuJour ;

            $client = $session_client ? $userRep->find($session_client->getId()) : null;

            // preparation de la facture
            if ($montantPaye > $totalFacture) {
                // gestion dans le cas il y a un rendu
                $montantPaye = $totalFacture;
            }
            // on supprime l'ancienne commande et mis à jour du stock
            $commandes = $commandeProdRep->findBy(['facturation' => $facture]);
            $mouvementsProduct = $mouvementProdRep->findBy(['commande' => $commandes]);
            foreach ($mouvementsProduct as $mouvementProd) {
                $stock_maj = $stockRep->findOneBy(['emplacement' =>$mouvementProd->getStockProduct(), 'product' => $mouvementProd->getProduct()]);
                if ($mouvementProd->getOrigine() == 'liaison produit') {
                    $stock_maj->setQuantite($stock_maj->getQuantite() + ($mouvementProd->getQuantite()));
                }else{
                    $stock_maj->setQuantite($stock_maj->getQuantite() - ($mouvementProd->getQuantite()));
                }
                $em->persist($stock_maj);
            }
            foreach ($commandes as $commande) {
                // on supprime la commande et automatiquement ça supprime le mouvement des produits
                $em->remove($commande);
                $em->flush();
            }

            // on stock la modification dans la table historique des modifictaions
            $modificationFacture = new ModificationFacture();
            $modificationFacture->setFacture($facture)
                    ->setMontantTotal($facture->getMontantTotal())
                    ->setMontantPaye($facture->getMontantPaye())
                    ->setRemise($facture->getRemise())
                    ->setFraisSup($facture->getFraisSup())
                    ->setNumeroFacture($facture->getNumeroFacture())
                    ->setClient($facture->getClient())
                    ->setCaisse($facture->getCaisse())
                    ->setModePaie($facture->getModePaie())
                    ->setLieuVente($lieu_vente)
                    ->setSaisiePar($this->getUser())
                    ->setDateFacturation($facture->getDateFacturation())
                    ->setDateSaisie(new \DateTime("now"));
            $em->persist($modificationFacture);


            // on modifie la facture 
            $facture->setMontantTotal($totalFacture)
                    ->setMontantPaye($montantPaye)
                    ->setRemise($montantRemise)
                    ->setFraisSup($frais)
                    ->setClient($client)
                    ->setEtat(($totalFacture == $montantPaye) ? "payé" : ($montantPaye ? 'paie partiel' : 'non payé'))
                    ->setTableCommande($emplacement)
                    ->setCaisse($caisse)
                    ->setModePaie($modePaie)
                    ->setTypeVente($typeVente)
                    ->setCommentaire($commentaire)
                    ->setLieuVente($lieu_vente)
                    ->setSaisiePar($this->getUser())
                    ->setDateFacturation($dateVente);
            $em->persist($facture);

            // enregistrement dans le mouvement caisse

            if ($montantPaye) {
                // on modifie la caisse 
                $mouvementCaisse = $mouvementCaisseRep->findOneBy(['facturation' => $facture]);
                $mouvementCaisse->setCaisse($caisse)
                        ->setModePaie($modePaieRep->find(4))
                        ->setMontant($montantPaye)
                        ->setSaisiePar($this->getUser())
                        ->setModePaie($modePaie)
                        ->setDateOperation($dateVente);
                $em->persist($mouvementCaisse);
            }
            $ancienMouvementCollab = $mouvementCollabRep->findOneBy(['facture' => $facture]);
            // on supprime le mouvement s'il existe
            if ($ancienMouvementCollab) {
                $em->remove($ancienMouvementCollab);
            }
            // enregistrement dans le compte du collaborateur s'il n y a pas de paiement
            if ($session_client) {
                $mouvementCollab = new MouvementCollaborateur ();
                $mouvementCollab->setCollaborateur($client)
                        ->setOrigine('facturation')
                        ->setMontant(-$rendu)
                        ->setDevise($devise)
                        ->setCaisse($caisse)
                        ->setLieuVente($lieu_vente)
                        ->setTraitePar($this->getUser())
                        ->setDateOperation($dateVente)
                        ->setDateSaisie(new \DateTime('now'));
                $facture->addMouvementCollaborateur($mouvementCollab);
                $em->persist($mouvementCollab);
            }

            // insertion des produits dans la bdd
            foreach ($tableCmd as  $item) {
                $commande = new CommandeProduct();
                $product = $productRep->find($item->getProduct());
                $commande->setProduct($item->getProduct())
                        ->setPrixVente($item->getPrixVente())
                        ->setQuantite($item->getQuantite());
                $facture->addCommandeProduct($commande);

                $stocks = $stockRep->findOneBy(['emplacement' => $lieuLivraison, 'product' => $item->getProduct()]);

                $stocks->setQuantite($stocks->getQuantite() - $item->getQuantite());
                $em->persist($stocks);
                // on insere dans le mouvement product
                $mouvementProduct = new MouvementProduct();
                $mouvementProduct->setStockProduct($lieuLivraison)
                            ->setProduct($product)
                            ->setQuantite(-$item->getQuantite())
                            ->setLieuVente($lieu_vente)
                            ->setPersonnel($this->getUser())
                            ->setDateOperation($dateVente)
                            ->setOrigine("vente direct")
                            ->setDescription("vente direct")
                            ->setClient($client);
                $commande->addMouvementProduct($mouvementProduct);

                // gestion de la recette pour mettre à jour le stock
                $recettes = $recetteRep->findBy(['product' => $product]);
                // si on trouve une recette on ajuste les stocks
                $recettes = array_reverse($recettes);
                if ($recettes) {
                    // $recettes = array_reverse($recettes);
                    foreach ($recettes as $recette) {

                        $ingredient = $recette->getIngredient();
                        $stock_ingredient = $stockRep->findOneBy(['product' => $ingredient, 'emplacement' => $lieuLivraison]);

                        $qtite_recette = $recette->getQuantite() * $item->getQuantite();

                        $stock_ingredient->setQuantite($stock_ingredient->getQuantite() - $qtite_recette);

                        $mouvement_ingredient = new MouvementProduct();
                        $mouvement_ingredient->setStockProduct($lieuLivraison)
                                    ->setProduct($ingredient)
                                    ->setQuantite(-$qtite_recette)
                                    ->setLieuVente($lieu_vente)
                                    ->setPersonnel($this->getUser())
                                    ->setDateOperation($dateVente)
                                    ->setOrigine("recette")
                                    ->setDescription("recette")
                                    ->setClient($client);
                        $commande->addMouvementProduct($mouvement_ingredient);
                        $em->persist($mouvement_ingredient);

                        // si c'est une boisson et que la quantité est negative on retire une bouteille
                        if ($ingredient instanceof \App\Entity\Boisson) {
                            // gestion de la liaison des produits dans le cas ou la quantité restante est <= 0
                            if($stock_ingredient->getQuantite() >= 0 ) {
                                $liaison = $liaisonRep->findOneBy(['product2' => $ingredient]);

                                $bouteille = $liaison->getProduct1();
                                $bouteille = $boissonRep->find($bouteille);

                                $stock_bouteille = $stockRep->findOneBy(['product' => $bouteille, 'emplacement' => $lieuLivraison]);

                                // on ajoute une bouteille dans le detail
                                $stock_ingredient->setQuantite($stock_ingredient->getQuantite() - $bouteille->getVolume());

                                $stock_bouteille->setQuantite($stock_bouteille->getQuantite() + 1);

                                // on reajuste le mouvement de stock
                                $mouvement_verre = new MouvementProduct();
                                $mouvement_verre->setStockProduct($lieuLivraison)
                                            ->setProduct($ingredient)
                                            ->setQuantite($bouteille->getVolume())
                                            ->setLieuVente($lieu_vente)
                                            ->setPersonnel($this->getUser())
                                            ->setDateOperation($dateVente)
                                            ->setOrigine("liaison produit")
                                            ->setDescription("liaison produit")
                                            ->setClient($client);
                                $commande->addMouvementProduct($mouvement_verre);
                                $em->persist($mouvement_verre);

                                $mouvement_bouteille = new MouvementProduct();
                                $mouvement_bouteille->setStockProduct($lieuLivraison)
                                            ->setProduct($bouteille)
                                            ->setQuantite(-1)
                                            ->setLieuVente($lieu_vente)
                                            ->setPersonnel($this->getUser())
                                            ->setDateOperation($dateVente)
                                            ->setOrigine("liaison produit")
                                            ->setDescription("liaison produit")
                                            ->setClient($client);
                                $commande->addMouvementProduct($mouvement_bouteille);

                                $em->persist($mouvement_bouteille);
                                $em->persist($stock_bouteille);
                                $em->persist($stock_ingredient);

                            }
                        }
                    }
                }


                
                $em->persist($commande);
                $em->persist($mouvementProduct);
                
            }
            foreach ($tableCmd as $value) {
                $em->persist($value);
                $em->remove($value);
            }
            $table->setEtat('libre');
            $em->persist($table);
            $em->flush();
            // on vide les sessions et on redirige vers la facture
            $session = $request->getSession();
            $session->remove("session_remise_glob");
            $session->remove("montant_paye");
            $session->remove("frais_sup");
            $session->remove("session_client");
            $session->remove("session_type_vente");
            $session->remove("session_table");


        }
        $this->addFlash("success", "Vente modifié avec succès :)"); 
        return $this->redirectToRoute("app_nhema_vente_facturation_show", ['id'=> $facture->getId(), 'lieu_vente' => $lieu_vente->getId()]);
    } 
}
