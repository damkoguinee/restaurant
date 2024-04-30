<?php

namespace App\Controller\Nhema\Achat;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\LieuxVentes;
use App\Entity\Decaissement;
use App\Form\DecaissementType;
use App\Entity\MouvementCaisse;
use App\Entity\AchatFournisseur;
use App\Entity\MouvementProduct;
use App\Form\AchatFournisseurType;
use App\Repository\UserRepository;
use App\Repository\StockRepository;
use App\Entity\ListeTransfertProduct;
use App\Entity\MouvementCollaborateur;
use App\Repository\EntrepriseRepository;
use App\Repository\ListeStockRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LieuxVentesRepository;
use App\Entity\ListeProductAchatFournisseur;
use Symfony\Component\Filesystem\Filesystem;
use App\Repository\CompteOperationRepository;
use App\Repository\MouvementCaisseRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\AchatFournisseurRepository;
use App\Repository\MouvementProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategorieOperationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\AsciiSlugger;
use App\Repository\CategorieDecaissementRepository;
use App\Repository\MouvementCollaborateurRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Repository\ListeProductAchatFournisseurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/nhema/achat/achat/fournisseur')]
class AchatFournisseurController extends AbstractController
{
    #[Route('/accueil/{lieu_vente}', name: 'app_nhema_achat_achat_fournisseur_index', methods: ['GET'])]
    public function index(LieuxVentes $lieu_vente, Request $request, UserRepository $userRep, AchatFournisseurRepository $achatFournisseurRepository, EntrepriseRepository $entrepriseRep): Response
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

        if ($request->isXmlHttpRequest()) {
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
        $pageEncours = $request->get('pageEncours', 1);
        if ($request->get("id_client_search")){
            $achats = $achatFournisseurRepository->findAchatByLieuBySearchPaginated($lieu_vente, $search, $date1, $date2, $pageEncours, 25);
        }else{
            $achats = $achatFournisseurRepository->findAchatByLieuPaginated($lieu_vente, $date1, $date2, $pageEncours, 25);
        }
        return $this->render('nhema/achat/achat_fournisseur/index.html.twig', [
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
            'achat_fournisseurs' => $achats,
            'search' => $search,
        ]);
    }

    #[Route('/new/{lieu_vente}', name: 'app_nhema_achat_achat_fournisseur_new', methods: ['GET', 'POST'])]
    public function new(LieuxVentes $lieu_vente, Request $request, EntityManagerInterface $entityManager, EntrepriseRepository $entrepriseRep, CategorieDecaissementRepository $categorieDecRep, CategorieOperationRepository $catetgorieOpRep, CompteOperationRepository $compteOpRep, MouvementCaisseRepository $mouvementRep): Response
    {
        $achatFournisseur = new AchatFournisseur();
        $form = $this->createForm(AchatFournisseurType::class, $achatFournisseur, ['lieu_vente' => $lieu_vente]);
        $form->handleRequest($request);

        $decaissement = new Decaissement();
        $form_decaissement = $this->createForm(DecaissementType::class, $decaissement, ['lieu_vente' => $lieu_vente]);
        $form_decaissement->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $montantString = $form->get('montant')->getData();
            $montantString = preg_replace('/[^-0-9,.]/', '', $montantString);
            $montant = floatval($montantString);

            $montantString = $form->get('tva')->getData();
            $montantString = preg_replace('/[^-0-9,.]/', '', $montantString);
            $montant_tva = floatval($montantString);

            $achatFournisseur->setLieuVente($lieu_vente)
                        ->setMontant($montant)
                        ->setTva($montant_tva)
                        ->setPersonnel($this->getUser())
                        ->setEtatPaiement("non payé")
                        ->setDateSaisie(new \DateTime("now"));

            $document = $form->get("document")->getData();
            if ($document) {
                $nomFichier= pathinfo($document->getClientOriginalName(), PATHINFO_FILENAME);
                $slugger = new AsciiSlugger();
                $nouveauNomFichier = $slugger->slug($nomFichier);
                $nouveauNomFichier .="_".uniqid();
                $nouveauNomFichier .= "." .$document->guessExtension();
                $document->move($this->getParameter("dossier_achat"),$nouveauNomFichier);
                $achatFournisseur->setDocument($nouveauNomFichier);
            }

            $mouvement_collab_achat = new MouvementCollaborateur();
            $mouvement_collab_achat->setCollaborateur($form->getViewData()->getFournisseur())
                            ->setOrigine("achat-fournisseur")
                            ->setMontant($montant)
                            ->setDevise($form->getViewData()->getDevise())
                            ->setLieuVente($lieu_vente)
                            ->setTraitePar($this->getUser())
                            ->setDateOperation($form->getViewData()->getDateFacture())
                            ->setDateSaisie(new \DateTime("now"));
            $achatFournisseur->addMouvementCollaborateur($mouvement_collab_achat);

            if ($request->get("etat_facture") == 'payé') {
                $caisse = $form_decaissement->getViewData()->getCompteDecaisser();
                $devise = $form->getViewData()->getDevise();
                $solde_caisse = $mouvementRep->findSoldeCaisse($caisse, $devise);
                if ($solde_caisse >= $montant) {
                    $categorie_dec = $categorieDecRep->find(2);
                    $decaissement->setClient($form->getViewData()->getFournisseur())
                                ->setLieuVente($lieu_vente)
                                ->setTraitePar($this->getUser())
                                ->setReference($form->getViewData()
                                ->setSaisiePar($this->getUser())
                                ->getNumeroFacture())
                                ->setMontant($montant)
                                ->setDevise($form->getViewData()->getDevise())
                                ->setCommentaire($form->getViewData()->getCommentaire())
                                ->setCategorie($categorie_dec)
                                ->setDateSaisie(new \DateTime("now"));

                    $mouvement_collab = new MouvementCollaborateur();
                    $mouvement_collab->setCollaborateur($form->getViewData()->getFournisseur())
                                    ->setOrigine("decaissement")
                                    ->setMontant(-$montant)
                                    ->setDevise($form->getViewData()->getDevise())
                                    ->setCaisse($form_decaissement->getViewData()->getCompteDecaisser())
                                    ->setLieuVente($lieu_vente)
                                    ->setTraitePar($this->getUser())
                                    ->setDateOperation($form_decaissement->getViewData()->getDateDecaissement())
                                    ->setDateSaisie(new \DateTime("now"));
                    $decaissement->addMouvementCollaborateur($mouvement_collab);

                    $mouvement_caisse = new MouvementCaisse();
                    $categorie_op = $catetgorieOpRep->find(3);
                    $compte_op = $compteOpRep->find(1);
                    $mouvement_caisse->setCategorieOperation($categorie_op)
                                    ->setCompteOperation($compte_op)
                                    ->setTypeMouvement("decaissement-fournisseur")
                                    ->setMontant(-$montant)
                                    ->setSaisiePar($this->getUser())
                                    ->setDevise($form->getViewData()->getDevise())
                                    ->setCaisse($form_decaissement->getViewData()->getCompteDecaisser())
                                    ->setLieuVente($lieu_vente)
                                    ->setDateOperation($form_decaissement->getViewData()->getDateDecaissement())
                                    ->setDateSaisie(new \DateTime("now"));
                    $decaissement->addMouvementCaiss($mouvement_caisse);
                    $entityManager->persist($decaissement);
                    $achatFournisseur->setEtatPaiement("payé");
                }else{
                    $this->addFlash("warning", "Le montant disponible en caisse est insuffisant");
                    // Récupérer l'URL de la page précédente
                    $referer = $request->headers->get('referer');
                    if ($referer) {
                        $formView = $form->createView();
                        $formDecView = $form_decaissement->createView();
                        return $this->render('nhema/achat/achat_fournisseur/new.html.twig', [
                            'entreprise' => $entrepriseRep->find(1),
                            'lieu_vente' => $lieu_vente,
                            'achat_fournisseur' => $achatFournisseur,
                            'form' => $formView,
                            'decaissement' => $decaissement,
                            'form_decaissement'  => $formDecView,
                            'referer' => $referer,
                        ]);
                    }
                }
            }
            $entityManager->persist($achatFournisseur);
            $entityManager->flush();
            $this->addFlash("success", "Facture ajoutée avec succès. 😊 ");
            return $this->redirectToRoute('app_nhema_achat_achat_fournisseur_index', ['lieu_vente' => $lieu_vente->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/achat/achat_fournisseur/new.html.twig', [
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
            'achat_fournisseur' => $achatFournisseur,
            'form' => $form,
            'decaissement' => $decaissement,
            'form_decaissement'  => $form_decaissement
        ]);
    }

    #[Route('/show/{id}/{lieu_vente}', name: 'app_nhema_achat_achat_fournisseur_show', methods: ['GET'])]
    public function show(AchatFournisseur $achatFournisseur, ListeProductAchatFournisseurRepository $listeProductAchatRep, LieuxVentes $lieu_vente, EntrepriseRepository $entrepriseRep): Response
    {
        $liste_receptions = $listeProductAchatRep->findBy(['achatFournisseur' => $achatFournisseur], ['id' => 'DESC']); 
        return $this->render('nhema/achat/achat_fournisseur/show.html.twig', [
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
            'achat_fournisseur' => $achatFournisseur,
            'liste_receptions' => $liste_receptions,
        ]);
    }

    #[Route('/edit/{id}/{lieu_vente}', name: 'app_nhema_achat_achat_fournisseur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AchatFournisseur $achatFournisseur, LieuxVentes $lieu_vente, EntityManagerInterface $entityManager, EntrepriseRepository $entrepriseRep): Response
    {
        
        $form = $this->createForm(AchatFournisseurType::class, $achatFournisseur, ['lieu_vente' => $lieu_vente]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $montantString = $form->get('montant')->getData();
            $montantString = preg_replace('/[^-0-9,.]/', '', $montantString);
            $montant = floatval($montantString);

            $montantString = $form->get('tva')->getData();
            $montantString = preg_replace('/[^-0-9,.]/', '', $montantString);
            $montant_tva = floatval($montantString);

            $achatFournisseur->setMontant($montant)
                            ->setTva($montant_tva);
            $document =$form->get("document")->getData();
            if ($document) {
                if ($achatFournisseur->getDocument()) {
                    $ancienDiplome=$this->getParameter("dossier_achat")."/".$achatFournisseur->getDocument();
                    if (file_exists($ancienDiplome)) {
                        unlink($ancienDiplome);
                    }
                }
                $nomDiplome= pathinfo($document->getClientOriginalName(), PATHINFO_FILENAME);
                $slugger = new AsciiSlugger();
                $nouveauNomDiplome = $slugger->slug($nomDiplome);
                $nouveauNomDiplome .="_".uniqid();
                $nouveauNomDiplome .= "." .$document->guessExtension();
                $document->move($this->getParameter("dossier_diplome"),$nouveauNomDiplome);
                $achatFournisseur->setDocument($nouveauNomDiplome);

            }
            $entityManager->flush();
            $this->addFlash("success", "Facture modifiée avec succès. 😊 ");
            return $this->redirectToRoute('app_nhema_achat_achat_fournisseur_index', ['lieu_vente' => $lieu_vente->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/achat/achat_fournisseur/edit.html.twig', [
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
            'achat_fournisseur' => $achatFournisseur,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}/{lieu_vente}', name: 'app_nhema_achat_achat_fournisseur_delete', methods: ['POST', 'GET'])]
    public function delete(Request $request, AchatFournisseur $achatFournisseur, LieuxVentes $lieu_vente, Filesystem $filesystem, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$achatFournisseur->getId(), $request->request->get('_token'))) {
            $justificatif = $achatFournisseur->getDocument();
            $pdfPath = $this->getParameter("dossier_achat") . '/' . $justificatif;
            // Si le chemin du justificatif existe, supprimez également le fichier
            if ($justificatif && $filesystem->exists($pdfPath)) {
                $filesystem->remove($pdfPath);
            }
            
            $entityManager->remove($achatFournisseur);
            $entityManager->flush();
        }
        $this->addFlash("success", "Facture supprimée avec succès. 😊 ");
        return $this->redirectToRoute('app_nhema_achat_achat_fournisseur_index', ['lieu_vente' => $lieu_vente->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/reception/{id}/{lieu_vente}', name: 'app_nhema_achat_achat_fournisseur_reception')]
    public function approInitial(AchatFournisseur $achatFournisseur, LieuxVentes $lieu_vente, Request $request, ListeStockRepository $listeStockRep, StockRepository $stockRep,  EntrepriseRepository $entrepriseRep, MouvementProductRepository $mouvementProductRep, ListeProductAchatFournisseurRepository $listeProductAchatRep, EntityManagerInterface $em): Response
    {
        if ($request->get("reception") and $request->get("id_stock")){
            if ($request->get("quantite")) {
                $quantite = $request->get("quantite");
            }else{
                $quantite = 0;
            }
            $stock_product = $stockRep->find($request->get("id_stock"));
            $taux = $achatFournisseur->getTaux();
            $taux = str_replace(' ', '', $taux);
            $prix_achat = str_replace(' ', '', $request->get("prix_achat"));
            $prix_vente = str_replace(' ', '', $request->get("prix_vente"));
            $prix_revient = str_replace(' ', '', $request->get("prix_revient"));

            $prix_achat = $prix_achat*$taux;

            $prix_revient = $prix_revient ? $prix_revient*$taux : $stock_product->getPrixRevient();

            $prix_vente = $prix_vente ? $prix_vente*$taux : $stock_product->getPrixVente();

            $peremption = str_replace(' ', '', $request->get("peremption"));
            $commentaire = $request->get("commentaire");
            $product_achat = new ListeProductAchatFournisseur();

            if (($quantite + $stock_product->getQuantite())) {
                $prix_revient_moyen =(($prix_revient * $quantite ) + ( $stock_product->getPrixRevient() * $stock_product->getQuantite())) / ($quantite + $stock_product->getQuantite());
                $stock_product->setPrixRevient($prix_revient_moyen);
            }

            $product_achat->setAchatFournisseur($achatFournisseur)
                        ->setQuantite($quantite)
                        ->setProduct($stock_product->getProduct())
                        ->setTaux($taux)
                        ->setPrixAchat($prix_achat)
                        ->setPrixRevient($prix_revient)
                        ->setStock($stock_product->getEmplacement())
                        ->setCommentaire($commentaire)
                        ->setPersonnel($this->getUser())
                        ->setDateSaisie(new \DateTime("now"));

            $stock_product->setPrixVente($prix_vente)
                ->setQuantite($stock_product->getQuantite() + $quantite)
                ->setPrixAchat($prix_achat);

            if ($peremption) {
                $stock_product->setDatePeremption($peremption ? (new \DateTime($peremption)) : '');
            }
            
            // on insère dans le mouvement des produits si quantité different de 0
            if ($quantite != 0) {
                $mouvementProduct = new MouvementProduct();
                $mouvementProduct->setStockProduct($stock_product->getEmplacement())
                    ->setProduct($stock_product->getProduct())
                    ->setPersonnel($this->getUser())
                    ->setQuantite($quantite)
                    ->setLieuVente($stock_product->getEmplacement()->getLieuVente())
                    ->setOrigine("achat-fournisseur")
                    ->setDescription("achat-fournisseur")
                    ->setDateOperation(new \DateTime("now"));
                $product_achat->addMouvementProduct($mouvementProduct);
                $em->persist($mouvementProduct);
            }
            $em->persist($product_achat);
            $em->persist($stock_product);
            $em->flush(); 
            $this->addFlash("success", "produit réceptionné avec succès :)");
            return new RedirectResponse($this->generateUrl('app_nhema_achat_achat_fournisseur_reception', ['id' => $achatFournisseur->getId(), 'lieu_vente' => $lieu_vente->getId(), 'search' => $request->get("search"), 'magasin' => $request->get("magasin"), 'pageEncours' => $request->get('pageEncours', 1)]));           

        }
        if ($request->get("search")){
            $search = $request->get("search");
        }else{
            $search = "";
        }
        
        $pageEncours = $request->get('pageEncours', 1);
        $pageMouvEncours = $request->get('pageMouvEncours', 1);

        if ($request->get("magasin")){
            $magasin = $listeStockRep->find($request->get("magasin"));
        }else{
            $magasin = $listeStockRep->findOneBy(['lieuVente' => $lieu_vente]);

        }
        // $stocks = $stockRep->findStocksByCodeBarrePaginated($magasin, $search, $pageEncours, 5);
        // if (!$stocks['data']) {
        //     $stocks = $stockRep->findStocksForApproInitPaginated($magasin, $search, $pageEncours, 5); 
        // } 

        $stocks = $stockRep->findStocksForApproInitPaginated($magasin, $search, $pageEncours, 5); 
        $liste_receptions = $listeProductAchatRep->findBy(['achatFournisseur' => $achatFournisseur], ['id' => 'DESC']); 

        $listeStocks = $listeStockRep->findBy(['lieuVente' => $lieu_vente]);
        return $this->render('nhema/achat/achat_fournisseur/reception.html.twig', [
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
            'liste_stocks' => $listeStocks,
            'liste_receptions' => $liste_receptions,
            'magasin' => $magasin,
            'achat_fournisseur' => $achatFournisseur,
            'search' => $search,
            'stocks' => $stocks,

        ]);
    }

    #[Route('/delete/reception/{id}/{lieu_vente}', name: 'app_nhema_achat_achat_fournisseur_reception_delete', methods: ['POST', 'GET'])]
    public function deleteReception(ListeProductAchatFournisseur $ProductAchat, LieuxVentes $lieu_vente, StockRepository $stockRep, EntityManagerInterface $entityManager): Response
    {
        $stock = $stockRep->findOneBy(['product' => $ProductAchat->getProduct(), 'emplacement' => $ProductAchat->getStock() ]);
        // remise du prix de revient initial
        
        $qtite_appro = $ProductAchat->getQuantite();
        $qtite_init = $stock->getQuantite() - $qtite_appro;
        $prix_revient_appro = $ProductAchat->getPrixRevient();
        $prix_revient_actuel = $stock->getPrixRevient();

        if ($qtite_init != 0 and !empty($qtite_init)) {
            $prix_initial = (($prix_revient_actuel * ($qtite_appro + $qtite_init)) - ($qtite_appro * $prix_revient_appro)) / $qtite_init ;

            $stock->setPrixRevient($prix_initial);

        }
        $stock->setQuantite($stock->getQuantite() - $ProductAchat->getQuantite());
        $entityManager->persist($stock);
        $entityManager->remove($ProductAchat);
        $entityManager->flush();
        $this->addFlash("success", "Réception supprimée avec succès :) ");
        return $this->redirectToRoute('app_nhema_achat_achat_fournisseur_reception', ['id' => $ProductAchat->getAchatFournisseur()->getId(), 'lieu_vente' => $lieu_vente->getId()], Response::HTTP_SEE_OTHER);
    }


    #[Route('/pdf/reception/{id}/{lieu_vente}', name: 'app_nhema_achat_achat_fournisseur_reception_pdf', methods: ['GET'])]
    public function genererPdfAction(AchatFournisseur $achatFournisseur, LieuxVentes $lieu_vente, ListeProductAchatFournisseurRepository $listeProductRep, MouvementCollaborateurRepository $mouvementCollabRep, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep)
    {
        $entreprise = $entrepriseRep->findOneBy(['id' => 1]);
        $logoPath = $this->getParameter('kernel.project_dir') . '/public/images/logos/'.$entreprise->getLogo();
        $logoBase64 = base64_encode(file_get_contents($logoPath));

        $liste_products = $listeProductRep->findBy(["achatFournisseur" => $achatFournisseur]);

        $soldeCollaborateur = $mouvementCollabRep->findSoldeCollaborateur($achatFournisseur->getFournisseur());

        $html = $this->renderView('nhema/achat/achat_fournisseur/pdf_reception.html.twig', [
            'achat_fournisseur' => $achatFournisseur,
            'liste_receptions' => $liste_products,
            'solde_collaborateur' => $soldeCollaborateur,
            'logoPath' => $logoBase64,
            'lieu_vente' => $lieu_vente,
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
            'Content-Disposition' => 'inline; filename="produits_receptionnés.pdf"',
        ]);
    }
}
