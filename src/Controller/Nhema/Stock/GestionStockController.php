<?php

namespace App\Controller\Nhema\Stock;

use App\Entity\Stock;
use App\Entity\LieuxVentes;
use App\Entity\AnomalieProduit;
use App\Entity\MouvementProduct;
use App\Form\AnomalieProduitType;
use App\Repository\StockRepository;
use App\Repository\EntrepriseRepository;
use App\Repository\ListeStockRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AnomalieProduitRepository;
use App\Repository\MouvementProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/nhema/stock/gestion/stock')]
class GestionStockController extends AbstractController
{
    #[Route('/{lieu_vente}', name: 'app_nhema_stock_gestion_stock_index')]
    public function index(LieuxVentes $lieu_vente, Request $request, ListeStockRepository $listeStockRep, StockRepository $stockRep,  EntrepriseRepository $entrepriseRep, EntityManagerInterface $em): Response
    {
        if ($request->get("search")){
            $search = $request->get("search");
        }else{
            $search = "";
        }
        
        $pageEncours = $request->get('pageEncours', 1);

        if ($request->get("magasin")){
            $magasin = $listeStockRep->find($request->get("magasin"));
        }else{
            $magasin = $listeStockRep->findOneBy(['lieuVente' => $lieu_vente]);

        }

        // partie ajusteent prix de vente des produits

        if ($request->get("ajustement") and $request->get("id_stock")){
            $prixVente = $request->get("prix_vente");

            if (is_numeric($prixVente) && $prixVente >= 0) {
                $stock_product = $stockRep->find($request->get("id_stock"));
                if ($request->get("peremption")) {
                    $stock_product->setDatePeremption(new \DateTime($request->get("peremption")));
                }
                $stock_product->setPrixVente($prixVente);

                $em->persist($stock_product);
                $em->flush();

                return new RedirectResponse($this->generateUrl('app_nhema_stock_gestion_stock_index', ['lieu_vente' => $lieu_vente->getId(), 'search' => $request->get("search"), 'magasin' => $request->get("magasin"), 'pageEncours' => $request->get('pageEncours', 1)])); 
            }
            

        }
        $stocks = $stockRep->findStocksPaginated($magasin, $search, $pageEncours, 20); 


        $listeStocks = $listeStockRep->findBy(['lieuVente' => $lieu_vente]);
        return $this->render('nhema/stock/gestion_stock/index.html.twig', [
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
            'liste_stocks' => $listeStocks,
            'magasin' => $magasin,
            'search' => $search,
            'stocks' => $stocks,

        ]);
    }

    #[Route('/detail/{stock}', name: 'app_nhema_stock_gestion_stock_show')]
    public function show(Stock $stock, AnomalieProduitRepository $anomalieRep, Request $request, EntrepriseRepository $entrepriseRep, EntityManagerInterface $em): Response
    {
        // $anomalieProduit = new AnomalieProduit();
        // $form = $this->createForm(AnomalieProduitType::class, $anomalieProduit);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $anomalieProduit->setStock($stock->getEmplacement())
        //         ->setProduct($stock->getProduct())
        //         ->setPrixRevient($stock->getPrixRevient())
        //         ->setPersonnel($this->getUser())
        //         ->setDateAnomalie(new \DateTime("now"));

        //     // on met à jour le produit dans le stock

        //     $stock->setQuantite($stock->getQuantite() + $anomalieProduit->getQuantite());

        //     // on insère dans le mouvement des produits
        //     $mouvementProduct = new MouvementProduct();
        //     $mouvementProduct->setStockProduct($stock->getEmplacement())
        //         ->setProduct($stock->getProduct())
        //         ->setPersonnel($this->getUser())
        //         ->setQuantite($anomalieProduit->getQuantite())
        //         ->setLieuVente($stock->getEmplacement()->getLieuVente())
        //         ->setOrigine("stock")
        //         ->setDescription($anomalieProduit->getComentaire())
        //         ->setDateOperation(new \DateTime("now"));
        //     $anomalieProduit->addMouvementProduct($mouvementProduct);

        //     $em->persist($anomalieProduit);
        //     $em->persist($stock);
        //     $em->persist($mouvementProduct);
        //     $em->flush();

        //     $this->addFlash("success", "L'anomalie a été ajoutée avec succès. 😢");
            
        //     return new RedirectResponse($this->generateUrl('app_nhema_stock_gestion_stock_show', ['stock' => $stock->getId()]));
        // }
        
        return $this->render('nhema/stock/gestion_stock/show.html.twig', [
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $stock->getEmplacement()->getLieuVente(),
            'stock' => $stock,
            // 'anomalie_produit' => $anomalieProduit,
            // 'liste_anomalies' => $anomalieRep->findBy(['stock' => $stock->getEmplacement(), 'product' => $stock->getProduct()], ['dateAnomalie' => 'DESC', 'stock' => 'ASC']),
            // 'form' => $form,

        ]);

    }


    #[Route('/appro/initial/{lieu_vente}', name: 'app_nhema_stock_gestion_stock_appro_initial')]
    public function approInitial(LieuxVentes $lieu_vente, Request $request, ListeStockRepository $listeStockRep, StockRepository $stockRep,  EntrepriseRepository $entrepriseRep, MouvementProductRepository $mouvementProductRep, EntityManagerInterface $em): Response
    {
        // partie ajustement prix de vente des produits
        if ($request->get("ajustement") and $request->get("id_stock")){
            if ($request->get("quantite")) {
                $quantite = $request->get("quantite");
            }else{
                $quantite = 0;
            }
            $prix_achat = str_replace(' ', '', $request->get("prix_achat"));
            $prix_vente = str_replace(' ', '', $request->get("prix_vente"));
            $prix_revient = str_replace(' ', '', $request->get("prix_revient"));
            $peremption = str_replace(' ', '', $request->get("peremption"));
            $nom = $request->get("designation");

            
            $stock_product = $stockRep->find($request->get("id_stock"));
            if (($quantite + $stock_product->getQuantite())) {
                $prix_revient_moyen =(($prix_revient * $quantite + $stock_product->getPrixRevient() * $stock_product->getQuantite()) / ($quantite + $stock_product->getQuantite()));

                $stock_product->setPrixRevient($prix_revient_moyen);
            }

            $stock_product->setPrixVente($prix_vente)
                ->setQuantite($stock_product->getQuantite() + $quantite)
                ->setPrixAchat($prix_achat);

            $product_maj = $stock_product->getProduct();
            $product_maj->setNom($nom); 
            

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
                    ->setOrigine("appro-initial")
                    ->setDescription("appro-initial")
                    ->setDateOperation(new \DateTime("now"));
                $em->persist($mouvementProduct);
            }
            $em->persist($stock_product);
            $em->persist($product_maj);
            $em->flush(); 

            return new RedirectResponse($this->generateUrl('app_nhema_stock_gestion_stock_appro_initial', ['lieu_vente' => $lieu_vente->getId(), 'search' => $request->get("search"), 'magasin' => $request->get("magasin"), 'pageEncours' => $request->get('pageEncours', 1)]));           

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

        $stocks = $stockRep->findStocksForApproInitPaginated($magasin, $search, $pageEncours, 5); 

        $liste_appros_initial = $mouvementProductRep->findListeProductsForApproInitPaginated('appro-initial', $magasin, $search, $pageMouvEncours, 20); 

        $listeStocks = $listeStockRep->findBy(['lieuVente' => $lieu_vente]);
        return $this->render('nhema/stock/gestion_stock/appro_initial.html.twig', [
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
            'liste_stocks' => $listeStocks,
            'liste_appros_initial' => $liste_appros_initial,
            'magasin' => $magasin,
            'search' => $search,
            'stocks' => $stocks,

        ]);
    }

    #[Route('/delete/appro/initial/{id}', name: 'app_nhema_stock_gestion_stock_appro_initial_delete', methods: ['POST', 'GET'])]
    public function delete(Request $request, MouvementProduct $mouvementProduct, StockRepository $stockRep, EntityManagerInterface $entityManager): Response
    {
        $stock = $stockRep->findOneBy(['product' => $mouvementProduct->getProduct(), 'emplacement' => $mouvementProduct->getStockProduct() ]);

        $stock->setQuantite($stock->getQuantite() - $mouvementProduct->getQuantite());
        $entityManager->persist($stock);
        $entityManager->remove($mouvementProduct);
        $entityManager->flush();
        $this->addFlash("success", "appro-initial supprimé avec succès ");
        return $this->redirectToRoute('app_nhema_stock_gestion_stock_appro_initial', ['lieu_vente' => $mouvementProduct->getLieuVente()->getId()], Response::HTTP_SEE_OTHER);
    }
}
