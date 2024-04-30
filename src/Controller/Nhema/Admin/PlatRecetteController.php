<?php

namespace App\Controller\Nhema\Admin;

use App\Entity\Plat;
use App\Entity\PlatRecette;
use App\Entity\Recette;
use App\Form\PlatRecetteType;
use App\Repository\PlatRepository;
use App\Repository\RecetteRepository;
use App\Repository\EntrepriseRepository;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LieuxVentesRepository;
use App\Repository\PlatRecetteRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/nhema/admin/plat/recette')]
class PlatRecetteController extends AbstractController
{
    #[Route('/', name: 'app_nhema_admin_plat_recette_index', methods: ['GET'])]
    public function index(PlatRecetteRepository $platRecetteRepository): Response
    {
        return $this->render('nhema/admin/plat_recette/index.html.twig', [
            'plat_recettes' => $platRecetteRepository->findAll(),
        ]);
    }

    #[Route('/new/{plat}', name: 'app_nhema_admin_plat_recette_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Plat $plat, ProductRepository $productRep, RecetteRepository $recetteRep, IngredientRepository $ingredientRep, EntityManagerInterface $entityManager, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep): Response
    {
        if ($request->get('id_plat')) {
            $quantite = $request->get('quantite');
            $verif_recette = $recetteRep->findOneBy(['product' => $request->get('id_plat'), 'ingredient' => $request->get('id_ingredient')]);
            if ($verif_recette) {
                // si la recette existe on la modifie
                $recette = $verif_recette;
                $recette->setQuantite($quantite)
                        ->setDescription($request->get('description'));
            }else{
                $recette = new Recette();
                $recette->setProduct($productRep->find($request->get('id_plat')))
                        ->setIngredient($productRep->find($request->get('id_ingredient')))
                        ->setQuantite($quantite)
                        ->setDescription($request->get('description'));
            }

            $entityManager->persist($recette);
            $entityManager->flush();
            $this->addFlash("success", "ingrédient ajouté avec succés :)");
            return new RedirectResponse($this->generateUrl('app_nhema_admin_plat_recette_new', ['plat' => $plat->getId()]));           

        }

        if ($request->get("search")){
            $search = $request->get("search");
        }else{
            $search = "";
        }
        $pageEncours = $request->get('pageEncours', 1);
        $listeingredients = $ingredientRep->listeDesIngredientsPagination($search, $pageEncours, 5); 
        // $listeingredients = $productRep->listeDesProduitsPagination($search, $pageEncours, 5); 

        return $this->render('nhema/admin/plat_recette/index.html.twig', [
            'entreprise' => $entrepriseRep->find(1),
            'lieux_ventes' => $lieuVenteRep->findAll(),
            'ingredients' => $listeingredients,
            'recettes' => $recetteRep->findBy(['product' => $plat]),
            'search' => $search,
            'plat' => $plat,
        ]);
    }

    #[Route('/{id}', name: 'app_nhema_admin_plat_recette_show', methods: ['GET'])]
    public function show(PlatRecette $platRecette): Response
    {
        return $this->render('nhema/admin/plat_recette/show.html.twig', [
            'plat_recette' => $platRecette,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_nhema_admin_plat_recette_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PlatRecette $platRecette, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlatRecetteType::class, $platRecette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_nhema_admin_plat_recette_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/admin/plat_recette/edit.html.twig', [
            'plat_recette' => $platRecette,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_nhema_admin_plat_recette_delete', methods: ['POST'])]
    public function delete(Request $request, Recette $recette, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recette->getId(), $request->request->get('_token'))) {
            $entityManager->remove($recette);
            $entityManager->flush();
            $this->addFlash("success", "ingrédient supprimé avec succés :)");

        }

        return $this->redirectToRoute('app_nhema_admin_plat_recette_new', ['plat' => $recette->getProduct()->getId()], Response::HTTP_SEE_OTHER);
    }
}
