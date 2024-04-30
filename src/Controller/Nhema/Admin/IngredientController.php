<?php

namespace App\Controller\Nhema\Admin;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\EntrepriseRepository;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LieuxVentesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/nhema/admin/ingredient')]
class IngredientController extends AbstractController
{
    #[Route('/', name: 'app_nhema_admin_ingredient_index', methods: ['GET'])]
    public function index(Request $request, IngredientRepository $ingredientRepository, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep): Response
    {
        if ($request->get("search")){
            $search = $request->get("search");
        }else{
            $search = "";
        }
        $pageEncours = $request->get('pageEncours', 1);
        $listeingredients = $ingredientRepository->listeDesIngredientsPagination($search, $pageEncours, 15); 
        return $this->render('nhema/admin/ingredient/index.html.twig', [
            'ingredients' => $listeingredients,
            'entreprise' => $entrepriseRep->find(1),
            'lieux_ventes' => $lieuVenteRep->findAll(),
            'search' => $search
        ]);
    }

    #[Route('/new', name: 'app_nhema_admin_ingredient_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep,): Response
    {
        $ingredient = new ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $fichier = $form->get("image")->getData();
            if ($fichier) {
                $nomFichier= pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                $slugger = new AsciiSlugger();
                $nouveauNomFichier = $slugger->slug($nomFichier);
                $nouveauNomFichier .="_".uniqid();
                $nouveauNomFichier .= "." .$fichier->guessExtension();
                $fichier->move($this->getParameter("dossier_img_ingredients"),$nouveauNomFichier);
                $ingredient->setImage($nouveauNomFichier);
            }
            $entityManager->persist($ingredient);
            $entityManager->flush();
            $this->addFlash("success","ingredient ajouté avec succès :)");
            if ($request->get('retour')) {
                return new RedirectResponse($request->get('retour'));
            }else{
                return $this->redirectToRoute('app_nhema_admin_ingredient_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('nhema/admin/ingredient/new.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form,
            'entreprise' => $entrepriseRep->find(1),
            'lieux_ventes' => $lieuVenteRep->findAll(),
            'referer' => $request->headers->get('referer'),
        ]);
    }

    #[Route('/{id}', name: 'app_nhema_admin_ingredient_show', methods: ['GET'])]
    public function show(Ingredient $ingredient, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep,): Response
    {
        return $this->render('nhema/admin/ingredient/show.html.twig', [
            'ingredient' => $ingredient,
            'entreprise' => $entrepriseRep->find(1),
            'lieux_ventes' => $lieuVenteRep->findAll(),
        ]);
    }

    #[Route('/edit/{id}', name: 'app_nhema_admin_ingredient_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ingredient $ingredient, EntityManagerInterface $entityManager, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep,): Response
    {
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image =$form->get("image")->getData();
            if ($image) {
                if ($ingredient->getImage()) {
                    $ancienImage=$this->getParameter("dossier_img_ingredients")."/".$ingredient->getImage();
                    if (file_exists($ancienImage)) {
                        unlink($ancienImage);
                    }
                }
                $nomImage= pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $slugger = new AsciiSlugger();
                $nouveauNomImage = $slugger->slug($nomImage);
                $nouveauNomImage .="_".uniqid();
                $nouveauNomImage .= "." .$image->guessExtension();
                $image->move($this->getParameter("dossier_img_ingredients"),$nouveauNomImage);
                $ingredient->setImage($nouveauNomImage);

            }
            $entityManager->flush();
            $this->addFlash("success","ingredient amodifié avec succès :)");
            return $this->redirectToRoute('app_nhema_admin_ingredient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nhema/admin/ingredient/edit.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form,
            'entreprise' => $entrepriseRep->find(1),
            'lieux_ventes' => $lieuVenteRep->findAll(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_nhema_admin_ingredient_delete', methods: ['POST'])]
    public function delete(Request $request, Ingredient $ingredient, EntityManagerInterface $entityManager, EntrepriseRepository $entrepriseRep, LieuxVentesRepository $lieuVenteRep,): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ingredient->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ingredient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_nhema_admin_ingredient_index', [], Response::HTTP_SEE_OTHER);
    }
}
