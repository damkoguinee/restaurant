<?php

namespace App\Controller\Nhema\Admin;

use App\Entity\Recette;
use App\Entity\Cocktail;
use App\Entity\CocktailRecette;
use App\Form\CocktailRecetteType;
use App\Repository\BoissonRepository;
use App\Repository\RecetteRepository;
use App\Repository\CocktailRepository;
use App\Repository\EntrepriseRepository;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LieuxVentesRepository;
use App\Repository\CocktailRecetteRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/nhema/admin/cocktail/recette')]
class CocktailRecetteController extends AbstractController
{
    #[Route('/', name: 'app_nhema_admin_cocktail_recette_index', methods: ['GET'])]
    public function index(CocktailRecetteRepository $cocktailRecetteRepository): Response
    {
        return $this->render('nhema/admin/cocktail_recette/index.html.twig', [
            'cocktail_recettes' => $cocktailRecetteRepository->findAll(),
        ]);
    }

    #[Route('/new/{cocktail}', name: 'app_nhema_admin_cocktail_recette_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Cocktail $cocktail, ProductRepository $productRep, RecetteRepository $recetteRep, IngredientRepository $ingredientRep, BoissonRepository $boissonRep, EntityManagerInterface $entityManager, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep): Response
    {
        if ($request->get('id_cocktail')) {
            $quantite = $request->get('quantite');
            $verif_recette = $recetteRep->findOneBy(['product' => $request->get('id_cocktail'), 'ingredient' => $request->get('id_ingredient')]);
            if ($verif_recette) {
                // si la recette existe on la modifie
                $recette = $verif_recette;
                $recette->setQuantite($quantite)
                        ->setDescription($request->get('description'));
            }else{
                $recette = new Recette();
                $recette->setProduct($productRep->find($request->get('id_cocktail')))
                        ->setIngredient($productRep->find($request->get('id_ingredient')))
                        ->setQuantite($quantite)
                        ->setDescription($request->get('description'));
            }
            

            $entityManager->persist($recette);
            $entityManager->flush();
            $this->addFlash("success", "ingrédient ajouté avec succés :)");
            return new RedirectResponse($this->generateUrl('app_nhema_admin_cocktail_recette_new', ['cocktail' => $cocktail->getId()]));           

        }

        if ($request->get("search")){
            $search = $request->get("search");
        }else{
            $search = "";
        }
        if ($request->get("type_ingredient")){
            $type_ingredient = $request->get("type_ingredient");
        }else{
            $type_ingredient = "boissons";
        }
        $pageEncours = $request->get('pageEncours', 1);
        if ($type_ingredient == 'boissons') {
            $listeingredients = $boissonRep->listeDesBoissonsPagination($search, $pageEncours, 5); 
        }else{
            $listeingredients = $ingredientRep->listeDesIngredientsPagination($search, $pageEncours, 5); 

        }

        return $this->render('nhema/admin/cocktail_recette/index.html.twig', [
            'entreprise' => $entrepriseRep->find(1),
            'lieux_ventes' => $lieuVenteRep->findAll(),
            'ingredients' => $listeingredients,
            'recettes' => $recetteRep->findBy(['product' => $cocktail]),
            'search' => $search,
            'type_ingredient' => $type_ingredient,
            'cocktail' => $cocktail,
        ]);
    }

    #[Route('/{id}', name: 'app_nhema_admin_cocktail_recette_show', methods: ['GET'])]
    public function show(CocktailRecette $cocktailRecette): Response
    {
        return $this->render('nhema/admin/cocktail_recette/show.html.twig', [
            'cocktail_recette' => $cocktailRecette,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_nhema_admin_cocktail_recette_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CocktailRecette $cocktailRecette, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CocktailRecetteType::class, $cocktailRecette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_nhema_admin_cocktail_recette_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/admin/cocktail_recette/edit.html.twig', [
            'cocktail_recette' => $cocktailRecette,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_nhema_admin_cocktail_recette_delete', methods: ['POST'])]
    public function delete(Request $request, Recette $recette, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recette->getId(), $request->request->get('_token'))) {
            $entityManager->remove($recette);
            $entityManager->flush();
            $this->addFlash("success", "ingrédient supprimé avec succés :)");

        }

        return $this->redirectToRoute('app_nhema_admin_cocktail_recette_new', ['cocktail' => $recette->getProduct()->getId()], Response::HTTP_SEE_OTHER);
    }
}
