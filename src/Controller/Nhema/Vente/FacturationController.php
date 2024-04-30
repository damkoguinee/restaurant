<?php

namespace App\Controller\Nhema\Vente;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Facturation;
use App\Entity\LieuxVentes;
use App\Entity\TableCommande;
use App\Form\FacturationType;
use App\Entity\LiaisonProduct;
use App\Entity\CommandeProduct;
use App\Entity\ModificationFacture;
use App\Entity\MouvementCaisse;
use App\Entity\MouvementProduct;
use App\Repository\UserRepository;
use App\Repository\StockRepository;
use App\Repository\TableRepository;
use App\Repository\CaisseRepository;
use App\Repository\ClientRepository;
use App\Repository\DeviseRepository;
use App\Repository\BoissonRepository;
use App\Repository\ProductRepository;
use App\Repository\RecetteRepository;
use App\Entity\MouvementCollaborateur;
use App\Entity\SuppressionFacture;
use App\Entity\Table;
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
use App\Repository\MouvementCaisseRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CategorieOperationRepository;
use App\Repository\ModificationFactureRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\MouvementCollaborateurRepository;
use App\Repository\MouvementProductRepository;
use App\Repository\SuppressionFactureRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

#[Route('/nhema/vente/facturation')]
class FacturationController extends AbstractController
{
    #[Route('/detail/vente/{lieu_vente}', name: 'app_nhema_vente_facturation_detail_vente', methods: ['GET'])]
    public function vente(LieuxVentes $lieu_vente, Request $request, SessionInterface $session, EntrepriseRepository $entrepriseRep, CommandeProductRepository $commandeProdRep, EntityManagerInterface $em): Response
    {
        $session_jour = $session->get("session_jour")->getJourDeTravail();
        if ($request->get("date1")){
            $date1 = $request->get("date1");
            $date2 = $request->get("date2");
        }else{
            $date1 = $session_jour->format("Y-m-d");
            $date2 = $session_jour->format("Y-m-d");
        }
        $pageEncours = $request->get('pageEncours', 1);
        $commandes = $commandeProdRep->listeDesProduitsVendusParPeriodeParLieuPagine($date1, $date2, $lieu_vente, $pageEncours, 30);
        return $this->render('nhema/vente/facturation/detail_vente.html.twig', [
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
            'commandes' => $commandes,
            'date1' => $date1,
            'date2' => $date2,
            'search' => ""
        ]);
    }

    #[Route('/produit/vente/{lieu_vente}', name: 'app_nhema_vente_facturation_produit_vente', methods: ['GET'])]
    public function venteProduit(LieuxVentes $lieu_vente, Request $request, SessionInterface $session, EntrepriseRepository $entrepriseRep, CommandeProductRepository $commandeProdRep, EntityManagerInterface $em): Response
    {
        $session_jour = $session->get("session_jour")->getJourDeTravail();
        if ($request->get("date1")){
            $date1 = $request->get("date1");
            $date2 = $request->get("date2");
        }else{
            $date1 = $session_jour->format("Y-m-d");
            $date2 = $session_jour->format("Y-m-d");
        }

        $commandes = $commandeProdRep->listeDesProduitsVendusGroupeParPeriodeParLieu($date1, $date2, $lieu_vente);

        $commandes_plat = [];
        $commandes_boisson = [];
        $commandes_chicha = [];
        $commandes_cocktail = [];
        $commandes_autres = [];
        foreach ($commandes as $value) {
            $product = $value['commandes']->getProduct();
            
            if ($product instanceof \App\Entity\Plat) {
                $commandes_plat[] = [
                    'nbre' => $value['nbre'],
                    'commandes' => $value['commandes']
                ];
            }elseif ($product instanceof \App\Entity\Boisson) {
                $commandes_boisson[] = [
                    'nbre' => $value['nbre'],
                    'commandes' => $value['commandes']
                ];
            }elseif($product instanceof \App\Entity\Chicha) {
                $commandes_chicha[] = [
                    'nbre' => $value['nbre'],
                    'commandes' => $value['commandes']
                ];
            }elseif($product instanceof \App\Entity\Cocktail) {
                $commandes_cocktail[] = [
                    'nbre' => $value['nbre'],
                    'commandes' => $value['commandes']
                ];
            }else {
                $commandes_autres[] = [
                    'nbre' => $value['nbre'],
                    'commandes' => $value['commandes']
                ];
            }
        }

        $commandes_par_type = [
            'Plats' => $commandes_plat,
            'Boissons' => $commandes_boisson,
            'Chichas' => $commandes_chicha,
            'Cocktails' => $commandes_cocktail,
            'Autres' => $commandes_autres
        ];

        return $this->render('nhema/vente/facturation/produits_vendus.html.twig', [
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
            'commandes_par_type' => $commandes_par_type,
            'date1' => $date1,
            'date2' => $date2,
            'search' => ""
        ]);
    }

    #[Route('/vente/modifier/{lieu_vente}', name: 'app_nhema_vente_facturation_vente_modifier', methods: ['GET'])]
    public function venteModifie(LieuxVentes $lieu_vente, Request $request, SessionInterface $session, EntrepriseRepository $entrepriseRep, ModificationFactureRepository $modificationRep): Response
    {
        $session_jour = $session->get("session_jour")->getJourDeTravail();
        if ($request->get("date1")){
            $date1 = $request->get("date1");
            $date2 = $request->get("date2");
        }else{
            $date1 = date("Y-m-d");
            $date2 = date("Y-m-d");
        }
        $pageEncours = $request->get('pageEncours', 1);
        $commandes = $modificationRep->commandesModifieesParPeriodeParLieuPagine($date1, $date2, $lieu_vente, $pageEncours, 30);
        return $this->render('nhema/vente/facturation/commandes_modifiees.html.twig', [
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
            'commandes' => $commandes,
            'date1' => $date1,
            'date2' => $date2,
            'search' => ""
        ]);
    }

    #[Route('/vente/supprimer/{lieu_vente}', name: 'app_nhema_vente_facturation_vente_supprimer', methods: ['GET'])]
    public function venteSupprimer(LieuxVentes $lieu_vente, Request $request, SessionInterface $session, EntrepriseRepository $entrepriseRep, SuppressionFactureRepository $suppressionRep): Response
    {
        $session_jour = $session->get("session_jour")->getJourDeTravail();
        if ($request->get("date1")){
            $date1 = $request->get("date1");
            $date2 = $request->get("date2");
        }else{
            $date1 = date("Y-m-d");
            $date2 = date("Y-m-d");
        }
        $pageEncours = $request->get('pageEncours', 1);
        $commandes = $suppressionRep->commandesSupprimeesParPeriodeParLieuPagine($date1, $date2, $lieu_vente, $pageEncours, 30);
        return $this->render('nhema/vente/facturation/commandes_supprimees.html.twig', [
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
            'commandes' => $commandes,
            'date1' => $date1,
            'date2' => $date2,
            'search' => ""
        ]);
    }

    #[Route('/valdation/{lieu_vente}', name: 'app_nhema_vente_facturation_validation')]
    public function validation(LieuxVentes $lieu_vente, Request $request, TableCommandeRepository $tableCmdRep, TableRepository $tableRep, FacturationRepository $facturationRep, StockRepository $stockRep, ListeStockRepository $listeStockRep, CaisseRepository $caisseRep, DeviseRepository $deviseRep, CategorieOperationRepository $categorieOpRep, CompteOperationRepository $compteOpRep,  SessionInterface $session, ModePaiementRepository $modePaieRep, UserRepository $userRep, RecetteRepository $recetteRep, ProductRepository $productRep, LiaisonProductRepository $liaisonRep, BoissonRepository $boissonRep, EntityManagerInterface $em): Response
    {
        $session_jour = $session->get("session_jour")->getJourDeTravail();
        $session_client = $session->get("session_client", '');
        $emplacement = $request->get('emplacement');
        
        $table = $tableRep->find($emplacement);
        $tableCmd = $tableCmdRep->findBy(['emplacement' => $table]);
        $emplacement = $table->getNom();
        $typeVente = $request->get('type_vente');
        
        if ($tableCmd) {
            $devise = $deviseRep->find(1);
            $init = $lieu_vente->getInitial();
            $dateDuJour = new \DateTime();
            $referenceDate = $dateDuJour->format('y');
            $idSuivant =($facturationRep->findMaxId() + 1);
            $numeroFacture = $init.$referenceDate . sprintf('%04d', $idSuivant);
            $numeroTicket = $idSuivant;

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
            $factures = new Facturation();
            if ($montantPaye > $totalFacture) {
                // gestion dans le cas il y a un rendu
                $montantPaye = $totalFacture;
            }
            $factures->setNumeroFacture($numeroFacture)
                    ->setNumeroTicket($numeroTicket)
                    ->setMontantTotal($totalFacture)
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
                    ->setDateFacturation($dateVente)
                    ->setDateSaisie(new \DateTime('now'));
            $em->persist($factures);

            // enregistrement dans le mouvement caisse

            if ($montantPaye) {
                // paiement espèces
                $mouvementCaisse = new MouvementCaisse();
                $mouvementCaisse->setLieuVente($lieu_vente)
                        ->setCaisse($caisse)
                        ->setCategorieOperation($categorieOpRep->find(1))
                        ->setCompteOperation($compteOpRep->find(6))
                        ->setTypeMouvement('facturation')
                        ->setMontant($montantPaye)
                        ->setDevise($devise)
                        ->setDateOperation($dateVente)
                        ->setSaisiePar($this->getUser())
                        ->setModePaie($modePaie)
                        ->setDateSaisie(new \DateTime("now"));
                $factures->addMouvementCaiss($mouvementCaisse);
                $em->persist($mouvementCaisse);
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
                $factures->addMouvementCollaborateur($mouvementCollab);
                $em->persist($mouvementCollab);
            }

            // insertion des produits dans la bdd
            foreach ($tableCmd as  $item) {
                $commande = new CommandeProduct();
                $product = $productRep->find($item->getProduct());
                $commande->setProduct($item->getProduct())
                        ->setPrixVente($item->getPrixVente())
                        ->setQuantite($item->getQuantite());
                $factures->addCommandeProduct($commande);
                // on met à jour le stock par defaut le magasin principal
                $lieuLivraison = $listeStockRep->findOneBy(['lieuVente' => $lieu_vente], ['id' => 'ASC']);

                // gestion de la recette pour mettre à jour le stock
                $recettes = $recetteRep->findBy(['product' => $product]);
                // si on trouve une recette on ajuste les stocks

                if ($recettes) {
                    foreach ($recettes as $recette) {
                        $ingredient = $recette->getIngredient();
                        $stock_ingredient = $stockRep->findOneBy(['product' => $ingredient, 'emplacement' => $lieuLivraison]);
                        $qtite_recette = $recette->getQuantite() * $item->getQuantite();
                        $stock_ingredient->setQuantite($stock_ingredient->getQuantite() - $qtite_recette);

                        $mouvement_ingredient = new MouvementProduct();
                        $mouvement_ingredient->setStockProduct($lieuLivraison)
                                    ->setProduct($ingredient)
                                    ->setQuantite(- $qtite_recette)
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
                            if($stock_ingredient->getQuantite() <= 0 ) {
                                $liaison = $liaisonRep->findOneBy(['product2' => $ingredient]);

                                $bouteille = $liaison->getProduct1();
                                $bouteille = $boissonRep->find($bouteille);
                                
                                $stock_bouteille = $stockRep->findOneBy(['product' => $bouteille, 'emplacement' => $lieuLivraison]);
                                
                                // on ajoute une bouteille dans le detail
                                $stock_ingredient->setQuantite($stock_ingredient->getQuantite() + $bouteille->getVolume());

                                $stock_bouteille->setQuantite($stock_bouteille->getQuantite() - 1);
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

                $stocks = $stockRep->findOneBy(['emplacement' => $lieuLivraison, 'product' => $item->getProduct()]);
                $stocks->setQuantite(($stocks->getQuantite() - $item->getQuantite()));
                
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
        $id = $facturationRep->findMaxId();
        $this->addFlash("success", "Vente éffectuée avec succès :)"); 
        return $this->redirectToRoute("app_nhema_vente_facturation_show", ['id'=> $id, 'lieu_vente' => $lieu_vente->getId()]);
    } 

    /**
     * liste des factures
     */
    
    #[Route('/liste/{lieu_vente}', name: 'app_nhema_vente_facturation_index')]
    public function facturations(LieuxVentes $lieu_vente, Request $request, UserRepository $userRep, AuthorizationCheckerInterface $authorizationChecker, FacturationRepository $facturationRep, SessionInterface $session, EntrepriseRepository $entrepriseRep): Response
    {
        $session_jour = $session->get("session_jour")->getJourDeTravail();
        if ($request->get("id_client_search")){
            $search = $request->get("id_client_search");
        }else{
            $search = "";
        }

        if ($request->get("date1")){
            $date1 = $request->get("date1");
            $date2 = $request->get("date2");

        }else{
            $date1 = $session_jour->format("Y-m-d");
            $date2 = date("Y-m-d");
        }
        if ($request->isXmlHttpRequest()) {
            if ( $request->query->get('search')) {
                $search = $request->query->get('search');
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
        $pageEncours = $request->get('pageEncours', 1);
        if ($request->get("id_client_search")){
            $facturations = $facturationRep->findFacturationByLieuBySearchPaginated($lieu_vente, $search, $date1, $date2, $pageEncours, 25);
        }elseif ($request->get("id_personnel")){
            $facturations = $facturationRep->findFacturationByLieuByPersonnelPaginated($lieu_vente, $request->get("id_personnel"), $date1, $date2, $pageEncours, 25);
        }else{
            if (!$authorizationChecker->isGranted('ROLE_GESTIONNAIRE')) {
                $facturations = $facturationRep->findFacturationByLieuByPersonnelPaginated($lieu_vente, $this->getUser(), $date1, $date2, $pageEncours, 25);
            }else{

                $facturations = $facturationRep->findFacturationByLieuPaginated($lieu_vente, $date1, $date2, $pageEncours, 25);
            }
        }
        

        return $this->render('nhema/vente/facturation/index.html.twig', [
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
            'facturations' => $facturations,
            'search' => $search,
            'date1' => $date1,
            'date2' => $date2,
        ]);
    } 

    #[Route('/new', name: 'app_nhema_vente_facturation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, LieuxVentes $lieu_vente, EntrepriseRepository $entrepriseRep): Response
    {
        $facturation = new Facturation();
        $form = $this->createForm(FacturationType::class, $facturation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($facturation);
            $entityManager->flush();

            return $this->redirectToRoute('app_nhema_vente_facturation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/vente/facturation/new.html.twig', [
            'facturation' => $facturation,
            'form' => $form,
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
        ]);
    }

    #[Route('/show/{id}/{lieu_vente}', name: 'app_nhema_vente_facturation_show')]
    public function show(Facturation $facturation, LieuxVentes $lieu_vente, MouvementCollaborateurRepository $mouvementCollabRep,CommandeProductRepository $commandeProdRep, MouvementCaisseRepository $mouvementCaisseRep,ModificationFactureRepository $modificationRep, EntrepriseRepository $entrepriseRep): Response
    {        
        $soldes_collaborateur = $mouvementCollabRep->findSoldeCollaborateur($facturation->getClient());
        return $this->render('nhema/vente/facturation/show.html.twig', [
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
            'facturation' => $facturation,
            'commandes' => $commandeProdRep->findBy(['facturation' => $facturation], ['quantite' => 'ASC']),
            'caisses' => $mouvementCaisseRep->findBy(['facturation' => $facturation]),
            'soldes_collaborateur' => $soldes_collaborateur,
            'modifications' => $modificationRep->findBy(['facture' => $facturation])

        ]);
    } 

    #[Route('/edit/{id}/{lieu_vente}', name: 'app_nhema_vente_facturation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Facturation $facturation, EntityManagerInterface $entityManager, LieuxVentes $lieu_vente, EntrepriseRepository $entrepriseRep): Response
    {
        $form = $this->createForm(FacturationType::class, $facturation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_nhema_vente_facturation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/vente/facturation/edit.html.twig', [
            'facturation' => $facturation,
            'form' => $form,
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
        ]);
    }

    #[Route('/facture/{id}/{lieu_vente}', name: 'app_nhema_vente_facturation_facture')]
    public function facture(Facturation $facture, LieuxVentes $lieu_vente, CommandeProductRepository $commandeProdRep)
    {
        $logoPath = $this->getParameter('kernel.project_dir') . '/public/images/logos/'.$lieu_vente->getEntreprise()->getLogo();
        $logoBase64 = base64_encode(file_get_contents($logoPath));

        $commandes = $commandeProdRep->findBy(['facturation' => $facture]);

        $html = $this->renderView('nhema/vente/facturation/facture.html.twig', [
            'commandes' => $commandes,
            'logoPath' => $logoBase64,
            'lieu_vente' => $lieu_vente,
            'facture' => $facture,
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
            'Content-Disposition' => 'inline; filename="facture.pdf"',
        ]);
    }

    #[Route('/delete/{id}/{lieu_vente}', name: 'app_nhema_vente_facturation_delete')]
    public function delete(Facturation $facturation, LieuxVentes $lieu_vente, CommandeProductRepository $commandeProdRep, ListeStockRepository $listeStockRep, StockRepository $stockRep, RecetteRepository $recetteRep, MouvementProductRepository $mouvementProdRep, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$facturation->getId(), $request->request->get('_token'))) {
            $commandes = $commandeProdRep->findBy(['facturation' => $facturation]);
            $lieuLivraison = $listeStockRep->findOneBy(['lieuVente' => $lieu_vente], ['id' => 'ASC']);

            foreach ($commandes as $cmd) {
                $mouvementsProduct = $mouvementProdRep->findBy(['commande' => $cmd]);
                foreach ($mouvementsProduct as $mouvementProd) {
                    if ($mouvementProd->getOrigine() == 'recette' or $mouvementProd->getOrigine() == 'liaison produit') {
                        $stock_maj = $stockRep->findOneBy(['emplacement' =>$mouvementProd->getStockProduct(), 'product' => $mouvementProd->getProduct()]);
    
                        $stock_maj->setQuantite($stock_maj->getQuantite() - ($mouvementProd->getQuantite()));
                        $em->persist($stock_maj);
                    }
                }

                // remettre les quantités livrées dans le stock
                $stock = $stockRep->findOneBy(['emplacement' => $lieuLivraison, 'product' => $cmd->getProduct()]);

                $stock->setQuantite($stock->getQuantite() + $cmd->getQuantite());
                $em->persist($stock);
            }
            $em->remove($facturation);
            // on stock la modification dans la table historique des modifictaions
            $suppressionFacture = new SuppressionFacture();
            $suppressionFacture->setNumeroFacture($facturation->getNumeroFacture())
                    ->setMontantTotal($facturation->getMontantTotal())
                    ->setMontantPaye($facturation->getMontantPaye())
                    ->setRemise($facturation->getRemise())
                    ->setFraisSup($facturation->getFraisSup())
                    ->setClient($facturation->getClient())
                    ->setCaisse($facturation->getCaisse())
                    ->setModePaie($facturation->getModePaie())
                    ->setLieuVente($lieu_vente)
                    ->setSaisiePar($this->getUser())
                    ->setDateFacturation($facturation->getDateFacturation())
                    ->setDateSaisie(new \DateTime("now"));
            $em->persist($suppressionFacture);
            $em->flush();
            $this->addFlash("success", "vente supprimée avec succès :)");
        }
        return $this->redirectToRoute("app_nhema_vente_facturation_index", ['lieu_vente' => $lieu_vente->getId()]);
    }


}
