<?php

namespace App\Controller\Nhema\Admin;

use App\Entity\Chicha;
use App\Entity\Recette;
use App\Entity\ChichaRecette;
use App\Form\ChichaRecetteType;
use App\Repository\ChichaRepository;
use App\Repository\ProductRepository;
use App\Repository\RecetteRepository;
use App\Repository\EntrepriseRepository;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LieuxVentesRepository;
use App\Repository\ChichaRecetteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/nhema/admin/chicha/recette')]
class ChichaRecetteController extends AbstractController
{
    #[Route('/', name: 'app_nhema_admin_chicha_recette_index', methods: ['GET'])]
    public function index(ChichaRecetteRepository $chichaRecetteRepository): Response
    {
        return $this->render('nhema/admin/chicha_recette/index.html.twig', [
            'chicha_recettes' => $chichaRecetteRepository->findAll(),
        ]);
    }

    #[Route('/new/{chicha}', name: 'app_nhema_admin_chicha_recette_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Chicha $chicha, ProductRepository $productRep, RecetteRepository $recetteRep, IngredientRepository $ingredientRep, EntityManagerInterface $entityManager, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep): Response
    {
        if ($request->get('id_chicha')) {
            $quantite = $request->get('quantite');
            $verif_recette = $recetteRep->findOneBy(['product' => $request->get('id_chicha'), 'ingredient' => $request->get('id_ingredient')]);
            if ($verif_recette) {
                // si la recette existe on la modifie
                $recette = $verif_recette;
                $recette->setQuantite($quantite)
                        ->setDescription($request->get('description'));
            }else{
                $recette = new Recette();
                $recette->setProduct($productRep->find($request->get('id_chicha')))
                        ->setIngredient($productRep->find($request->get('id_ingredient')))
                        ->setQuantite($quantite)
                        ->setDescription($request->get('description'));
            }

            $entityManager->persist($recette);
            $entityManager->flush();
            $this->addFlash("success", "ingrédient ajouté avec succés :)");
            return new RedirectResponse($this->generateUrl('app_nhema_admin_chicha_recette_new', ['chicha' => $chicha->getId()]));           

        }

        if ($request->get("search")){
            $search = $request->get("search");
        }else{
            $search = "";
        }
        $pageEncours = $request->get('pageEncours', 1);
        $listeingredients = $ingredientRep->listeDesIngredientsPagination($search, $pageEncours, 5); 

        return $this->render('nhema/admin/chicha_recette/index.html.twig', [
            'entreprise' => $entrepriseRep->find(1),
            'lieux_ventes' => $lieuVenteRep->findAll(),
            'ingredients' => $listeingredients,
            'recettes' => $recetteRep->findBy(['product' => $chicha]),
            'search' => $search,
            'chicha' => $chicha,
        ]);
    }

    #[Route('/{id}', name: 'app_nhema_admin_chicha_recette_show', methods: ['GET'])]
    public function show(ChichaRecette $chichaRecette): Response
    {
        return $this->render('nhema/admin/chicha_recette/show.html.twig', [
            'chicha_recette' => $chichaRecette,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_nhema_admin_chicha_recette_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ChichaRecette $chichaRecette, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChichaRecetteType::class, $chichaRecette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_nhema_admin_chicha_recette_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/admin/chicha_recette/edit.html.twig', [
            'chicha_recette' => $chichaRecette,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_nhema_admin_chicha_recette_delete', methods: ['POST'])]
    public function delete(Request $request, Recette $recette, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recette->getId(), $request->request->get('_token'))) {
            $entityManager->remove($recette);
            $entityManager->flush();
            $this->addFlash("success", "ingrédient supprimé avec succés :)");

        }

        return $this->redirectToRoute('app_nhema_admin_chicha_recette_new', ['chicha' => $recette->getProduct()->getId()], Response::HTTP_SEE_OTHER);
    }
}
