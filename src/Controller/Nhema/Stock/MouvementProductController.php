<?php

namespace App\Controller\Nhema\Stock;

use App\Entity\LieuxVentes;
use App\Repository\EntrepriseRepository;
use App\Repository\ListeStockRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\MouvementProductRepository;
use App\Repository\ProductRepository;
use App\Repository\StockRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/nhema/stock/mouvement/product')]
class MouvementProductController extends AbstractController
{
    #[Route('/{lieu_vente}', name: 'app_nhema_stock_mouvement_product')]
    public function index(LieuxVentes $lieu_vente, Request $request, MouvementProductRepository $mouvementRep, ProductRepository $productRep, ListeStockRepository $listeStockRep, StockRepository $stockRep, EntrepriseRepository $entrepriseRep): Response
    {
        if ($request->get("id_product_search")){
            $search = $request->get("id_product_search");
        }else{
            $search = "";
        }
        if ($request->get("magasin")){
            $magasin = $listeStockRep->find($request->get("magasin"));
        }else{
            $magasin = $listeStockRep->findOneBy(['lieuVente' => $lieu_vente]);

        }

        if ($request->get("date1")){
            $date1 = $request->get("date1");
            $date2 = $request->get("date2");

        }else{
            $date1 = date("Y-01-01");
            $date2 = date("Y-m-d");
        }  

        if ($request->isXmlHttpRequest()) {
            $search = $request->query->get('search_product');
            $products = $productRep->rechercheProduct($search);    
            $response = [];
            foreach ($products as $product) {
                $response[] = [
                    'nom' => $product->getNom(),
                    'id' => $product->getId()
                ]; // Mettez à jour avec le nom réel de votre propriété
            }
            return new JsonResponse($response);
        }
        $listeStocks = $listeStockRep->findBy(['lieuVente' => $lieu_vente]);

        $first_product = $productRep->findOneBy([]);
        $id_product =  $request->get('id_product_search', $first_product);
        $product = $productRep->find($id_product); 
        if ($request->get("magasin") == 'general') {
            $mouvements = $mouvementRep->findProductMouvGeneral($product, $date1 , $date2, $lieu_vente);
            $quantiteInitial = $mouvementRep->sumQuantiteBeforeStartDate($product, $date1 , $lieu_vente);
            $stockProduct = $stockRep->sumQuantiteProduct($product, $listeStocks);
            $entrees = $mouvementRep->sumQuantiteSup($product, $lieu_vente);
            $sorties = $mouvementRep->sumQuantiteInf($product, $lieu_vente);
        } else{
            $mouvements = $mouvementRep->findProductMouvByStock($magasin,$product, $date1 , $date2);

            $quantiteInitial = $mouvementRep->sumQuantiteBeforeStartDateByStock($magasin,$product, $date1);
            $stockProduct = $stockRep->sumQuantiteProduct($product, $magasin);
            $entrees = $mouvementRep->sumQuantiteSupByStock($product, $magasin);
            $sorties = $mouvementRep->sumQuantiteInfByStock($product, $magasin);
        } 

        return $this->render('nhema/stock/mouvement_product/index.html.twig', [
            'entreprise' => $entrepriseRep->find(1),
            'lieu_vente' => $lieu_vente,
            'search' => $search,
            'magasin' => $magasin ? $magasin : 'general',
            'mouvements' => $mouvements,
            'liste_stocks' => $listeStocks,
            'product' => $product,
            'quantite_init' => $quantiteInitial,
            'stock_product' => $stockProduct,
            'entrees' => $entrees,
            'sorties' => $sorties,
        ]);
    }
}
